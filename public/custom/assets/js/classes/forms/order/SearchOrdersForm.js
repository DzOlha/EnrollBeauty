import DateRangePicker from "../../element/DateRangePicker.js";
import Notifier from "../../notifier/Notifier.js";
import Select2 from "../../element/Select2.js";

class SearchOrdersForm
{
    constructor(
        requester, submitApiUrl, table,
        apiUrlGetServicesAll, apiUrlGetWorkersAll,
        apiUrlGetDepartmentsAll, apiUrlGetAffiliatesAll,
        apiGetUserByEmail
    ) {
        this.requester = requester;
        this.submitFormUrl = submitApiUrl;
        this.table = table;
        this.populatedTable = false;

        this.searchTimer = null;

        this.workerSelectId = 'worker-name';
        this.userSelectId = 'user-email';
        this.serviceSelectId = 'service-name';
        this.departmentSelectId = 'department-name';
        this.affiliateSelectId = 'affiliate-name-address';
        this.dateRangeId = 'date-range-input';
        this.priceFromId = 'price-from';
        this.priceToId = 'price-to';
        this.statusSelectId = 'status-select';

        this.searchSelectFieldClass = 'select2-search__field';

        this.submitButtonId = 'submit-search-button';

        this.apiUrlGetServicesAll = apiUrlGetServicesAll;
        this.apiUrlGetWorkersAll = apiUrlGetWorkersAll;
        this.apiUrlGetDepartmentsAll = apiUrlGetDepartmentsAll;
        this.apiGetUserByEmail = apiGetUserByEmail;
        this.apiUrlGetAffiliatesAll = apiUrlGetAffiliatesAll;
    }

    init() {
        this.addListenerSubmitForm();
        this.addListenerSearchUser();

        this.getAllServices();
        this.getAllWorkers();
        this.getAllDepartments();
        this.getAllAffiliates();

        this.dateRangeObj = new DateRangePicker(this.dateRangeId);
        this.dateRangeObj._init(false, 7);

        this.submitForm();
    }

    submitForm()
    {
        this.listenerSubmitForm();
    }

    getAll(api, id) {
        this.requester.get(
            api,
            (response) => {
                Select2.populate(id, response.data);
            },
            this.errorSearchCallback.bind(this)
        )
    }

    getAllServices() {
        this.getAll(this.apiUrlGetServicesAll, this.serviceSelectId)
    }
    getAllDepartments() {
        this.getAll(this.apiUrlGetDepartmentsAll, this.departmentSelectId)
    }
    getAllWorkers() {
        this.getAll(this.apiUrlGetWorkersAll, this.workerSelectId)
    }
    getAllAffiliates() {
        this.getAll(this.apiUrlGetAffiliatesAll, this.affiliateSelectId)
    }

    addListenerSearchUser() {
        let user = document.querySelector(`.${this.userSelectId}-wrapper`);
        user.addEventListener('click', () => {
            let searchInput = document.querySelector(
                `.${this.searchSelectFieldClass}`
            )
            if(searchInput === null) return;
            searchInput.addEventListener('input', this.handleUserSearch);
        })
    }

    /**
     * Live search
     */
    handleUserSearch = (e) => {
        clearTimeout(this.searchTimer);

        let input = e.currentTarget;

        this.searchTimer = setTimeout(() => {
            let dataToSend = {
                'email': input.value
            };

            this.requester.get(
                this.apiGetUserByEmail,
                this.successCallbackSearchUser.bind(this),
                this.errorSearchCallback.bind(this),
                dataToSend,
            )
        }, 2000);
    }
    successCallbackSearchUser(response) {
        const select = $(`#${this.userSelectId}`);

        select.empty();
        select.append(new Option('Choose one', ' '))

        response.data.forEach((option) => {
            select.append(new Option(option.email, option.id));
        });

        // Recreate the Select2 element
        select.select2('destroy').select2();

        /**
         * Open the search result
         */
        select.select2('open');

        this.addListenerSearchUser();
    }

    addListenerSubmitForm()
    {
        let submit = document.getElementById(this.submitButtonId);
        submit.addEventListener('click', this.listenerSubmitForm);
    }

    listenerSubmitForm = (e) => {
        let data = this.validateForm();
        if(data) {
            /**
             * Set the data from search form into the table api url
             * to take into account the parameters of search when
             * will change the pagination or sorting items in the table
             */
            this.table.setData(data);
            this.table.setApiUrl(this.submitFormUrl + '?');
            if(!this.populatedTable) {
                this.table.getItemsPerPage();
                this.table.POPULATE();
                this.populatedTable = true;
            } else {
                this.table.regenerate();
            }
        }
    }

