<template>
    <div class="content-wrapper">
        <div class="mb-3">
            <h4>{{ $t('download') }}</h4>
        </div>
        <div class="row">
            <div class="col-2 mb-primary" v-for="val in value">
                <a v-bind:href="exportUrl+'/'+val">
                    <div class="card card-with-shadow border-0 file-card">
                        <div class="card-body text-center">
                            <div class="icon-box mx-auto mb-primary">
                                <app-icon name="download"/>
                            </div>
                            <p class="default-font-color mb-0">{{ $t('sheet') }} - {{ val }}</p>
                            <p class="font-size-90 mb-0">
                                <span class="default-font-color">{{ $t('rows') }}:</span> <span
                                class="text-muted">{{ val * sheetNumber - perSheet + 1 }} - {{ val * perSheet }}</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>
<script>

import AppFunction from "../../../../core/helpers/app/AppFunction";

export default {
    name: "AppDataExport",
    props: {
        url: {
            required: true,
            type: String,
        },
        title: {
            required: true,
            type: String,
        },
        totalSheet: {
            required: true,
        },
        perSheet: {
            default: 1000,
        }
    },
    data() {
        return {
            value: Math.ceil(this.totalSheet),
            sheetNumber: this.perSheet,
            exportUrl: AppFunction.getAppUrl(this.url),
        }
    },
}
</script>
<style scoped lang="scss">
.file-card {
    transition: 0.1s ease-in-out;
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 100%;
        background-color: var(--base-color);
        svg {
            width: 22px;
            height: 22px;
            color: #4466F2;
        }
    }
    &:hover {
        transform: scale(1.02);
    }
}
</style>






