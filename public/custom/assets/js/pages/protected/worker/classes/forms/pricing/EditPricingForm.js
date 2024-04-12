import AddPricingForm from "./AddPricingForm.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";

class EditPricingForm extends AddPricingForm {
    constructor(
        requester, submitUrl, apiGetServices, apiGetExistingServicePricing,
        modalForm, optionBuilder, pricingTable) {
        super(
            requester, submitUrl, apiGetServices, apiGetExistingServicePricing,
            modalForm, optionBuilder, pricingTable
        );
        this.managePricingClass = 'manage-pricing';

        this.manageBase = 'manage';
        this.oldServiceId = null;
        this.oldPrice = null;

        this.pricingId = null;
        this.dataAttrPricingId = 'data-pricing-id';
    }

    setDeleteCallback(callback, context) {
        this.deleteCallback = callback.bind(context);
    }

    addListenerManagePricing(id) {
        let selector = `${this.manageBase}-${id}`;
        let btn = document.getElementById(
            selector
        );
        btn.addEventListener('click', this.handleShowEditPricingForm)
    }
    handleShowEditPricingForm = (e) =>
    {
        e.preventDefault();
        this.oldServiceId = e.currentTarget.getAttribute('data-service-id');
        this.oldPrice = e.currentTarget.getAttribute('data-service-price');

        this.pricingId = e.currentTarget.getAttribute(this.dataAttrPricingId);

        this.modalForm.setSelectors('modalEditPricing');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Edit Pricing',
            this.modalForm.formBuilder.createEditPricingForm(this.pricingId),
            'Update'
        );
        this._initSelect2();
        this.getServices();

        this.modalForm.close();
        this.deleteCallback(this.pricingId);
        this.addListenerSubmitForm();
    }
    successCallbackGetServices(response) {
        let serviceSelect = $(`#${this.serviceSelectId}`);
        this._populateSelectOptions(serviceSelect, response.data);
        this._initSelect2();

        this.populateForm();
    }
    populateForm() {
        // console.log(this.oldServiceId);
        // console.log(this.oldPrice);

        // Assuming this is your select element
        let select = $(`#${this.serviceSelectId}`);

        // Check if Select2 is initialized
        if (select.data('select2')) {
            // Select2 is initialized, set the value
            select.val(this.oldServiceId).select2();
            select.prop('disabled', true);
        } else {
            // Select2 is not initialized yet, wait for it to initialize
            select.on('select2:open', function () {
                select.val(this.oldServiceId).select2();
                select.prop('disabled', true);
            });
        }

        // Set other values
        $(`#${this.priceInputId}`).val(this.oldPrice);
    }

    validateInputs() {
        let data = super.validateInputs();
        let notChanged = false;
        let error = $(`#${this.priceInputId}-error`);
        if(data) {
            //$(`#${this.serviceSelectId}`).val() === this.oldServiceId
            if($(`#${this.priceInputId}`).val() === this.oldPrice) {
                notChanged = true;
                error.html('Please, modify service price to submit the form!')
            }
            if(!notChanged) {
                error.html('');
                return data;
            }
        }
        return false;
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *         id:
     *         service_id:
     *         name:
     *         price:
     *         currency:
     *         updated_datetime:
     *     }
     * }
     */
    successCallbackSubmit(response) {
        /**
         * Reset the properties
         * @type {null}
         */
        this.oldPrice = null;
        this.oldServiceId = null;
        this.pricingId = null;

        /**
         * Hide gif loader
         */
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
         * Update the single row of the table that
         * has been modified right now
         */
        $(`tr[${this.dataAttrPricingId}=${response.data.id}]`).replaceWith(
            this.pricingTable.populateRow(response.data)
        );
        this.addListenerManagePricing(response.data.id);
    }
}
export default EditPricingForm;