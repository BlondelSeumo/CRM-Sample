<template>
    <app-modal
        :modal-id="modalId"
        modalSize="fullscreen"
        @close-modal="closeModal"
    >
        <template slot="header">
            <h5 class="modal-title">
                <app-icon name="cpu" /> {{$t('template_gallery')}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="close-btn"> <app-icon name="x"/></span>
            </button>
        </template>
        <template slot="body">
            <div class="row">
                <div class="col-12">
                    <h4>{{`${$t('our_template')} ${$t('gallery')}`}}</h4>
                    <app-card-view
                            id="template-choose-modal"
                            :properties="properties"
                            @selectedTemplateCard="selectTemplate"
                    />
                </div>
            </div>
        </template>
    </app-modal>
</template>

<script>

    export default {
        name: "ChooseTemplatesModal",
        props: {
            properties: {
                type: Object,
                default() {
                    return {
                        filters: [],
                        showFilter: false,
                        url: route('templates.index'),
                        customContentKey: 'custom_content',
                        defaultContentKey: 'default_content',
                        showAction: false,
                        cardLimit: 5,
                        previewType: "image",
                        previewImageKey: {
                          "relation": 'thumbnail',
                          "key": 'path'
                        },
                    }
                }
            },
            modalId: {
                type: String,
                require: true
            }
        },
        methods:{
            selectTemplate(payload) {
                this.$emit('selected', payload)
                this.closeModal()
            },
            closeModal() {
                this.$emit('close')
                $('#' + this.modalId).modal('hide')
            }
        }
    }
</script>

<style scoped>

</style>
