
import AddPricingForm from "./AddPricingForm.js";
import API from "../../../../../common/pages/api.js";
class EditPricingForm extends AddPricingForm {
    constructor(requester, modalForm, optionBuilder, pricingTable) {
        super(
            requester, modalForm, optionBuilder, pricingTable
        );
        this.submitActionUrl = API.WORKER.API.PROFILE["service-pricing"].edit;
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
        //console.log(this.oldServiceId);
        //console.log(this.oldPrice);

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
    successCallbackSubmit(response) {
        super.successCallbackSubmit(response);
        this.oldPrice = null;
        this.oldServiceId = null;
        this.pricingId = null;
    }
}
export default EditPricingForm;