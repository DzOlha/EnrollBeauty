import AddServiceForm from "./AddServiceForm.js";
import Select2 from "../../../classes/element/Select2.js";
import API from "../../../api.js";
import Notifier from "../../../classes/notifier/Notifier.js";
import Input from "../../../classes/element/Input.js";
import GifLoader from "../../../classes/loader/GifLoader.js";

class EditServiceForm extends AddServiceForm
{
    constructor(
        requester, modalForm, optionBuilder,
        servicesTable, addApiUrl, getDepartmentUrl,
        editApiUrl, withDelete
    ) {
        super(requester, modalForm, optionBuilder, servicesTable, addApiUrl, getDepartmentUrl);
        this.withDelete = withDelete;

        this.submitActionUrl = editApiUrl;

        this.manageBase = 'manage';
        this.dataAttributeServiceId = 'data-service-id';

        this.departmentId = null;

        this.apiGetService = API.WORKER.API.SERVICE.get.one;
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
        let id = e.currentTarget.getAttribute(this.dataAttributeServiceId);

        this.modalForm.setSelectors('modalEditService');
        this.submitButtonId = this.modalForm.modalSubmitId;

        this.modalForm.show(
            'Edit Service',
            this.modalForm.formBuilder.createEditServiceForm(id, this.withDelete),
            'Update'
        );

        this.getServiceData(id);
        this.modalForm.close();
        this.addListenerSubmitForm();

        if(this.withDelete) {
            this.deleteCallback(id);
        }

        /**
         * Set the service id on the update button to be able
         * to retrieve this info if the user submit the form
         * @type {HTMLElement}
         */
        let submitBtn = document.getElementById(this.submitButtonId);
        submitBtn.setAttribute(this.dataAttributeServiceId, id);
    }
    getServiceData(id) {
        this.requester.get(
            `${this.apiGetService}?id=${id}`,
            /**
             *
             * @param response = {
             *     success:
             *     data: {
             *         id:
             *         name:
             *         department_id:
             *     }
             * }
             */
            (response) => {
                this.departmentId = response.data.department_id;
                $(`#${this.serviceNameInputId}`).val(response.data.name);
                this.getDepartments();
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    successCallbackGetDepartments(response)
    {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);

        this.departmentSelect2 = new Select2(
            this.modalForm.modalContentId,
            'Department',
            'Choose department',
            this.departmentSelectId,
            true,
            true,
            modalBody
        );
        this.departmentSelect2.populate(response.data);

        this.departmentSelect2.set(this.departmentId);
    }
    validateFormData() {
        let department = this.departmentSelect2.validate('department_id');
        let serviceName = Input.validateInput(
            this.serviceNameInputId, 'service_name', this.serviceNameValidationCallback
        )

        /**
         * Get the service id from the submit button
         * @type {string}
         */
        let serviceId = document.getElementById(this.submitButtonId)
                                       .getAttribute(this.dataAttributeServiceId);

        if(department && serviceName) {
            return {
                'service_id': serviceId,
                ...department, ...serviceName
            }
        }
        return false;
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *         id:
     *         name:
     *         department_id:
     *         department_name:
     *     }
     * }
     */
    successCallbackSubmit(response) {
        GifLoader.hide(this.requestTimeout);
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);

        /**
         * Close modal window with form
         */
        $(`#${this.modalForm.modalCloseId}`).click();

        /**
         * Update only one row in the table to reflect
         * the changes of the service on the frontend
         */
        $(`tr[${this.dataAttributeServiceId}=${response.data.id}]`).replaceWith(
            this.table.populateRow(response.data)
        );
        this.addListenerManage(response.data.id);

        if(this.withDelete) {
            this.showWorkersCallback(response.data.id);
        }

        this.departmentId = null;
    }
}
export default EditServiceForm;