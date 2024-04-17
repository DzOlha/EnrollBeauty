import AddDepartmentForm from "./AddDepartmentForm.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import ImageDropify from "../../../../../../classes/element/ImageDropify.js";
import CONST from "../../../../../../config/contants/constants.js";
import Input from "../../../../../../classes/element/Input.js";
import AjaxImageFiller from "../../../../../../classes/helper/AjaxImageFiller.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";

class EditDepartmentForm extends AddDepartmentForm
{
    constructor(requester, modalForm, optionBuilder, table, addApiUrl, getObjectUrl) {
        super(requester, modalForm, optionBuilder, table, addApiUrl);

        this.inputFileWrapperSelector = `#modalEditDepartment .dropify-wrapper`;
        this.getObjectUrl = getObjectUrl;
        this.manageBase = 'manage';
        this.dataNameAttribute = 'data-department-name';
        this.dataIdAttribute = 'data-department-id';
    }
    setDeleteCallback(callback, context) {
        this.deleteCallback = callback.bind(context);
    }

    setShowWorkersCallback(callback, context){
        this.showWorkersCallback = callback.bind(context);
    }

    addListenerManage(id) {
        let selector = `${this.manageBase}-${id}`;
        let btn = document.getElementById(
            selector
        );
        btn.addEventListener('click', this.handleShowEditForm)
    }
    handleShowEditForm = (e) =>
    {
        e.preventDefault();
        let id = e.currentTarget.getAttribute(this.dataIdAttribute);
        let name = e.currentTarget.getAttribute(this.dataNameAttribute);

        this.modalForm.setSelectors('modalEditDepartment');
        this.submitButtonId = this.modalForm.modalSubmitId;

        this.modalForm.show(
            'Edit Department',
            this.modalForm.formBuilder.createEditDepartmentForm(id),
            'Update'
        );

        this._initTheForm(id);
        this.modalForm.close();
        this.addListenerSubmitForm();


        this.deleteCallback(id);
    }
    _initTheForm(id) {
        this.requester.get(
            `${this.getObjectUrl}?id=${id}`,
            (response) => {
                this._populateForm(response.data)
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    _populateForm(data)
    {
        $(`#${this.departmentNameInputId}`).val(data.name);
        $(`#${this.departmentDescriptionInputId}`).val(data.description);

        /**
         * Populate main photo
         */
        let image = new ImageDropify(
            this.inputFileWrapperSelector, this.departmentPhotoInputId, 'Department'
        );
        let path = data.photo_filename
            ? `${CONST.adminImgFolder}/departments/department_${data.id}/${data.photo_filename}`
            : '';

        image.set(this.departmentPhotoInputId, path);

        /**
         * Set the service id on the update button to be able
         * to retrieve this info if the user submit the form
         * @type {HTMLElement}
         */
        let submitBtn = document.getElementById(this.submitButtonId);
        submitBtn.setAttribute(this.dataIdAttribute, data.id);
    }

    mainPhotoValidationCallback = (value) => {
        let image = new ImageDropify(
            this.inputFileWrapperSelector,
            this.departmentPhotoInputId,
            'Department',
            this.allowedImageExtensions
        );
        return image.validate(value, false);
    }

    validateFormData() {
        let name = Input.validateInput(
            this.departmentNameInputId, 'name', this.departmentNameValidationCallback
        );
        let description = Input.validateInput(
            this.departmentDescriptionInputId, 'description', this.departmentDescriptionValidationCallback
        );
        let photo = Input.validateInput(
            this.departmentPhotoInputId, 'photo', this.mainPhotoValidationCallback, true
        );

        if(name && description) {
            let data = {
                ...name, ...description, ...photo
            }
            return AjaxImageFiller._populateFormDataObject(data, 'photo');
        }
        return false;
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     name:
         * }
         */
        let data = this.validateFormData();
        data.append('id', e.currentTarget.getAttribute(this.dataIdAttribute));

        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.postFiles(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    GifLoader.hide(this.requestTimeout);
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *         id:
     *         name:
     *     }
     * }
     */
    successCallbackSubmit(response) {
        GifLoader.hide(this.requestTimeout );
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);

        /**
         * Close modal window with form
         */
        $(`#${this.modalForm.modalCloseId}`).click();

        /**
         * Regenerate the row of the table of departments
         */
        this._regenerateTableRow(response.data);
    }
    _regenerateTableRow(data)
    {
        $(`tr[${this.dataIdAttribute}=${data.id}]`).replaceWith(
            this.departmentsTable.populateRow(data)
        );
        this.addListenerManage(data.id);
        this.showWorkersCallback(data.id);
    }
}
export default EditDepartmentForm;