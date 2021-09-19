import { axiosGet } from "./AxiosHelper";
export class app {
    constructor(routePrefix) {
        this.dataPaginate = false;
        this.routePrefix = "";
    }
    prefix(routePrefix = "crm") {
        this.routePrefix = routePrefix;
        return this;
    }
    route(routeName) {
        this.routeName = routeName;
        return this;
    }
    params(params) {
        this.urlParams = new URLSearchParams(params);
        return this;
    }
    paginate() {
        this.dataPaginate = true;
        return this;
    }
    get(...props) {
        return axiosGet(
            `${this.routePrefix}${this.routeName}?${this.urlParams}`
        ).then(response => {
            let result = this.dataPaginate ? response.data.data : response.data,
                subset =
                    props.length < 1
                        ? result
                        : result.reduce((acc, item) => {
                            acc.push(
                                props.reduce((el, key) => {
                                    el[key] = item[key];
                                    return el;
                                }, {})
                            );
                            return acc;
                        }, []);
            return subset;
        });
    }
    pipeline(funcArr) {
        funcArr.forEach(obj => {
            if (Promise.resolve(obj) !== obj) {
                throw new Error(
                    "Expects all methods are passed in parameter array should return Promise"
                );
            }
        });
        return Promise.all(funcArr);
    }
}
export const api = new app();
