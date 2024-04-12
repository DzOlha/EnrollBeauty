import AddAffiliateForm from "./AddAffiliateForm.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";

class EditAffiliateForm extends AddAffiliateForm
{
    constructor(
        requester, getAffiliateApi, modalForm, table,
        apiGetWorkers, editApiUrl,  withDelete = true
    ) {
        super(requester, modalForm, table, apiGetWorkers, editApiUrl);

        this.withDelete = withDelete;
        // this.managerId = null;
        this.manageBase = 'manage';
        this.dataIdAttribute = 'data-affiliate-id';
        this.apiGetObject = getAffiliateApi;
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

        this.modalForm.setSelectors('modalEditObject');
        this.submitButtonId = this.modalForm.modalSubmitId;

        this.modalForm.show(
            'Edit Affiliate',
            this.modalForm.formBuilder.createAffiliateNameInputEdit(id, this.withDelete),
            'Update'
        );

        /**
         * Set the service id on the update button to be able
         * to retrieve this info if the user submit the form
         * @type {HTMLElement}
         */
        let submitBtn = document.getElementById(this.submitButtonId);
        submitBtn.setAttribute(this.dataIdAttribute, id);

        this._initForm();

        this.modalForm.close();
        this.addListenerSubmitForm();

        if(this.withDelete) {
            this.deleteCallback(id);
        }
    }

    successCallbackGetWorkers(response) {
        super.successCallbackGetWorkers(response);

        let id = document.getElementById(this.submitButtonId)
            .getAttribute(this.dataIdAttribute);

        this.getObjectData(id);
    }

    getObjectData(id) {
        this.requester.get(
            `${this.apiGetObject}?id=${id}`,
            (response) => {
                this._populateFormWithData(response.data);
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    /**
     *
     * @param data = {
     *          id:
     *          name:
     *          country:
     *          city:
     *          address:
     *          manager_id:
     *     }
     */
    _populateFormWithData(data) {
        /**
         * Set affiliate name
         */
        $(`#${this.nameInputId}`).val(data.name);

        /**
         * Set manager id
         */
        this.managerSelect2.set(data.manager_id);

        /**
         * Set country
         */
        const countryToSet = this.dataForSelect.find(
            country => country.name === data.country
        ) || null;
        this.countrySelect2.set(this.dataForSelect[countryToSet.id].id)
        this.populateCityForCountry(this.countrySelect2.get());

        /**
         * Set city
         */
        const cityToSet = countryToSet.cities.find(
            city => city.name === data.city
        ) || null;
        this.citySelect2.set(this.dataForSelect[countryToSet.id]['cities'][cityToSet.id].id)

        /**
         * Set street address
         */
        $(`#${this.addressInputId}`).val(data.address);
    }

    validateFormData() {
        let data = super.validateFormData();
        if(data) {
            /**
             * Get the object id from the submit button
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
     *          id:
     *          name:
     *          country:
     *          city:
     *          address:
     *          manager_id:
     *          manager_name:
     *          manager_surname:
     *          created_date:
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
         * the changes of the affiliate on the frontend
         */
        $(`tr[${this.dataIdAttribute}=${response.data.id}]`).replaceWith(
            this.table.populateRow(response.data)
        );
        this.addListenerManage(response.data.id);
    }

}
export default EditAffiliateForm;