    validatePriceRange()
    {
        const priceFromInput = document.getElementById(this.priceFromId)
        const priceToInput = document.getElementById(this.priceToId)

        const priceFrom = priceFromInput.value !== ''
            ? parseFloat(priceFromInput.value)
            : priceFromInput.value;

        const priceTo = priceToInput.value !== ''
            ? parseFloat(priceToInput.value)
            : priceToInput.value;

        const priceFromError = document.getElementById(
            `${this.priceFromId}-error`
        );
        const priceToError = document.getElementById(
            `${this.priceToId}-error`
        );

        let validPriceFrom = true;
        let validPriceTo = true;
        let fromLessThanTo = true;

        if (isNaN(priceFrom)) {
            validPriceFrom = false;
            priceFromError.innerHTML =
                'Price must be a valid number (up to two decimal places). Example: 100 or 5.50';
            priceFromError.classList.add('text-danger');
            priceFromInput.classList.add('border-danger');
        }

        if (isNaN(priceTo)) {
            validPriceTo = false;
            priceToError.innerHTML =
                'Price must be a valid number (up to two decimal places). Example: 100 or 5.50';
            priceToError.classList.add('text-danger');
            priceToInput.classList.add('border-danger');
        }

        let fromGreaterThanTo = priceFrom !== '' && priceTo !== '' && priceFrom >= priceTo;

        if (fromGreaterThanTo) {
            fromLessThanTo = false;
            priceFromError.innerHTML = 'Price-from must be less than Price-to.'
            priceFromError.classList.add('text-danger');
            priceFromInput.classList.add('border-danger');
        }

        if (validPriceFrom && fromLessThanTo) {
            priceFromInput.classList.remove('border-danger');
            priceFromError.classList.remove('text-danger');
            priceFromError.innerHTML = '';
        }
        if (validPriceTo) {
            priceToInput.classList.remove('border-danger');
            priceToError.classList.remove('text-danger');
            priceToError.innerHTML = '';
        }

        if (fromLessThanTo && validPriceFrom && validPriceTo) {
            return {
                'priceBottom': priceFrom,
                'priceTop': priceTo
            }
        }
        return false;
    }


    validateForm()
    {
        let dataToSend =
        {
            'worker_id': '',
            'user_id': '',
            'department_id': '',
            'service_id': '',
            'affiliate_id': '',
            'start_date': '',
            'end_date': '',
            'price_bottom': '',
            'price_top': '',
            'status': '',
        }
        /**
         * Worker
         */
        let worker = document.getElementById(this.workerSelectId);
        if(worker) {
            dataToSend.worker_id = worker.value.trim();
        }

        /**
         * User
         */
        let user = document.getElementById(this.userSelectId);
        if(user) {
            dataToSend.user_id = user.value.trim();
        }

        /**
         * Department
         */
        let dep = document.getElementById(this.departmentSelectId);
        if(dep) {
            dataToSend.department_id = dep.value.trim();
        }

        /**
         * Service
         */
        let service = document.getElementById(this.serviceSelectId);
        if(service) {
            dataToSend.service_id = service.value.trim();
        }

        /**
         * Affiliate
         */
        let affiliate = document.getElementById(this.affiliateSelectId);
        if(affiliate) {
            dataToSend.affiliate_id = affiliate.value.trim();
        }

        /**
         * @type {*|jQuery|HTMLElement}
         *
         * Get and check DATE range
         */
        let dateRange = this.dateRangeObj.validate();
        if (dateRange) {
            dataToSend.start_date = dateRange[0];
            dataToSend.end_date = dateRange[1];
        }

        /**
         * Get and validate PRICE range
         */
        let priceRange = this.validatePriceRange();
        if (priceRange) {
            dataToSend.price_bottom = priceRange.priceBottom;
            dataToSend.price_top = priceRange.priceTop;
        }

        /**
         * Status
         */
        let status = document.getElementById(this.statusSelectId);
        if(status) {
            dataToSend.status = status.value.trim();
        }

        if (dateRange && priceRange) {
            return dataToSend;
        }
        return false;
    }

    errorSearchCallback(response) {
        Notifier.showErrorMessage(response.error);
    }
}
export default SearchOrdersForm;