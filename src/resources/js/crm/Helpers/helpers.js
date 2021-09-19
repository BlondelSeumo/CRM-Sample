import Vue from 'vue'
import moment from "moment";
import Collection from "./Collection";
import Permission from "./Permission";
import AppFunction from "../../core/helpers/app/AppFunction";
export const optional = (obj, ...props) => {
    if(!obj || typeof obj !== 'object')
        return undefined;
    const val = obj[props[0]];
    if(props.length === 1 || !val) return val;
    const rest = props.slice(1);
    return optional.apply(null, [val, ...rest])
};

Vue.prototype.$optional = (obj, ...props) => {
    return optional(obj, ...props);
};

Vue.prototype.$errorMessage = (errorObject, field, isArray = true) => {
    if (!Object.keys(errorObject).length)
        return '';
    if (isArray){
        let error = errorObject[field]
        if (error){
            return error[0];
        }
        return '';
    }
    return  errorObject[field];
};

export const configFormatter = (format) => {
    return {
        id: format,
        value: Vue.prototype.$t(format)
    }
};

export const textTruncate = (str, length, ending) => {
    if (length == null) {
        length = 50;
    }
    if (ending == null) {
        ending = '...';
    }
    str = str.replace(/(<([^>]+)>)/ig, '');
    str = str.replace(/&nbsp;/g, ' ');
    if (str.length > length) {
        return str.substring(0, length - ending.length) + ending;
    } else {
        return str;
    }
};

export const formDataAssigner = function (formData = new FormData, dataObject) {
    Object.keys(dataObject).map((key) => {
        if (dataObject[key] && !dataObject[key].length > 0 && Object.keys(dataObject[key]).length > 0) {
            Object.keys(dataObject[key]).map(childKey => {
                return formData.append(key+`[${childKey}]`, dataObject[key][childKey]);
            })
        }else if (Array.isArray(dataObject[key])) {
            dataObject[key].map((el, index) => {
                Object.keys(el).map(objectKeys => {
                    formData.append(key+`[${index}][${objectKeys}]`, el[objectKeys]);
                });
            })
        }else{
            return formData.append(key, dataObject[key]);
        }
    });
    return formData;
};

export const date_format = () => {
    return {
        'd-m-Y': 'DD-MM-YYYY',
        'm-d-Y': 'MM-DD-YYYY',
        'Y-m-d': 'YYYY-MM-DD',
        'm/d/Y': 'MM/DD/YYYY',
        'd/m/Y': 'DD/MM/YYYY',
        'Y/m/d': 'YYYY/MM/DD',
        'm.d.Y': 'MM.DD.YYYY',
        'd.m.Y': 'DD.MM.YYYY',
        'Y.m.d': 'YYYY.MM.DD',
    };
};
export const formatted_date = () => {
    return date_format()[optional(window.settings, 'date_format')];
};

export const formatted_time = () => {
    return optional(window.settings, 'time_format') === 'h' ? '12' : '24';
}

export const time_format = () => {
    const format = optional(window.settings, 'time_format');

    return format === 'h' ? `${window.settings.time_format}:mm A` : `${window.settings.time_format}:mm`;
}
export const formatDateToLocal = (date, withTime = false, time = null) => {
    if (!date)
        return '';
    if (!time){
        withTime = false;
    }
    const formatString = withTime ? `${formatted_date()} ${time_format()}` : formatted_date();

    if (time){
        return  moment(`${date} ${time}`).utc(false)
            .local()
            .format(formatString);
    }
    return  moment(date).utc(false)
        .local()
        .format(formatString);
};

export const formatDateTimeToLocal = (date) => {
    const formatString = `${formatted_date()} ${time_format()}`;
    return  moment(date).utc(false)
        .local()
        .format(formatString);
};

export const timeInterval = (date) => {
    return moment(date).utc(true).fromNow();
};

export const onlyTime = date => {
    return  moment(date).utc(false)
        .local()
        .format(`${time_format()}`);
};
export const onlyTimeFromTime = (date, time) => {
    let d = new Date(
        date + " " + time
    );
    return time
        ? moment(d).format(`${time_format()}`)
        : "";
};

