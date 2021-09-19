<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('templates')"
                    :directory="[$t('proposals'),$t('templates')]"
                    :icon="'hexagon'"
                />
            </div>
            <div class="col-sm-12 col-md-6" v-if="$can('create_templates')">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button type="button"
                            class="btn btn-primary btn-with-shadow"
                            data-toggle="modal"
                            @click="addTemplate()">
                        {{ $t('add_template') }}
                    </button>
                </div>
            </div>
        </div>

        <app-card-view
            id="template-card"
            :properties="properties"
            @action="getActions"
        />

        <app-template-preview-modal
            :content="selectedTemplateContent"
            :template-subject="templateSubject"
            modalId="template-preview"
            @close="isPreviewModalActive = false"
            v-if="isPreviewModalActive"
        />
        <!--Delete Confirmation Modal -->
        <app-confirmation-modal
            v-if="showDeleteModal"
            :modal-id="'delete-example'"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "TemplateView",
    mixins: [FormMixin],
    data() {
        return {
            urlGenerator,
            selectedCard: {},
            template: '',
            tableId: "template-card",
            selectUrl: '',
            copyUrl: '',
            formData: {},
            selectedTemplateContent: '',
            templateSubject: '',
            isPreviewModalActive: false,
            showDeleteModal: false,
            properties: {
                filters: [
                    {
                        title: "Created date",
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    {
                        title: this.$t('owner'),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: this.$can('manage_public_access') ? true : false
                    },
                ],
                showClearFilter: true,
                showFilter: true,
                url: route('templates.index'),
                customContentKey: 'custom_content',
                defaultContentKey: 'default_content',
                subjectKey: 'subject',
                showAction: true,
                showSearch: true,
                previewType: "image",
                previewImageKey: {
                    "relation": 'thumbnail',
                    "key": 'path'
                },
                actions: [
                    {
                        title: this.$t('preview'),
                        icon: "eye",
                        type: "modal",
                        modalId: "Preview"
                    },
                    {
                        title: this.$t('edit'),
                        icon: "edit",
                        type: "page",
                        modalId: "Edit",
                        modifier: () => this.$can("update_templates") ? true : false
                    },
                    {
                        title: this.$t('delete'),
                        icon: "trash",
                        type: "modal",
                        modalId: "delete-modal",
                        modifier: () => this.$can("delete_templates") ? true : false
                    },
                    {
                        title: this.$t('duplicate'),
                        icon: "copy",
                        type: "page",
                        modalId: "Duplicate",
                        modifier: () => this.$can("copy_templates") ? true : false
                    },
                    {
                        title: this.$t('add_to_proposal'),
                        icon: "zap",
                        type: "page",
                        modalId: "add-to-proposal",
                        modifier: () => this.$can("add_proposals") ? true : false
                    },
                    {
                        title: this.$t('download'),
                        icon: "download",
                        type: "page",
                        modalId: "download",
                    }
                ],
                cardLimit: 10
            }
        }
    },

    created() {
        this.setOwnerFilterOption();
        this.getStatus();
    },
    methods: {
        setOwnerFilterOption() {
            this.axiosGet(route('crm.auth_user')).then(response => {
                let user = this.properties.filters.find(elsement => elsement.title === this.$t('owner'));
                user.option = response.data.map(users => {
                    return {
                        id: users.id,
                        value: users.full_name
                    }
                });
            })
        },
        addTemplate() {
            window.open(route('templates.create'), '_self');
        },
        getActions(card, actionObj, active) {
            this.selectedCard = card;
            this.template = card.id;
            if (actionObj.type == 'page') {
                if (actionObj.modalId == this.$t('edit')) {
                    this.selectUrl = route('templates.edit', {'id': this.template});
                    window.open(this.selectUrl, '_self');
                } else if (actionObj.modalId == this.$t('duplicate')) {
                    this.copyUrl = route('templates.copy', {'id': this.template});
                    window.open(this.copyUrl, '_self');
                } else if (actionObj.modalId == 'add-to-proposal') {
                    this.formData.subject = this.selectedCard.subject;
                    if (this.selectedCard.custom_content) {
                        this.formData.content = this.selectedCard.custom_content;
                    } else {
                        this.formData.content = this.selectedCard.default_content;
                    }
                    //this.formData.content = 'hoise';
                    this.formData.owner_id = null;
                    this.submitFromFixin(
                        'post',
                        route('proposals.add'),
                        this.formData
                    );
                } else if (actionObj.modalId == 'download') {
                    const content = this.selectedCard.custom_content ? this.selectedCard.custom_content : this.selectedCard.default_content;
                    this.downloadTemplate(content, this.selectedCard.subject);
                }
            } else if (actionObj.type == 'modal') {
                if (actionObj.modalId == 'delete-modal') {
                    this.showDeleteModal = true;
                } else if (actionObj.modalId == this.$t('preview')) {
                    this.templateSubject = this.selectedCard.subject;
                    if (this.selectedCard.custom_content) {
                        this.selectedTemplateContent = this.selectedCard.custom_content;
                    } else {
                        this.selectedTemplateContent = this.selectedCard.default_content;
                    }
                    this.openModal();
                }
            }

        },
        downloadTemplate(content, name) {
            let a = document.createElement('a');
            a.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(content)
            a.download = name + '.html'
            a.style.display = 'none';
            document.body.appendChild(a).click();
            document.body.removeChild(a);
        },
        confirmed() {
            let url = route('templates.destroy', {'id': this.template});
            this.axiosDelete(url)
                .then(response => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit('reload-' + this.tableId);
                }).catch(({error}) => {
                //trigger after error
            }).finally(() => {
                this.cancelled();
            });
            this.showDeleteModal = false;
        },
        cancelled() {
            this.showDeleteModal = false;
        },
        openModal() {
            this.isPreviewModalActive = true;
        },

        closeModal() {
            this.isPreviewModalActive = false;
            $('#template-preview').modal('hide');
        },

        afterError(response) {
            if (response) {
                if (response.data.errors) {
                    this.$toastr.e(response.data.message);
                }
            }
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            window.location.replace(route('proposals.lists'))
        },
        afterFinalResponse() {
            this.formData = '';
        },
        getStatus() {
            this.axiosGet(
                route(`statuses.index`, {_query: {name: "status_draft", type: "proposal"}}))
                .then((res) => {
                    this.formData.status_id = collect(res.data).first().id;
                });
        },

    }
}
</script>
