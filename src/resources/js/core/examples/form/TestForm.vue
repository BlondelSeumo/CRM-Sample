<template>
    <div class="content-wrapper">
        <form ref="form" data-url="test-component" enctype="multipart/form-data">
            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>General Inputs</h4>
                </div>
                <div class="card-body">
                    <!--Text-->
                    <div class="form-group">
                        <app-input
                            type="text"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.textValue"
                        />
                    </div>

                    <!--Email-->
                    <div class="form-group">
                        <app-input
                            type="email"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.emailValue"
                        />
                    </div>

                    <!--Number-->
                    <div class="form-group">
                        <app-input
                            type="number"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.phone"
                        />
                    </div>

                    <!--Password-->
                    <div class="form-group">
                        <app-input
                            type="password"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="testFields.password"
                        />
                    </div>

                    <!--Currency-->
                    <div class="form-group">
                        <app-input
                            type="currency"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="currency"
                        />
                    </div>

                    <!--Decimal-->
                    <div class="form-group">
                        <app-input
                            type="decimal"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="decimalNumber"
                        />
                    </div>

                    <!--Tel Input-->
                    <div class="form-group">
                        <app-input
                            type="tel-input"
                            v-model="testNumber"
                            placeholder="Enter phone number"
                            :required="true"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Checkbox, Radios & Switches</h4>
                </div>
                <div class="card-body">
                    <!--Switch-->
                    <div class="form-group">
                        <app-input
                            type="switch"
                            :label="$t('Switch')"
                            v-model="switchValue"
                        />
                    </div>

                    <!--Checkbox-->
                    <div class="form-group">
                        <app-input
                            type="checkbox"
                            v-model="checkboxValue"
                            :list="multiSelectOptionList"
                        />
                    </div>

                    <!--Radio-->
                    <div class="form-group">
                        <app-input
                            type="radio"
                            radio-checkbox-name="test"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="radioBtn"
                        />
                    </div>

                    <!--Radio Buttons-->
                    <div class="form-group">
                        <app-input
                            type="radio-buttons"
                            @focusin="focusInShow"
                            @mousemove="mouseMoveShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="radioButtons"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Selections</h4>
                </div>
                <div class="card-body">
                    <!--Select-->
                    <div class="form-group">
                        <app-input
                            type="select"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="selectValue"
                        />
                    </div>

                    <!--Search & Select-->
                    <div class="form-group">
                        <app-input
                            type="search-select"
                            v-model="searchSelectValue"
                            :list="multiSelectOptionList"
                            placeholder="Search and select"
                            :required="true"
                        />
                    </div>

                    <!--Multiselect-->
                    <div class="form-group">
                        <app-input
                            type="multi-select"
                            v-model="multiSelect"
                            :list="multiSelectOptionList"
                        />
                    </div>

                    <!--Multi create-->
                    <div class="form-group">
                        <app-input
                            type="multi-create"
                            v-model="multiCreate"
                            :list="multiCreateOptionList"
                            list-value-field="name"
                            placeholder="Add variation here"
                            :multi-create-preloader="multiPreloader"
                            @storeData="testStoreData"
                        />
                    </div>

                    <!--Smart Select-->
                    <div class="form-group">
                        <app-input
                            label="smart-select"
                            type="smart-select"
                            :list="multiSelectOptionList"
                            v-model="smartSelect"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Pickers</h4>
                </div>
                <div class="card-body">
                    <!--Timepicker-->
                    <div class="form-group">
                        <app-input
                            type="time"
                            v-model="testFields.timeValue"
                        />
                    </div>

                    <!--Datepicker-->
                    <div class="form-group">
                        <app-input
                            type="date"
                            v-model="dateValue"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>File Uploads</h4>
                </div>
                <div class="card-body">
                    <!--Custom File-->
                    <div class="form-group">
                        <app-input
                            type="custom-file-upload"
                            :label="$t('change_image')"
                            v-model="customFile"
                        />
                    </div>

                    <!--File-->
                    <div class="form-group">
                        <app-input
                            type="file"
                            v-model="fileValue"
                            :label="'Choose file'"
                        />
                    </div>

                    <!--Dropzone-->
                    <div class="form-group">
                        <app-input
                            type="dropzone"
                            v-model="dropzoneValue"
                        />
                    </div>
                </div>
            </div>

            <div class="card card-with-shadow border-0 mb-primary">
                <div class="card-header primary-card-color p-primary">
                    <h4>Text area & Editors</h4>
                </div>
                <div class="card-body">
                    <!--Textarea-->
                    <div class="form-group">
                        <app-input
                            type="textarea"
                            @focusin="focusInShow"
                            @focusout="focusOutShow"
                            @click="showClickEvent"
                            @input="showInputEventValue"
                            v-model="textarea"
                        />
                    </div>

                    <!--Text Editor-->
                    <div class="form-group">
                        <app-input
                            type="text-editor"
                            :minimal="true"
                            v-model="textEditor"
                        />
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" type="submit" @click.prevent="submitData">Submit</button>
            <button class="btn btn-secondary" type="button" @click.prevent="changeData">Change Value(s)</button>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../mixins/form/FormMixin.js";

