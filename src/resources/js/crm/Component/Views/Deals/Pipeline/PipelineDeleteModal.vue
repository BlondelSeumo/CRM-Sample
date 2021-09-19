<template>
    <app-modal
            modal-id="modalId"
            modal-size="default"
            modal-alignment="top"
            @close-modal="closeModal"
    >
        <template slot="header">
            <h5 class="modal-title">{{ $t('delete_pipeline:') }} <b>{{currentPipeLine.name}}</b></h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close" @click.prevent="closeModal">
                <span>
                    <app-icon name="x"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form ref="form">

                <div class="form-group">
                    <div class="row">
                        <div class="mb-2 col-sm-12">
                            <h5 style="color: red;">{{$t('opps!')}}</h5>
                        </div>
                        <div class="col-sm-12">
                            <h5>{{$t('what_about_the_deals_of_this_pipeline?')}}</h5>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <app-input
                                    type="radio"
                                    :list="[{id:1, value: 'Want to move these deals to another pipeline?'}]"
                                    v-model="formData.delete_option"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group" v-if="formData.delete_option == 1">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12 d-flex mb-1">
                                <b class="mr-2">{{currentPipeLine.name}}</b>
                                <app-icon name="corner-right-down"></app-icon>
                            </div>
                            <div class="col-sm-12">
                                <app-input
                                        type="select"
                                            list-value-field='name'
                                            :list="pipelineList"
                                            v-model="formData.pipeline_id"/>
                            </div>
                    </div>
                </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <app-input
                                    type="radio"
                                    :list="[{id:0, value: 'Or delete anyway'}]"
                                    v-model="formData.delete_option"
                            />
                        </div>
                    </div>
                </div>

            </form>
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
            {{ $t('move') }}
          </button>

            <button v-else
              :disabled="formData.delete_option ? false : true"
              type="button"
              class="btn btn-danger"
              data-dismiss="modal"
              @click.prevent="deletePipeline">
                {{ $t('delete') }}
            </button>

        </template>

    </app-modal>
</template>
<script>
    import {FormMixin} from "@core/mixins/form/FormMixin";
    export default {
        name: "PipelineDeleteModal",
        mixins: [FormMixin],
        props: {
            modalId:String,
            deletePipelineUrl:{
                type:String,
                required:true
            },
            pipelineId:{
                type:Number,
                required:true
            }
        },
        data(){
            return{
                pipelineList: [],
                formData: {},
                url: '',
                currentPipeLine:{}
            }
        },
        methods:{
            deletePipeline(value){
                if (this.formData.delete_option == 1){
                    this.url = this.deletePipelineUrl+'?move_to='+this.formData.pipeline_id;
                    this.axiosDelete(this.url)
                        .then(response => {
                            this.$toastr.s(response.data.message);
                            this.$hub.$emit('reload-'+this.modalId);
                          this.$hub.$emit('pipeline-delete-success');
                        }).catch(({error}) => {
                      //trigger after error
                      this.$toastr.e(error.data?.message);
                    }).finally(() => {
                        this.closeModal();
                    });
                } else if (this.formData.delete_option == 0) {
                  this.url = this.deletePipelineUrl;
                  this.axiosDelete(this.url)
                    .then(response => {
                      this.$toastr.s(response.data.message);
                      this.$hub.$emit('reload-' + this.modalId);
                      this.$hub.$emit('pipeline-delete-success');
                    }).catch(({error}) => {
                    //trigger after error
                    this.$toastr.e(error.data?.message);
                  }).finally(() => {
                    this.closeModal();
                  });
                }
            },

            getPipelines(){
                this.axiosGet(route('pipelines.index')).then(response =>{
                    this.pipelineList = response.data.data.filter(v => v.id!=this.$props.pipelineId);
                    this.currentPipeLine = response.data.data.filter(v => v.id==this.$props.pipelineId)[0];
                    this.pipelineList.unshift({
                        id: "",
                        disabled: true,
                        name: this.$t("choose_another_one"),
                    });
                })
            },
            closeModal(value){
                this.$emit('close-modal', value);
            },

        },

        mounted(){
            this.getPipelines();
        }
    }
</script>
