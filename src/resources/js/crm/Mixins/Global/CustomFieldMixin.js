import { formatDateToLocal, numberFormatter } from "@app/Helpers/helpers";
export const getCustomFileds = {
  data(){
    return {

    }
  },
  methods: {
    getCustomFiled(type) {
      let mdata = [];
      this.axiosGet(`custom-field?type=${type}`).then((response) => {
        mdata = response.data;
        if (mdata.length){
            this.options.columns = this.commonColumn;
            mdata.forEach((field, index) => {
                let customCol = {
                    title: field.name,
                    type: "custom-html",
                    key: "custom_fields",
                    modifier: (value, row) => {
                        let test = value.find((el) => el.custom_field_id == field.id);

                        let customValue = test
                            ? field.custom_field_type.name == "date"
                                ? formatDateToLocal(test.value)
                                : field.custom_field_type.name == "number"
                                    ? numberFormatter(test.value)
                                    : test.value
                            : "-";
                        return `<p class="width-180">${customValue}</p>`
                    },
                };

                let lastItemCol = this.options.columns.pop();
                    this.options.columns.push(customCol);
                    this.options.columns.push(lastItemCol);
            });
        } else {
          this.options.columns = this.commonColumn;
        }
      });
    },
  },
};

export const detailsPageCustomFields = {
    data(){
        return {
            customFieldData: {},
        }
    },
    methods: {
        detailsPageCustomField(type) {
            let mdata = [];
            this.axiosGet(`custom-field?type=${type}`).then((response) => {
                mdata = response.data;
                if (mdata.length){
                    mdata.forEach((field, index) => {
                        if (this.formData.custom_fields){
                            let test = this.formData.custom_fields.find((el) => el.custom_field_id == field.id);
                            this.customFieldData[field.name] = test ? test.value : "-";
                        }
                    });
                    this.loadData = true;
                }

            });
        },
    },
};

export const getAllCustomFields = {
  data() {
    return {
      customFields: {},
      customFieldValue: {},
      customFieldDataLoaded:false,
    };
  },
  methods: {
    getAllCustomFields(type) {
      this.axiosGet(route('core.custom-fields.index')).then(({ data }) => {
        this.customFields = data.data.filter((e) => e.context == type);
        this.customFields.forEach((el, i) => {
          if (this.formData.custom_fields) {
            //edit
            let targetField = this.formData.custom_fields.find(
              (e) => e.custom_field_id == el.id
            );
            if (el.custom_field_type.name == "checkbox") {
              //checkbox

              let options = [];
              if (targetField) {
                el.meta.split(",").map((e, i) => {
                  if (targetField.value.split(",").find((el) => el == e))
                    options.push(i);
                });
              }
              this.customFieldValue[el.name] = options;
              // console.log(options, "if if edit checkbox");
            } else if (
              el.custom_field_type.name == "select" ||
              el.custom_field_type.name == "radio"
            ) {
              let v = undefined;
              // select & radio
              if (targetField) {
                el.meta.split(",").map((e, i) => {
                  if (targetField.value.split(",").find((el) => el == e)) v = i;
                });
              }
              this.customFieldValue[el.name] = v;
            } else if (el.custom_field_type.name == "date") {
              //other

              this.customFieldValue[el.name] = targetField
                ? new Date(targetField.value)
                : "";
              // console.log("if else edit other");
            } else {
              //other

              this.customFieldValue[el.name] = targetField
                ? targetField.value
                : "";
              // console.log("if else edit other");
            }
          } else {
            //add
            if (
              el.custom_field_type.name == "select" ||
              el.custom_field_type.name == "radio"
            ) {
              // select & radio
              this.customFieldValue[el.name] = undefined;
            } else {
              this.customFieldValue[el.name] =
                el.custom_field_type.name == "checkbox" ? [] : "";
            }
            // console.log("else add");
          }
        });
        setTimeout(() => {
          this.dataLoaded = true;
          this.customFieldDataLoaded = true;
        }, 300)
      });
    },
    generateInputList({ meta }) {
      if (meta) {
        return meta.split(",").map((m, i) => {
          return { id: i, value: m };
        });
      }
    },
  },
};