export default {
    name: "AddEditForm",
    mixins: [FormMixin],
    data() {
        return {
            demo: 1,
            switchValue: false,
            searchSelectValue: '',
            testNumber: '',
            test: '',
            testName: '',
            testPass: '',
            testPass1: '',
            decimalNumber: 300,
            emailErrorMessage: '',
            errorMessage: '',
            imageUrl: "/images/core.png",
            showError: false,
            dataLoaded: false,
            isRange: false,
            dateValue: new Date(),
            dateTimeValue: new Date(),
            rangeValue: {
                start: new Date(),
                end: new Date()
            },
            timeValue: "12:00",
            smartSelect: 1,
            selectValue: 2,
            multiSelect: [11],
            multiPreloader: false,
            textarea: "This is Fardin",
            currency: 5000,
            radioButtons: 3,
            radioBtn: 1,
            checkboxValue: '',
            textEditor: "<h1>hi there, how are you </h1>",
            fileValue: '',
            customFile: '',
            dropzoneValue: [
                '/images/error-401.png',
                '/images/error-403.png',
                '/images/error-404.png',
            ],
            testFields: {
                timeValue: "12:15 PM",
                phone: 1796426502,
                textValue: "Sani",
                emailValue: 'sani@gain.media',
                confirmedPassword: '',
                password: '',
                password2: '',
                date1: new Date("2020-04-08"),
                date2: null
            },
            testArray: [
                {
                    name: "fardin",
                },
                {
                    name: "ahsan",
                },
            ],
            passwordArr: [
                {
                    password: '',
                },
                {
                    password: '',
                },
            ],
            multiSelectOptionList: [
                {id: 1, value: 'Cricket'},
                {id: 2, value: 'Football'},
                {id: 3, value: 'Badminton'},
                {id: 4, value: 'Option 4'},
                {id: 5, value: 'Option 5'},
                {id: 6, value: 'Option 6'},
                {id: 7, value: 'Option 7'},
                {id: 8, value: 'Option 8'},
                {id: 9, value: 'Option 9'},
                {id: 10, value: 'Option 10'},
            ],

            // Multi Create
            multiCreate: [],
            multiCreateOptionList: [
                {id: 1, name: 'Cricket'},
                {id: 2, name: 'Football',},
                {id: 3, name: 'Kabadi'},
                {id: 4, name: 'Hadudu'}
            ]
        };
    },
    created() {
        // this.dateValue = new Date("2020-04-08");
        this.getData();
        // console.log(this.$store.settings, "store");
    },
    methods: {
        changeData() {
            this.multiSelect = [4, 5];
            this.multiCreate = [2];
            this.searchSelectValue = 3;
            this.isRange = true;
        },
        focusInShow() {
            console.log('Focused In');
        },
        mouseMoveShow() {
            console.log('mouseMoveShow text');
        },
        focusOutShow() {
            console.log('Focused Out');
        },
        showClickEvent() {
            console.log('This is click event');
        },
        showInputEventValue(value) {
            console.log('Value is : ' + value);
        },
        submitData() {
            //     this.fieldStatus.isSubmit = true;
            //     if (this.test.length > 6) {
            let formData = new FormData();
            formData.append("custom_file", this.customFile);
            formData.append("dropzoneValue", this.dropzoneValue);

            console.log(this.customFile, 'custom');
            console.log(formData, 'custom');

            this.save(formData);

            // } else {
            //     this.fieldStatus['testFields_textValue'] = {
            //         isValid: false,
            //         message: "Length should more then 6"
            //     }
            // }
        },

        // Multi create
        testStoreData(arg) {
            this.multiPreloader = true;
            this.addNewMultiCreateOption(arg);
        },
        addNewMultiCreateOption(value) {
            this.axiosPost({
                url: 'store-multi-create-option',
                data: {name: value}
            }).then(response => {
                this.multiCreateOptionList = response.data.list;
                this.multiCreate.push(response.data.new_id);
            }).finally(() => {
                this.multiPreloader = false;
            });
        },

        // Hooks
        beforeSubmit(fields) {
            let formData = new FormData();

            // fields.dropzoneValue.map((value, index) => {
            //   formData.append("dropzoneValue[]", fields.dropzoneValue[index]);
            // });
            // // console.log(fields.checkbox, typeof fields.checkbox);
            // fields.checkbox.map((value, index) => {
            //   formData.append("checkbox[]", fields.checkbox[index]);
            // });
            // formData.append("radio", fields.radio);
            // formData.append("currency", fields.currency);
            // formData.append("custom-file-upload", fields["custom-file-upload"]);
            // formData.append("date", fields.date);
            // formData.append("decimal", fields.decimal);
            // formData.append("email", fields.email);
            // formData.append("multi-select", fields["multi-select"]);
            // formData.append("number", fields.number);
            // formData.append("password", fields.password);
            // formData.append("radio-buttons", fields["radio-buttons"]);
            // formData.append("selectValue", fields.selectValue);
            // formData.append("smart-select", fields["smart-select"]);
            // formData.append("text", fields.text);
            // formData.append("text-editor", fields["text-editor"]);
            // formData.append("textarea", fields.textarea);
            // formData.append("timeValue", fields.timeValue);

            // formData.append("checkbox[]", fields.checkbox);
            //   console.log(formData);
            // return formData;
        },
        afterSubmit() {
        },
        afterSuccess(res) {
            console.log(res, "success");
            this.customFile = ''
        },
        afterError(res) {
            this.testName = 3;
            this.showError = true;
            this.errorMessage = "Given email is invalid";
        },
        getData() {
            let instance = this,
                url = "test-component";
            instance
                .axiosGet(url)
                .then(response => {
                    instance.dataLoaded = true;
                    this.fields = response.data;

                    this.customFile = response.data.customFile;
                    console.log(this.customFile);
                })
                .catch(({response}) => {
                    if (instance.isFunction(instance.afterErrorFromGetEditData))
                        instance.afterErrorFromGetEditData(response);
                });
        }
    }
}
</script>
