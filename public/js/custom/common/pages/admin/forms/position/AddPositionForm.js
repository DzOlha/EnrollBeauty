import Form from "../../../user/forms/Form.js";
import Notifier from "../../../classes/notifier/Notifier.js";
import Select2 from "../../../classes/element/Select2.js";
import Input from "../../../classes/element/Input.js";
import GifLoader from "../../../classes/loader/GifLoader.js";

class AddPositionForm extends Form
{
    constructor(
        requester, modalForm, table,
        getDepartmentUrl, addApiUrl,
    ) {
        super(
            '',
            '',
            addApiUrl,
            requester
        );
        this.modalForm = modalForm;
        this.table = table;

        this.addTriggerId = 'add-position-trigger';

        this.positionNameInputId = 'position-name-input';
        this.departmentSelectId = 'department-select';

        this.departmentSelect2 = null;

        this.modalBodyClass = 'modal-body';

        this.apiGetDepartments = getDepartmentUrl;
    }
    init() {
        this.addListenerShowAddForm();
    }
    /**
     * Add listener to the 'Add Service' button
     */
    addListenerShowAddForm() {
        let trigger = document.getElementById(this.addTriggerId);
        trigger.addEventListener('click', this.handleShowAddForm);
    }
    handleShowAddForm = () => {
        this.modalForm.setSelectors('modalAddPosition');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Add New Position',
            this.modalForm.formBuilder.createAddPositionForm(),
            'Add'
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
            'Department',
            'Choose department',
            this.departmentSelectId,
            true,
            true,
            modalBody
        );
        this.departmentSelect2.populate(response.data);
    }
    positionNameValidationCallback = (value) => {
        let result = {};
        if(!value) {
            result.error = "Position name is required field!";
            return result;
        }

        if(value.length < 3) {
            result.error = "Position name should be longer than 3 characters!";
            return result;
        }

        return result;
    }
    validateFormData() {
        let department = this.departmentSelect2.validate('department_id');
        let positionName = Input.validateInput(
            this.positionNameInputId, 'position_name', this.positionNameValidationCallback
        )
        if(department && positionName) {
            return {
                ...department, ...positionName
            }
        }
        return false;
    }

    listenerSubmitForm = (e) => {
        /**
         * {
         *     position_name:
         *     department_id:
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
         * Regenerate the table of positions to show the newly added position there
         */
        this.table.regenerate();
    }

}
export default AddPositionForm;