import Form from "../../../../user/classes/forms/Form.js";
import Select2 from "../../../../../../classes/element/Select2.js";
import Input from "../../../../../../classes/element/Input.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import AddressRegex from "../../../../../../classes/regex/impl/AddressRegex.js";
import NameRegex from "../../../../../../classes/regex/impl/NameRegex.js";


class AddAffiliateForm extends Form
{
    constructor(
        requester, modalForm, table,
        apiGetWorkers, addApiUrl
    ) {
        super(
            '',
            '',
            addApiUrl,
            requester
        );
        this.modalForm = modalForm;
        this.table = table;

        this.addTriggerId = 'add-affiliate-trigger';

        this.nameInputId = 'name-input';

        this.countrySelect2 = null;
        this.countrySelectId = 'country-select';

        this.citySelect2 = null;
        this.citySelectId = 'city-select';

        this.dataForSelect = [
            {
                id: 0,
                name: 'Ukraine',
                cities: [
                    {
                        id: 0,
                        name: 'Kyiv'
                    },
                    {
                        id: 1,
                        name: 'Lviv'
                    }
                ]
            },
        ];
        this.addressInputId = 'address-input';

        this.managerSelect2 = null;
        this.managerSelectId = 'manager-select';

        this.departmentSelect2 = null;

        this.modalBodyClass = 'modal-body';

        this.apiGetWorkers = apiGetWorkers;
    }
    init() {
        this.addListenerShowAddForm();
    }
    _initForm() {
        this.getWorkers();
    }

    populateCountrySelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);

        this.countrySelect2 = new Select2(
            this.modalForm.modalContentId,
            'Country',
            'Choose country',
            this.countrySelectId,
            true,
            true,
            modalBody
        );
        this.countrySelect2.populate(this.dataForSelect);
        this.addListenerSelectCountry();
    }

    addListenerSelectCountry()
    {
        this.countrySelect2.addCallbackAfterSelectValue(
            this.populateCityForCountry.bind(this)
        )
    }

    populateCitySelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);

        this.citySelect2 = new Select2(
            this.modalForm.modalContentId,
            'City',
            'Choose city',
            this.citySelectId,
            true,
            true,
            modalBody
        );
        this.citySelect2.populate([]);
    }

    populateCityForCountry(countryValue) {
        this.citySelect2.populate(this.dataForSelect[countryValue]['cities']);
    }

    getWorkers()
    {
        this.requester.get(
            `${this.apiGetWorkers}`,
            this.successCallbackGetWorkers.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackGetWorkers(response) {
        this.populateManagerSelect2(response.data);

        this.populateCountrySelect2();
        this.populateCitySelect2();
        this.appendStreetAddressInput();
    }

    populateManagerSelect2(data) {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);

        this.managerSelect2 = new Select2(
            this.modalForm.modalContentId,
            'Manager',
            'Choose manager',
            this.managerSelectId,
            false,
            true,
            modalBody
        );
        this.managerSelect2.populate(data);
    }

    appendStreetAddressInput() {
        let parent = document.getElementById(this.modalForm.modalContentId);
        parent.insertAdjacentHTML(
            'beforeend',
            this.modalForm.formBuilder.createStreetAddressInput()
        );
    }

    /**
     * Add listener to the 'Add Affiliate' button
     */
    addListenerShowAddForm() {
        let trigger = document.getElementById(this.addTriggerId);
        trigger.addEventListener('click', this.handleShowAddForm);
    }
    handleShowAddForm = () => {
        this.modalForm.setSelectors('modalAddAffiliate');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Add New Affiliate',
            this.modalForm.formBuilder.createAffiliateNameInput(),
            'Add'
        );
        this._initForm();
        this.modalForm.close();
        this.addListenerSubmitForm();
    }

    nameValidationCallback = (value) => {
        let result = {};
        if(!value) {
            result.error = "Affiliate name is required field!";
            return result;
        }

        let pattern = new NameRegex(3, 100, true);
        if(!pattern.test(value)) {
            result.error = "Affiliate name should have length between 3-100 characters and contain only letters and whitespaces!";
            return result;
        }

        return result;
    }

    addressValidationCallback = (value) => {
        let result = {};
        if(!value) {
            result.error = "Street address is a required field!";
            return result;
        }

        let pattern = new AddressRegex();
        //let pattern2 = /^(вул\.)\. ([\u0400-\u04FFіїІЇ\s]+,)? \d+[\u0400-\u04FFіїІЇ]?\b/;


        if(!pattern.test(value)) {
            result.error = "Invalid format for the street address! Please, follow the example like 'str. Street, 3'";
            return result;
        }

        if(value.length > 255) {
            result.error = 'Street address should not exceed 255 characters!';
            return result;
        }

        return result;
    }

    /**
     *
     * @returns {{[p: string]: *}|boolean} = {
     *     name:
     *     manager_id:
     *     country:
     *     city:
     *     address:
     * }
     */
    validateFormData() {
        let name = Input.validateInput(
            this.nameInputId, 'name', this.nameValidationCallback
        );
        let manager = this.managerSelect2.validate('manager_id');
        let country = this.countrySelect2.validate('value');
        let city = this.citySelect2.validate('value');

        let address = Input.validateInput(
            this.addressInputId, 'address', this.addressValidationCallback
        );

        if(name && manager && country && city && address) {
            let countryName = this.dataForSelect[country.value]['name'];
            let cityName = this.dataForSelect[country.value]['cities'][city.value]['name'];

            return {
                ...name, ...manager,
                'country': countryName,
                'city': cityName,
                ...address
            }
        }
        return false;
    }

    listenerSubmitForm = (e) => {
        /**
         * {
         *     name:
         *     manager_id:
         *     country:
         *     city:
         *     address:
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
         * Regenerate the table
         */
        this.table.regenerate();
    }
}
export default AddAffiliateForm;