import _ from 'lodash'

export const pipexRoute = {
    prefix:'',
    misc:{
        dashboard:'',
    },
    lead:{
        person:
            {
                // for Restful API
                create:'',
                read:'',
                update:'',
                delete:'',

                // others
                all:'',
                paginate:'',
            },
        organization:{
            // for Restful API
            create:'',
            read:'',
            update:'',
            delete:'',

            // others
            all:'',
            paginate:'',
        },
    },
    deal:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',

        // others
        all:'',
        paginate:'',
        file:{
            // for Restful API
            create:'',
            read:'',
            update:'',
            delete:'',

            // others
            all:'',
            paginate:'',
        },
        note:{
            // for Restful API
            create:'',
            read:'',
            update:'',
            delete:'',

            // others
            all:'',
            paginate:'',
        },
        bulk:{
            update:'',
            delete:''
        }
    },
    proposal:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',

        // others
        all:'',
        paginate:'',
        file:{
            // for Restful API
            create:'',
            read:'',
            update:'',
            delete:'',

            // others
            all:'',
            paginate:'',
        }
    },
    activity:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',

        // others
        all:'',
        paginate:'',
        dealType:'',
        personType:'',
        organizationType:''
    },
    reports:{
        deal:{
            chart:'',
            table:''
        },
        pipeline:{
            chart:'',
            table:''
        },
        proposal:{
            chart:'',
            table:''
        },
    },
    settings: {
        // for Restful API
        generalSettings: '/general-settings',
        appSettings: '/admin/app/settings',
        appCustomField: '/admin/app/custom-fields'
    },
    userAndRoles:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',
    },
    status:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',

        // others
        all:'',
        paginate:'',
    },
    tag:{
        index:'/tags'
    },
    stage:{
        index:'/stages',
    },
    pipeline:{
        // for Restful API
        create:'',
        read:'',
        update:'',
        delete:'',

        // others
        all:'',
        paginate:'',
    },
    get:function(path){
        let preparedPath = path.split('.').length % 2 ? path+'.index' : path;
        return this.prefix+_.get(this,preparedPath);
    }
}
