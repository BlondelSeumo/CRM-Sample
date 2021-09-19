export default {
    data(){
        return{
            cardColumns: [
                {
                    title: this.$t('lead'),
                    key: 'lead',
                    isVisible: true
                },
                {
                    title: this.$t('owner'),
                    key: 'owner',
                    isVisible: true
                },
                {
                    title: this.$t('value'),
                    key: 'value',
                    isVisible: true
                },
                {
                    title: this.$t('tags'),
                    key: 'tags',
                    isVisible: true
                },
                {
                    title: this.$t('todo_activity'),
                    key: 'todoActivity',
                    isVisible: true
                },
                {
                    title: this.$t('done_activity'),
                    key: 'doneActivity',
                    isVisible: true
                },
                {
                    title: this.$t('proposal'),
                    key: 'proposal',
                    isVisible: true
                },
                {
                    title: this.$t('discussions'),
                    key: 'discussion',
                    isVisible: true
                },
                {
                    title: this.$t('created_date'),
                    key: 'createdDate',
                    isVisible: true
                },

            ],
            cardDataManager:{
                lead: true,
                owner: true,
                value: true,
                tags: true,
                todoActivity: true,
                doneActivity: true,
                proposal: true,
                discussion: true,
                createdDate: true,
            },
        }
    },
    computed:{
        initColumns(){
            let initColumn = this.cardColumns.map((column)=>{
                return {
                    ...column,
                    isVisible: true
                }
            });
            return initColumn;
        }
    },
    mounted(){
        this.getCookies();
    },
    methods:{
        setCookies(data){
            let cookiesData = JSON.stringify(data); JSON.stringify
            AppCookie.set('kanban-view-manager', cookiesData);
        },
        getCookies(){
            let columnCookies = AppCookie.get('kanban-view-manager');
            if(columnCookies){
                this.cardColumns = JSON.parse(columnCookies);
                this.getColumnsOptionsInit(this.cardColumns);
            }
        },
        getColumnsOptions(data){
            this.setCookies(data);
            data.forEach(item=>{
                this.cardDataManager[item.key] = item.isVisible;
            })
        },
        getColumnsOptionsInit(data){
            data.forEach(item=>{
                this.cardDataManager[item.key] = item.isVisible;
            })
        },
    }
}