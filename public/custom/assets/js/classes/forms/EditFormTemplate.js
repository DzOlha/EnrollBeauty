import AddFormTemplate from "./AddFormTemplate.js";
import GifLoader from "../loader/GifLoader.js";
import Notifier from "../notifier/Notifier.js";

/**
 * To use this EditFormTemplate you need
 * to have the initialized AddFormTemplate class
 *
 * and to override the following methods of EditFormTemplate
 *
 *
 * 1) getModelContent(id) - to specify the view of the form
 *
 * 2) populateForm(data) - to populate the initialized form with the current object data
 *
 * 3) regenerateItem(data) - to regenerate the item (row/card) after updating
 *
 * and set the delete callback of the class if there is such functionality needed
 *
 * and set the edit(manage) callback to the appropriate table/list
 * by using addListenerEdit(id) method
 */
class EditFormTemplate extends AddFormTemplate
{
    constructor(requester, modalForm, apiUrl, addTriggerId,
                dataIdAttribute, apiGetObjectData, withDelete = true) {
        super(requester, modalForm, apiUrl, addTriggerId);

        this.withDelete = withDelete;

        this.manageBase = 'manage';
        this.dataIdAttribute = dataIdAttribute;

        this.apiGetObject = apiGetObjectData;
    }

    setDeleteCallback(callback, context) {
        this.deleteCallback = callback.bind(context);
    }

    addListenerEdit(id) {
        let btn = document.getElementById(
            `${this.manageBase}-${id}`
        );
        btn.addEventListener('click', this.handleShowEditForm)
    }

    handleShowEditForm = (e) => {
        e.preventDefault();
        this.modalForm.setSelectors('modalEditObject');
        this.submitButtonId = this.modalForm.modalSubmitId;

        /**
         * Get the id of the object we want to edit
         */
        let id = e.currentTarget.getAttribute(this.dataIdAttribute);

        let modalContent = this.getModelContent(id);

        this.modalForm.show(
            modalContent.headline,
            modalContent.formHtml,
            modalContent.submitBtnText,
        );

        /**
         * Save the id of the object into data attribute of the submit button
         */
        let submit = document.getElementById(this.modalForm.modalSubmitId);
        submit.setAttribute(this.dataIdAttribute, id);

        this.initModalForm();
        this.getObjectData(id);

        this.modalForm.close();
        this.addListenerSubmitFormEdit();

        if(this.withDelete) {
            this.deleteCallback(id);
        }
    }

    addListenerSubmitFormEdit() {
        let submit = document.getElementById(this.submitButtonId);
        submit.addEventListener('click', this.listenerSubmitFormEdit);
    }

    getModelContent(id)
    {
        // return {
        //     'headline': 'Edit the Object',
        //     'formHtml':  this.modalForm.formBuilder.createEditWorkerForm(id),
        //     'submitBtnText': 'Edit'
        // }
    }
    getObjectData(id){
        this.requester.get(
            `${this.apiGetObject}?id=${id}`,
            this.successCallbackGetObject.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackGetObject(response) {
        this.populateForm(response.data);
    }

    populateForm(data) {}

    listenerSubmitFormEdit = (e) => {
        let data = this.validateFormData();
        data.id = e.currentTarget.getAttribute(this.dataIdAttribute);

        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmitEdit.bind(this),
                (response) => {
                    GifLoader.hide(this.requestTimeout);
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }
    successCallbackSubmitEdit(response) {
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
         * Regenerate the table of workers to show the newly added worker there
         */
        this.regenerateItem(response.data);
    }

    regenerateItem(data) {}
}
export default EditFormTemplate;