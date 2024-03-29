<template>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3"
            :class="importUrl ? 'col-xl-9 col-xxl-9' : 'col-xl-12 col-xxl-12'">
            <div class="formContainer">
                <form enctype="multipart/form-data" @submit.prevent="Submit" novalidate>
                    <div class="formTitleContainer d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="formTitle m-0">Single Insert</h5>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-4 mb-4" v-for="field of fields"
                            :key="field.name">
                            <!--If Single Selectbox Required-->
                            <RegularSelect v-if="field.formComponent == 'RegularSelect'" :label="field.label"
                                :name="field.name" :id="field.name" :source="field.source" :mount="field.mount"
                                :watching="field.watching" :validations="field.validations" :options="field.options"
                                v-model="form[field.name]" />
                            <!--End-->

                            <!--If InputText Required-->
                            <InputText v-else-if="field.formComponent == 'InputText'" :label="field.label"
                                :name="field.name" :id="field.name" :validations="field.validations"
                                v-model="form[field.name]" />
                            <!--End-->

                            <!--If Textarea Required-->
                            <Textarea v-else-if="field.formComponent == 'Textarea'" :label="field.label" :name="field.name"
                                :id="field.name" :validations="field.validations" v-model="form[field.name]" />
                            <!--End-->

                            <!--If File Required-->
                            <File v-else-if="field.formComponent == 'File'" :label="field.label" :name="field.name"
                                :id="field.name" :validations="field.validations" v-model="form[field.name]"
                                @showModal="showModal($event)" />
                            <!--End-->

                            <!--If File With Preview Required-->
                            <FileWithPreview v-else-if="field.formComponent == 'FileWithPreview'
                                " :label="field.label" :name="field.name" :id="field.name"
                                :validations="field.validations" v-model="form[field.name]" />
                            <!--End-->

                            <!--If Radio Required-->
                            <Radio v-else-if="field.formComponent == 'Radio'" :label="field.label" :name="field.name"
                                :id="field.name" :validations="field.validations" :options="field.options"
                                v-model="form[field.name]" :checked="field.value" />
                            <!--End-->

                            <!--If Checkbox Required-->
                            <Checkbox :id="field.name" :name="field.name" :label="field.label"
                                :validations="field.validations" v-model:checked="form[field.name]"
                                v-else-if="field.formComponent == 'Checkbox'" :checked="field.value" />
                            <!--End-->

                            <!--If CheckboxGroup Required-->
                            <CheckboxGroup :id="field.name" :name="field.name" :label="field.label" :source="field.source"
                                :options="field.options" :validations="field.validations" v-model:value="form[field.name]"
                                v-else-if="field.formComponent == 'CheckboxGroup'
                                    " />
                            <!--End-->

                            <span v-else>-</span>
                        </div>
                    </div>
                    <div class="formFooterContainer d-flex justify-content-between align-items-center border-top">
                        <button type="sbumit" class="btn btn-primary me-2">
                            Submit
                        </button>
                        <button type="button" class="btn btn-danger me-2">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 col-xxl-3 mb-3" v-if="importUrl">
            <div class="formContainer">
                <form method="POST" enctype="multipart/form-data" @submit.prevent="SubmitBulkUploadForm" novalidate>
                    <div class="formTitleContainer d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="formTitle m-0">Bulk Insert</h5>
                        <a :href="downloadUrl">
                            <button type="button" class="btn btn-primary me-2">
                                <i class="fa fa-download"></i>
                            </button>
                        </a>
                    </div>
                    <div v-if="$page.props.errors.url" :class="$page.props.errors.url ? 'is-invalid' : 'valid'">
                        <a :href="$page.props.errors.url">
                            Click here
                        </a>
                        To download error sheet
                    </div>

                    <div class="row">
                        <div class="col-12 mb-4">
                            <label for="name" class="form-label">Select Excel (.xlsx) file <span
                                    class="required-asterisk">*</span></label>
                            <input type="file" class="form-control"
                                :class="$page.props.errors.name ? 'is-invalid' : 'valid'" :value="modelValue" accept=".xlsx"
                                @input="fileInput($event)" placeholder="No file chosen" required />
                            <div class="invalid-feedback" v-if="$page.props.errors.name" v-text="$page.props.errors.name" />
                        </div>
                    </div>
                    <div class="formFooterContainer d-flex justify-content-between align-items-center border-top">
                        <button type="sbumit" class="btn btn-primary me-2">
                            Submit
                        </button>
                        <div v-if="message">{{ message }}</div>
                        <button type="button" class="btn btn-danger me-2">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Popup Modal-->
    <Transition name="bounce">
        <Modal v-if="isModalVisible" @close="closeModal" :src="modalSrc" :type="modalType" />
    </Transition>
    <!--End-->
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const RegularSelect = defineAsyncComponent(() => import("./Select/Regular"));
const InputText = defineAsyncComponent(() => import("./Input/Text"));
const Textarea = defineAsyncComponent(() => import("./Text/Textarea"));
const Radio = defineAsyncComponent(() => import("./Input/Radio"));
const Checkbox = defineAsyncComponent(() => import("./Input/Checkbox"));
const CheckboxGroup = defineAsyncComponent(() =>
    import("./Input/CheckboxGroup")
);
const File = defineAsyncComponent(() => import("./Input/File"));
const FileWithPreview = defineAsyncComponent(() =>
    import("./Input/FileWithPreview")
);
const BulkInputForm = defineAsyncComponent(() => import("./BulkInputForm"));
const Modal = defineAsyncComponent(() => import("../Modal/ModalView"));
console.log(props.fields);
const props = defineProps({
    fields: Array,
    url: String,
    methodName: String,
    downloadUrl: String,
    importUrl: String,
});

let data = { _method: props.methodName };

for (let key in props.fields) {
    let fieldName = props.fields[key].name;
    let fieldValue = props.fields[key].value;
    let fieldType = props.fields[key].type;
    if (fieldType == "String") {
        data[fieldName] = fieldValue != '' ? fieldValue : "";
    } else if (fieldType == "Number") {
        data[fieldName] = fieldValue != '' ? fieldValue : null;
    } else if (fieldType == "Array") {
        data[fieldName] = fieldValue != '' ? fieldValue : [];
    } else if (fieldType == "Boolean") {
        data[fieldName] = fieldValue != '' ? fieldValue : false;
    }
}

let form = useForm(data);

let Submit = () => {
    form.post(props.url, data);
};
//bulk insert form upload
let fileData = {
    name: null,
};
let formBuilder = useForm(fileData);
let SubmitBulkUploadForm = () => {
    formBuilder.post(props.importUrl, fileData);
};

let fileInput = (event) => {
    console.log(event.target.files[0]);
    formBuilder.name = event.target.files[0];
    console.log(formBuilder.name);
}
</script>

<script>
export default {
    data() {
        return {
            isModalVisible: false,
            modalSrc: "",
            modalType: "",
        };
    },
    methods: {
        showModal: function (event) {
            this.isModalVisible = true;
            this.modalSrc = event["uploadedFile"];
            this.modalType = event["uploadedFileName"];
        },
        closeModal: function () {
            this.isModalVisible = false;
        },
    },
};
</script>
