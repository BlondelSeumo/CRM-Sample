<template>
    <div class="column-filter single-filter">
        <div class="dropdown keep-inside-clicks-open">
            <button class="btn btn-filter" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <app-icon :name="'credit-card'"/>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-manage">
                <div class="btn-dropdown-close d-sm-none">
                    <span class="title">
                        {{ $t('manage_columns') }}
                    </span>
                    <span class="back float-right" @click.prevent="closeDropDown">
                        <app-icon :name="'x'"/>
                    </span>
                </div>
                <div class="dropdown-item">
                    <h6>{{ $t('want_to_manage_card') }}</h6>
                    <p class="text-justify mb-0 filter-subtitle-text">
                        {{ $t('please_enable_see_option_as_you_want') }}
                    </p>
                </div>

                <div class="dropdown-item manage-column-options custom-scrollbar pt-0">
                    <div v-for="(element,index) in list"
                         v-if="!isUndefined(element.title)"
                         :key="'manage-column-'+index"
                         class="row py-2">

                        <div class="col-12 d-flex justify-content-between">
                            <div class="content-type">
                                {{ element.title }}
                            </div>
                            <label class="custom-control border-switch mb-0 mr-3">
                                <input type="checkbox"
                                       class="border-switch-control-input"
                                       :checked="element.isVisible"
                                       @click="element.isVisible = !element.isVisible"
                                       :id="'switch-'+index">
                                <span class="border-switch-control-indicator"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider d-none d-sm-block"/>

                <filter-action @apply="applyColumnSort" @clear="clear"/>
            </div>
        </div>
    </div>
</template>

<script>
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {FilterMixin} from "../../../../core/components/filter/mixins/FilterMixin";
import FilterAction from "../../../../core/components/filter/FilterAction";

export default {
    name: "ManageCardColumns",
    components: {FilterAction},
    mixins: [FilterMixin],
    extends: CoreLibrary,
    props: {
        manageOption: {
            type: Array
        },
        initOption: {
            type: Array
        }
    },
    data() {
        return {
            list: []
        }
    },
    mounted() {
        this.list = _.cloneDeep(this.manageOption);
    },
    computed: {
        dragOptions() {
            return {
                animation: 300
            };
        }
    },
    watch: {
        initOption: function (data) {
            this.clear();
            this.$emit('get-columns-options', data);
        }
    },
    methods: {
        clear() {
            this.list = _.cloneDeep(this.initOption);
        },
        applyColumnSort() {
            this.$emit('get-columns-options', this.list);
            this.closeDropDown();
        }
    }
}
</script>