export const calenderTime = date => {
    const days = moment(date).diff(moment.now(), 'days');
    if (moment(date).format('YYYY') < moment().format('YYYY')) {
        return  moment(date).format('DD MMM, YY')
    }
    if (days < -7) {
        return  moment(date).format('DD MMM')
    }
    return moment().calendar(date, {
        sameDay: '['+Vue.prototype.$t('today')+']',
        nextDay: '['+Vue.prototype.$t('tomorrow')+']',
        nextWeek: '['+Vue.prototype.$t('next_week')+']',
        lastDay: '['+Vue.prototype.$t('yesterday')+']',
        lastWeek: '['+Vue.prototype.$t('last')+'] dddd',
        sameElse: `${date_format()[window.settings.date_format]}`
    });
};

export const getThousandSeparator = () => {
    return window.settings.thousand_separator == 'space' ? ' ' :  window.settings.thousand_separator;
}

export const numberFormatter = number => {
    if (!isNaN(parseFloat(number))) {
        number = parseFloat(number).toFixed(getNumberOfDecimal());
        let parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, getThousandSeparator());
        return parts.join(getDecimalSeparator());
    }
    return 0;
}
export const getCurrencySymbol = ()=> {
    return window.settings.currency_symbol ? window.settings.currency_symbol : ' '
}
export const getCurrencyPosition = ()=> {
    return window.settings.currency_position ? window.settings.currency_position : ' '
}
export const getDecimalSeparator = ()=> {
    return window.settings.decimal_separator ? window.settings.decimal_separator : ' '
}
export const getNumberOfDecimal = ()=> {
    return window.settings.number_of_decimal ? window.settings.number_of_decimal : ' '
}
export const numberWithCurrencySymbol = number => {
    let modifiedValue;
    let formattedNumber = numberFormatter(number).toString();
    let currencySymbol = getCurrencySymbol().toString();

    if (getCurrencyPosition() == 'prefix_with_space'){
        modifiedValue = currencySymbol + ' ' + formattedNumber;
    } else if (getCurrencyPosition() == 'prefix_only'){
        modifiedValue = currencySymbol + formattedNumber;
    } else if (getCurrencyPosition() == 'suffix_with_space') {
        modifiedValue = formattedNumber + ' ' + currencySymbol;
    } else{
        modifiedValue = formattedNumber + currencySymbol;
    }
    return modifiedValue;
}

export const dealAgeHumanize = (createdAt, updatedAt, status) => {
    let checkDealStatus = status.name == 'status_open' ? true : false;
    let dateTime = '';
    if (checkDealStatus){
        dateTime = Math.abs(
            new Date(createdAt) - new Date()
        );
    }else {
        dateTime = Math.abs(
            new Date(updatedAt) - new Date(createdAt)
        );
    }

    let days = parseInt(dateTime / (1000 * 60 * 24 * 60));
    let minutes = (parseInt(dateTime / (1000 * 60)) % (24 * 60));
    let hours = minutes > 60 ? parseInt(minutes / 60) : 0;
    let minute = minutes > 60 ? (minutes % 60) : minutes;

    return days + " days " + hours + " hours " + minute + " minutes ";
};

export const reportDealsAvgAge = (dateTime) => {
    let days = parseInt(dateTime / (60 * 24 * 60));
    let minutes = (parseInt(dateTime / 60) % (24 * 60));
    let hours = minutes > 60 ? parseInt(minutes / 60) : 0;
    let minute = minutes > 60 ? (minutes % 60) : minutes;

    return days + " days " + hours + " hours " + minute + " minutes ";
};

export const companyName = ()=> {
    return window.settings.company_name ? window.settings.company_name : ' '
}

export const copyRightText = ()=> {
    let date = new Date();
    return 'Copyright @ ' + date.getFullYear() + ' by ' + companyName();
}

export const textEditorHints = tags => {
    return {
        words: tags,
        match: /\B{(\w*)$/,
        search: function (keyword, callback) {
            callback($.grep(this.words, function (item) {
                return item.includes(keyword);
            }));
        }
    }
}

export const urlGenerator =  (url) => {
    return url.includes(AppFunction.getBaseUrl()) ? url :`${AppFunction.getBaseUrl()}/${url.split('/').filter(d => d).join('/')}`;
};

Vue.prototype.$can = ability => (new Permission()).can(ability);

export const ucFirst = string => {
    if (string) {
        return String(string)[0].toUpperCase() + String(string).substring(1)
    }
}

export const collection = list => new Collection(list);

Vue.prototype.collection = list => collection(list);
