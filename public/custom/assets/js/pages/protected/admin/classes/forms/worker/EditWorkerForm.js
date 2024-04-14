import AddWorkerForm from "./AddWorkerForm.js";
import Select2 from "../../../../../../classes/element/Select2.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";


class EditWorkerForm extends AddWorkerForm
{
    constructor(
        requester, submitUrl, getPositionsApi, getRolesApi,
        getWorkerApi,
        modalForm, optionBuilder, workersTable
    ) {
        super(
            requester, submitUrl, getPositionsApi,
            getRolesApi, modalForm, optionBuilder, workersTable
        );
        this.manageBase = 'manage';
        this.dataIdAttribute = 'data-worker-id';
        this.apiGetWorker = getWorkerApi;
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
        this.modalForm.setSelectors('modalEditWorker');
        this.submitButtonId = this.modalForm.modalSubmitId;

        /**
         * Get the id of the worker we want to edit
         */
        let id = e.currentTarget.getAttribute(this.dataIdAttribute);

        this.modalForm.show(
            'Edit the Worker',
            this.modalForm.formBuilder.createEditWorkerForm(id),
            'Edit'
        );

        /**
         * Save the id of the worker into data attribute of the submit button
         */
        let submit = document.getElementById(this.modalForm.modalSubmitId);
        submit.setAttribute(this.dataIdAttribute, id);

        this._initForm();

        this.getObjectData(id);

        this.modalForm.close();
        this.addListenerSubmitForm();

        this.deleteCallback(id);
    }
    _initForm()
    {
        this._initSelect2();
        this.getPositions();
        this.getRoles();
    }
    getObjectData(id) {
        this.requester.get(
            `${this.apiGetWorker}?id=${id}`,
            this.successCallbackGetWorker.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }
    successCallbackGetWorker(response) {
        this._populateForm(response.data);
    }

    /**
     * @param data = {
     *     id:
     *     name:
     *     surname:
     *     email:
     *     position_id:
     *     role_id:
     *     gender:
     *     age:
     *     years_of_experience:
     *     salary:
     * }
     * @protected
     */
    _populateForm(data) {
        /**
         * Set the name
         */
        $(`#${this.nameInputId}`).val(data.name);

        /**
         * Set surname
         */
        $(`#${this.surnameInputId}`).val(data.surname);

        /**
         * Set email
         */
        $(`#${this.emailInputId}`).val(data.email);

        /**
         * Set position
         */
        this._setSelect2(this.positionSelectId, data.position_id, 'Choose one');

        /**
         * Set role
         */
        this._setSelect2(this.roleSelectId, data.role_id, 'Choose one');

        /**
         * Set gender
         */
        this._setSelect2(this.genderSelectId, data.gender ?? '', 'Choose one');

        /**
         * Set age
         */
        $(`#${this.ageInputId}`).val(data.age);

        /**
         * Set years of experience
         */
        $(`#${this.experienceInputId}`).val(data.years_of_experience);

        /**
         * Set salary
         */
        $(`#${this.salaryInputId}`).val(data.salary ?? '');
    }

    _setSelect2(selectId, value, placeholder) {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        Select2._setSelect2(selectId, value, placeholder, modalBody);
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     name:
         *     surname:
         *     email:
         *     position_id:
         *     role_id:
         *     gender:
         *     age:
         *     experience:
         *     salary:
         * }
         */
        let data = this.validateInputs();
        data.id = e.currentTarget.getAttribute(this.dataIdAttribute);

        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.put(
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
     *         surname:
     *         email:
     *         position:
     *         experience:
     *         salary:
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
         * Regenerate the table of workers to show the newly added worker there
         */
       this._regenerateWorkerRow(response.data);
    }
    _regenerateWorkerRow(data)
    {
        $(`tr[${this.dataIdAttribute}=${data.id}]`).replaceWith(
            this.workersTable.populateRow(data)
        );
        this.addListenerEdit(data.id);
    }

}
export default EditWorkerForm;