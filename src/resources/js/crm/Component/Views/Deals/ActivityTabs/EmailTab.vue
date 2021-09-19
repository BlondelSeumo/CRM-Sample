<template>
    <form class="mb-0">
        <div class="mb-primary">
            <div class="form-group">
                <div class="form-row">
                    <label class="col-3 mb-0 d-flex align-items-center">
                        {{ $t('email') }}
                    </label>
                    <div class="col-9">
                        <app-input type="text"
                                   v-model="email"
                                   :placeholder="$t('enter_email')"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <label class="col-3 mb-0 d-flex align-items-center">
                        {{ $t('subject') }}
                    </label>
                    <div class="col-9">
                        <app-input type="text"
                                   v-model="subject"
                                   :placeholder="$t('enter_subject')"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-0">
                <div class="form-row">
                    <label class="col-3 mb-0 h-fit-content">
                        {{ $t('body') }}
                    </label>
                    <div class="col-9">
                        <app-input type="textarea"
                                   v-model="body"
                                   :text-area-rows="6"
                                   :placeholder="$t('type_here')"/>
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
        name: "EmailTab",
        mixins: [FormMixin],
        data() {
            return {
                email: '',
                subject: '',
                body: ''
            }
        }
    }
</script>
