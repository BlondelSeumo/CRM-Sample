<template>
    <app-modal modal-id="transfer-deal-modal" modal-size="default" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('transfer_deals') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close"
                    @click.prevent="closeModal">
                <span>
                    <app-icon name="x"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <div class="form-group">
                <div class="row">
                    <div class="mb-2 col-sm-12">
                        <h5 style="color: red;">{{ $t('opps!') }}</h5>
                    </div>
                    <div class="col-sm-12">
                        <h5>{{ $t('what_about_the_deals_of_user') }}</h5>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <app-input
                            type="radio"
                            :list="[{id:1, value: $t('want_to_move_these_deals_to_another_user')}]"
                            v-model="formData.delete_option"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group" v-if="formData.delete_option == 1">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <app-input
                                type="select"
                                list-value-field='full_name'
                                :placeholder="$t('choose_a_user')"
                                :list="systemRoleList"
                                v-model="formData.new_user_id"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <app-input
                            type="radio"
                            :list="[{id:0, value: $t('or_delete_anyway')}]"
                            v-model="formData.delete_option"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template slot="footer">
            <button
                type="button"
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>

            <button v-if="formData.delete_option == 1"
                    type="button"
                    class="btn btn-primary"
                    data-dismiss="modal"
                    @click.prevent="deletePipeline">

                <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                <template v-if="!loading">{{ $t('move') }}</template>
            </button>

            <button v-else
                    :disabled="formData.delete_option ? false : true"
                    type="button"
                    class="btn btn-danger"
                    data-dismiss="modal"
                    @click.prevent="deletePipeline">
                <span class="w-100">
                    <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                </span>
                <template v-if="!loading">{{ $t('delete') }}</template>
            </button>

        </template>

    </app-modal>
</template>

<script>

import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

export default {
    name: "TransferDealModal",
    mixins: [FormMixin],
    props: {
        userId: {},
        tableId: {
            type: String
        }
    },
    data() {
        return {
            formData: {},
            systemRoleList: [],
            userName: {},
            loading: false,
        }
    },
    methods: {
        systemRole() {
            this.axiosGet(route('role.system')).then(({data}) => {
                this.systemRoleList = data.filter(v => v.id != this.$props.userId);
                this.userName = data.filter(v => v.id == this.$props.userId)[0];
            });
        },
        deletePipeline() {
            this.loading = true;
            if (this.formData.delete_option == 1) {
                this.submitFromFixin('post', route('user.move', {id: this.userId}), this.formData);
            } else if (this.formData.delete_option == 0) {
                this.axiosDelete(route('user_delete', {id: this.userId})).then((response) => {
                    this.afterSuccess(response);
                }).catch(({error}) => {
                    this.$toastr.e("", this.$t("action_not_allowed"));
                });
            }
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.$hub.$emit('reload-role-modal');
            this.closeModal();
        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    },
    created() {
        this.systemRole();
    }
}
</script>
