import AddPositionForm from "./AddPositionForm.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";


class EditPositionForm extends AddPositionForm
{
    constructor(
        requester, getPositionUrl,
        modalForm, table,
        getDepartmentUrl, apiUrl, withDelete = true
    )
    {
        super(requester, modalForm, table, getDepartmentUrl, apiUrl);

        this.withDelete = withDelete;
        this.departmentId = null;
        this.manageBase = 'manage';
        this.dataIdAttribute = 'data-position-id';
        this.apiGetObject = getPositionUrl;
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

        this.modalForm.setSelectors('modalEditPosition');
        this.submitButtonId = this.modalForm.modalSubmitId;

        this.modalForm.show(
            'Edit Position',
            this.modalForm.formBuilder.createEditPositionForm(id, this.withDelete),
            'Update'
        );

        this.getObjectData(id);
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
        submitBtn.setAttribute(this.dataIdAttribute, id);
    }

    getObjectData(id)
    {
        this.requester.get(
            `${this.apiGetObject}?id=${id}`,
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
                $(`#${this.positionNameInputId}`).val(response.data.name);
                this.getDepartments();
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    successCallbackGetDepartments(response)
    {
        super.successCallbackGetDepartments(response);

        this.departmentSelect2.set(this.departmentId);
        this.departmentId = null;
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

    validateFormData() {
        let data = super.validateFormData();
        if(data) {
            /**
             * Get the position id from the submit button
             * @type {string}
             */
            data.id = document.getElementById(this.submitButtonId)
                              .getAttribute(this.dataIdAttribute);
        }
        return data;
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
         * the changes of the position on the frontend
         */
        $(`tr[${this.dataIdAttribute}=${response.data.id}]`).replaceWith(
            this.table.populateRow(response.data)
        );
        this.addListenerManage(response.data.id);
    }
}
export default EditPositionForm;