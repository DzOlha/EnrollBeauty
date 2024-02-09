import Form from "../../../user/forms/Form.js";
import Input from "../../../classes/element/Input.js";
import GifLoader from "../../../classes/loader/GifLoader.js";
import Notifier from "../../../classes/notifier/Notifier.js";

class AddDepartmentForm extends Form {
    constructor(
        requester, modalForm, optionBuilder, table,
        addApiUrl
    ) {
        super(
            '',
            '',
            addApiUrl,
            requester
        );
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.departmentsTable = table;

        this.addTriggerId = 'add-department-trigger';

        this.departmentNameInputId = 'department-name-input';

        this.modalBodyClass = 'modal-body';
    }
    /**
     * Add listener to the 'Add Service' button
     */
    addListenerShowAddForm() {
        let trigger = document.getElementById(this.addTriggerId);
        trigger.addEventListener('click', this.handleShowAddForm);
    }

    handleShowAddForm = () => {
        this.modalForm.setSelectors('modalAddDepartment');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Add New Department',
            this.modalForm.formBuilder.createAddDepartmentForm(),
            'Add'
        );

        this.modalForm.close();
        this.addListenerSubmitForm();
    }
    departmentNameValidationCallback = (value) => {
        let result = {};
        if(!value) {
            result.error = "Department name is required field!";
            return result;
        }

        if(value.length < 3) {
            result.error = "Department name should be longer than 3 characters!";
            return result;
        }

        return result;
    }
    validateFormData() {
        let name = Input.validateInput(
            this.departmentNameInputId, 'name', this.departmentNameValidationCallback
        )
        if(name) {
            return {
                ...name
            }
        }
        return false;
    }
    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *    name:
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

    /**
     * @param response = {
     *     success:
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
         * Regenerate the table of services to show the newly added service there
         */
        this.departmentsTable.regenerate();
    }
}
export default AddDepartmentForm;