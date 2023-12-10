
import Form from "../../user/forms/Form.js";
import Notifier from "../../classes/notifier/Notifier.js";
import Cookie from "../../classes/cookie/Cookie.js";
import GifLoader from "../../classes/loader/GifLoader.js";
import API from "../../../../common/pages/api.js";

class AddPricingForm extends Form {
    constructor(requester, modalForm, optionBuilder, pricingTable) {
        super(
            '',
            '',
            API.WORKER.API.PROFILE["service-pricing"].add,
            requester
        );
        //console.log(modalForm.modalSubmitId);
        //this.requester = requester;
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.pricingTable = pricingTable;
        this.addPricingTriggerId = 'add-pricing-trigger';

        this.serviceSelectId = 'service-select';
        this.priceInputId = 'price-input';

        this.select2ContainerClass = 'select2-container';

        this.serviceParentClass = 'service-selector-parent';

        this.modalBodyClass = 'modal-body';

        this.apiGetServices = API.WORKER.API.SERVICE.get.all;
        this.apiGetExistingServicePricing = API.WORKER.API.PROFILE["service-pricing"].get.all;

        this.modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
    }
    setUpdateCallback(callback, context) {
        this.updateCallback = callback.bind(context);
    }
    _initSelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        this._initServiceSelect2(modalBody);
    }
    _initServiceSelect2(modalBody) {
        $(`#${this.serviceSelectId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Choose one"
        });
    }
    _checkSelectAndSetErrorBorder(value, wrapperClass, errorId, errorMessage) {
        let wrapper = $(`.${wrapperClass} .${this.select2ContainerClass}`);
        let errorBlock = $(`#${errorId}`);
        if(!value) {
            wrapper.addClass('border-error');
            errorBlock.html(errorMessage);
            return false;
        } else {
            wrapper.removeClass('border-error');
            errorBlock.html('');
        }
        return true;
    }
    /**
     * Add listener to the 'Add New Worker' button
     */
    addListenerShowAddPricingForm() {
        let trigger = document.getElementById(this.addPricingTriggerId);
        trigger.addEventListener('click', this.handleShowAddPricingForm);
    }

    /**
     * Handle the click on 'Add Ne Worker' button to
     * show the modal window with the appropriate form
     */
    handleShowAddPricingForm = () => {
        this.modalForm.setSelectors('modalAddPricing');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Add New Pricing',
            this.modalForm.formBuilder.createAddPricingForm(),
            'Add'
        );
        this._initSelect2();
        this.getServices();
        this.modalForm.close();
        this.addListenerSubmitForm();
    }
    getServices() {
        this.requester.get(
            this.apiGetServices,
            this.successCallbackGetServices.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    _populateSelectOptions(parent, data) {
        parent.html('');
        parent.append(this.optionBuilder.createOptionLabel());

        data.forEach((item) => {
            parent.append(this.optionBuilder.createOption(
                item.id, item.name
            ));
        })
        parent.select2('destroy').select2();
    }
    successCallbackGetServices(response) {
        //console.log(response);
        let serviceSelect = $(`#${this.serviceSelectId}`);
        this._populateSelectOptions(serviceSelect, response.data);
        this.getExistingServicePricing();

        this._initSelect2();
    }
    getExistingServicePricing() {
        this.requester.get(
            this.apiGetExistingServicePricing,
            this.successCallbackGetExistingServicePricing.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    successCallbackGetExistingServicePricing(response) {
        /**
         * Disable existing services to not let the user
         * add pricing for them
         */
        for(const key in response.data) {
            let pricing = response.data[key];

            $(`#${this.serviceSelectId} option`).each(function() {
                if($(this).val() == pricing.service_id) {
                    $(this).prop('disabled', true);
                }
            });
        }
        //this._initSelect2();
    }
    validateService() {
        let serviceSelect = document.getElementById(this.serviceSelectId);
        /**
         * Validate position id
         */
        let service_id = serviceSelect.value.trim();
        let validService = this._checkSelectAndSetErrorBorder(
            service_id, this.serviceParentClass,
            `${this.serviceSelectId}-error`, "Service is required field!"
        );
        if(validService) {
            return {
                'service_id': service_id,
            }
        } else {
            return false;
        }
    }
    validatePrice() {
        let priceInput = document.getElementById(this.priceInputId);
        let priceNumber = priceInput.value.trim();
        let validPrice = true;
        let priceError = $(`#${this.priceInputId}-error`);

        if(!priceNumber) {
            validPrice = false;
            priceInput.classList.add('border-danger');
            priceError.html('Price is required field!!');
        }
        if(isNaN(priceNumber)) {
            validPrice = false;
            priceInput.classList.add('border-danger');
            priceError.html('Incorrect price number is provided!');
        }
        if(priceNumber < 0 && validPrice) {
            validPrice = false;
            priceInput.classList.add('border-danger');
            priceError.html('Price can not be negative number!');
        }

        if(validPrice) {
            if(priceInput.classList.contains('border-danger')) {
                priceInput.classList.remove('border-danger');
            }
            priceError.html('');
        }

        if(validPrice) {
            return {
                'price': priceNumber,
            }
        } else {
            return false;
        }
    }

    validateInputs() {
        let serviceId = this.validateService();
        let price = this.validatePrice();

        if(serviceId && price) {
            return {
                ...serviceId, ...price
            }
        } else {
            return false;
        }
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     service_id:
         *     price:
         * }
         */
        let data = this.validateInputs();
        //console.log(data);

        if(data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    GifLoader.hide(this.requestTimeout );
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
         * Regenerate the table of pricing to show the newly added pricing there
         */
        this.pricingTable.sendApiRequest(
            this.pricingTable.getItemsPerPage(),
            Cookie.get(this.pricingTable.currentPageCookie)
        );

        /**
         * Update event listener on edit pricing
         */
        this.updateCallback();
    }
}
export default AddPricingForm;
