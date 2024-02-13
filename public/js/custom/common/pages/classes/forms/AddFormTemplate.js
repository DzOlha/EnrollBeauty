
import GifLoader from "../loader/GifLoader.js";
import Notifier from "../notifier/Notifier.js";
import Form from "../../user/forms/Form.js";

/**
 * To use this AddFormTemplate class you need to
 * override the following methods
 *
 *
 * 1) getModelContent() - to specify the views of the form
 *
 * 2) initModalForm() - to initialize all elements in the form in modal window
 *
 * 3) validateFormData() - to validate all needed fields of the form before submit it
 *
 * 4) regenerateStructure() - to be able to regenerate the table/list/etc.
 *                            of all objects to which you added a new one.
 */
class AddFormTemplate extends Form
{
    constructor(
        requester, modalForm, addApiUrl, addTriggerId
    ) {
        super(
            '',
            '',
            addApiUrl,
            requester
        );
        this.modalForm = modalForm;

        this.addTriggerId = addTriggerId;

        this.modalBodyClass = 'modal-body';
    }

    init() {
        this.addListenerShowAddForm();
    }
    /**
     * Add listener to the add button
     */
    addListenerShowAddForm() {
        let trigger = document.getElementById(this.addTriggerId);
        trigger.addEventListener('click', this.handleShowAddForm);
    }

    handleShowAddForm = () => {
        this.modalForm.setSelectors('modalAddObject');
        this.submitButtonId = this.modalForm.modalSubmitId;

        let modalContent = this.getModelContent();

        this.modalForm.show(
            modalContent.headline,
            modalContent.formHtml,
            modalContent.submitBtnText,
        );

        this.initModalForm();
        this.modalForm.close();
        this.addListenerSubmitFormAdd();
    }

    addListenerSubmitFormAdd() {
        let submit = document.getElementById(this.submitButtonId);
        submit.addEventListener('click', this.listenerSubmitFormAdd);
    }

    getModelContent()
    {
        // return {
        //     'headline': 'Add New Object',
        //     'formHtml':  this.modalForm.formBuilder.createAddPositionForm(),
        //     'submitBtnText': 'Add'
        // }
    }

    initModalForm() {}

    validateFormData() {}

    listenerSubmitFormAdd = (e) => {
        let data = this.validateFormData();
        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmitAdd.bind(this),
                (response) => {
                    GifLoader.hide(this.requestTimeout);
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }

    successCallbackSubmitAdd(response) {
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
         * Regenerate the whole structure after inserting new element
         */
        this.regenerateStructure();
    }

    regenerateStructure() {}
}
export default AddFormTemplate;