import AddDepartmentForm from "./AddDepartmentForm.js";
import GifLoader from "../../../classes/loader/GifLoader.js";
import Notifier from "../../../classes/notifier/Notifier.js";

class EditDepartmentForm extends AddDepartmentForm
{
    constructor(requester, modalForm, optionBuilder, table, addApiUrl) {
        super(requester, modalForm, optionBuilder, table, addApiUrl);

        this.manageBase = 'manage';
        this.dataNameAttribute = 'data-department-name';
        this.dataIdAttribute = 'data-department-id';
    }
    setDeleteCallback(callback, context) {
        this.deleteCallback = callback.bind(context);
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

        this._populateForm(name);
        this.modalForm.close();
        this.addListenerSubmitForm();


        this.deleteCallback(id);

        /**
         * Set the service id on the update button to be able
         * to retrieve this info if the user submit the form
         * @type {HTMLElement}
         */
        let submitBtn = document.getElementById(this.submitButtonId);
        submitBtn.setAttribute(this.dataIdAttribute, id);
    }
    _populateForm(name)
    {
        $(`#${this.departmentNameInputId}`).val(name);
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
        data.id = e.currentTarget.getAttribute(this.dataIdAttribute);

        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.post(
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
    }
}
export default EditDepartmentForm;