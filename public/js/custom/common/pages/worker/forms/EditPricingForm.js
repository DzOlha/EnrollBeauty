
import AddPricingForm from "./AddPricingForm.js";
import API from "../../../../common/pages/api.js";
class EditPricingForm extends AddPricingForm {
    constructor(requester, modalForm, optionBuilder, pricingTable) {
        super(
            requester, modalForm, optionBuilder, pricingTable
        );
        this.submitActionUrl = API.WORKER.API.PROFILE["service-pricing"].edit;
        this.managePricingClass = 'manage-pricing';
        this.oldServiceId = null;
        this.oldPrice = null;
        this.updateCallback = this.addListenerManagePricing.bind(this);
    }
    addListenerManagePricing() {
        setTimeout(() => {
            let manageButtons = Array.from(
                document.getElementsByClassName(this.managePricingClass)
            );
            console.log(manageButtons);
            manageButtons.forEach((button) => {
                button.addEventListener('click', this.handleShowEditPricingForm)
            })
        }, 400)
    }
    handleShowEditPricingForm = (e) =>
    {
        e.preventDefault();
        this.oldServiceId = e.currentTarget.getAttribute('data-service-id');
        this.oldPrice = e.currentTarget.getAttribute('data-service-price');

        this.modalForm.setSelectors('modalEditPricing');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Edit Pricing',
            this.modalForm.formBuilder.createEditPricingForm(),
            'Update'
        );
        this._initSelect2();
        this.getServices();
        setTimeout(() => {
            this.populateForm();
        }, 100);
        this.modalForm.close();
        this.addListenerSubmitForm();
    }
    populateForm() {
        console.log(this.oldServiceId);
        console.log(this.oldPrice);

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
            if($(`#${this.serviceSelectId}`).val() === this.oldServiceId
            && $(`#${this.priceInputId}`).val() === this.oldPrice) {
                notChanged = true;
                error.html('Please, modify service price to submit the form!')
            }
            if(!notChanged) {
                error.html('');
                return data;
            }
        }
    }
    successCallbackSubmit(response) {
        super.successCallbackSubmit(response);
        this.oldPrice = null;
        this.oldServiceId = null;
    }
}
export default EditPricingForm;