import Form from "../../../../user/classes/forms/Form.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import Select2 from "../../../../../../classes/element/Select2.js";
import Input from "../../../../../../classes/element/Input.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";
import NameRegex from "../../../../../../classes/regex/impl/NameRegex.js";

class AddServiceForm extends Form {
    constructor(
        requester, modalForm, servicesTable,
        addApiUrl, getDepartmentUrl
    ) {
        super(
            '',
            '',
            addApiUrl,
            requester
        );
        this.modalForm = modalForm;
        this.table = servicesTable;
        this.addServiceTriggerId = 'add-service-trigger';

        this.serviceNameInputId = 'service-name-input';
        this.departmentSelectId = 'department-select';

        this.departmentSelect2 = null;

        this.modalBodyClass = 'modal-body';

        this.apiGetDepartments = getDepartmentUrl;
        this.headline = 'Add New Service';
        this.btnText = 'Add';
        this.modalIdValue = 'modalAddService';

        this.departmentFieldName = 'Department';
        this.placeholderDepartment = 'Choose department';
    }
    /**
     * Add listener to the 'Add Service' button
     */
    addListenerShowAddServiceForm() {
        let trigger = document.getElementById(this.addServiceTriggerId);
        trigger.addEventListener('click', this.handleShowAddServiceForm);
    }
    handleShowAddServiceForm = () => {
        this.modalForm.setSelectors(this.modalIdValue);
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            this.headline,
            this.modalForm.formBuilder.createAddServiceForm(),
            this.btnText
        );
        this.getDepartments();
        this.modalForm.close();
        this.addListenerSubmitForm();
    }
    getDepartments() {
        this.requester.get(
            this.apiGetDepartments,
            this.successCallbackGetDepartments.bind(this),
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
            this.departmentFieldName,
            this.placeholderDepartment,
            this.departmentSelectId,
            true,
            true,
            modalBody
        );
        this.departmentSelect2.populate(response.data);
    }

    serviceNameValidationCallback = (value) => {
        let result = {};
        if(!value) {
            result.error = "Service name is required field!";
            return result;
        }

        let pattern = new NameRegex(3, 100, true);
        if(!pattern.test(value)) {
            result.error = "Service name should be between 3-100 characters long and contain only letters with whitespaces and dashes!";
            return result;
        }

        return result;
    }

    validateFormData() {
        let department = this.departmentSelect2.validate('department_id');
        let serviceName = Input.validateInput(
            this.serviceNameInputId, 'service_name', this.serviceNameValidationCallback
        )
        if(department && serviceName) {
            return {
                ...department, ...serviceName
            }
        }
        return false;
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     service_id:
         *     price:
         * }
         */
        let data = this.validateFormData();
        //console.log(data);

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
         * Regenerate the table of services to show the newly added service there
         */
        this.table.regenerate();
    }

}
export default AddServiceForm;