<template>
    <form class="mb-0">
        <div class="mb-primary">
            <div class="form-group">
                <div class="form-row">
                    <label class="col-3 mb-0 d-flex align-items-center">
                        {{ $t('call_delivery_time') }}
                    </label>
                    <div class="col-9">
                        <app-input type="time"
                                   v-model="callDeliveryTime"
                                   :placeholder="$t('choose_your_call_delivery_time')"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <label class="col-3 mb-0 d-flex align-items-center">
                        {{ $t('duration') }}
                    </label>
                    <div class="col-9">
                        <app-input type="select"
                                   :list="demoList"
                                   v-model="callDuration"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="form-row">
                    <label class="col-3 mb-0 d-flex align-items-center">
                        {{ $t('contact_person') }}
                    </label>
                    <div class="col-9">
                        <app-input type="select"
                                   :list="demoList"
                                   v-model="contactPerson"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-primary px-primary border-top mx-minus-primary">
            <button type="button" class="btn btn-primary mr-1">{{ $t('save') }}</button>
            <button type="button" class="btn btn-secondary">{{ $t('cancel') }}</button>
        </div>
    </form>
</template>

<script>
    import {FormMixin} from "@core/mixins/form/FormMixin";

    export default {
        name: "CallTab",
        mixins: [FormMixin],
        data() {
            return {
                callDeliveryTime: '',
                callDuration: '',
                contactPerson: '',
                demoList: [
                    {id: 1, value: 'Option 1'},
                    {id: 2, value: 'Option 2'},
                    {id: 3, value: 'Option 3'},
                    {id: 4, value: 'Option 4'},
                ]
            }
        }
    }
</script>
