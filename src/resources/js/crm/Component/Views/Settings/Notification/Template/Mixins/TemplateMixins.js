import {FormMixin} from "@core/mixins/form/FormMixin";
import {FormSubmitMixin} from "../../../../../../Mixins/Global/FormSubmitMixin";

export const TemplateMixins = {
    mixins: [FormMixin, FormSubmitMixin],
    data(){
        return{
            template:''
        }
    },
    methods:{
        submitData(){
            delete this.template.user;
            delete this.template.pivot;
            this.submitFromFixin('patch', `admin/app/notification-templates/${this.template.id}`, this.template);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.props);
            $("#notification-template").modal('hide');
            this.closeModal()
        },
        insertAtCaret(areaId, text) {
            let txtarea = document.getElementById(areaId);
            if (!txtarea) {
                return;
            }

            let scrollPos = txtarea.scrollTop;
            let strPos = 0;
            let br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
                "ff" : (document.selection ? "ie" : false));
            if (br == "ie") {
                txtarea.focus();
                let range = document.selection.createRange();
                range.moveStart('character', -txtarea.value.length);
                strPos = range.text.length;
            } else if (br == "ff") {
                strPos = txtarea.selectionStart;
            }

            let front = (txtarea.value).substring(0, strPos);
            let back = (txtarea.value).substring(strPos, txtarea.value.length);
            txtarea.value = front + text + back;
            strPos = strPos + text.length;
            if (br == "ie") {
                txtarea.focus();
                let ieRange = document.selection.createRange();
                ieRange.moveStart('character', -txtarea.value.length);
                ieRange.moveStart('character', strPos);
                ieRange.moveEnd('character', 0);
                ieRange.select();
            } else if (br == "ff") {
                txtarea.selectionStart = strPos;
                txtarea.selectionEnd = strPos;
                txtarea.focus();
            }

            txtarea.scrollTop = scrollPos;
        }
    },

    watch : {
        template : {
            handler : 'setTemplateObj',
            immediate : true,
        }
    },
}
