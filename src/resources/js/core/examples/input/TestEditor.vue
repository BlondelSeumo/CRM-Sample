<template>
    <div class="content-wrapper" style="height: 100vh">
        <app-input type="text-editor" name="text-editor" id="text-editor"
                     :placeholder="placeholder"
                     :initialValue="value"
                     :field-props="fieldStatus"/>
    </div>
</template>

<script>
    import TextEditor from "../../components/input/TextEditor";

    export default {
        name: "TestEditor",
        components: {
            TextEditor
        },
        data() {
            return {
                placeholder: "Write something...",
                value: "",
                fieldStatus: {
                    isSubmit: false,
                }
            }
        }
    }
</script>
