import Form from "./../Form.js";
import API from "../../../../../../config/api/api.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";

class SearchScheduleForm extends Form {
    constructor(
        requester, submitUrl,
        scheduleRenderer,
        optionBuilder, dateRenderer, api = false
    ) {
        super(
            '',
            'submit-search-button',
            submitUrl,
            requester
        );
        this.renderer = scheduleRenderer;
        this.optionBuilder = optionBuilder
        this.dateRenderer = dateRenderer;
        this.serviceNameSelectId = 'service-name';
        this.serviceNameSelectWrapper = `select2-${this.serviceNameSelectId}-container`;

        this.workerNameSelectId = 'worker-name';
        this.workerNameSelectContainer = `select2-${this.workerNameSelectId}-container`;

        this.affiliateSelectId = 'affiliate-name-address';
        this.affiliateSelectWrapper = `select2-${this.affiliateSelectId}-container`;

        this.dateRangeId = 'date-range-input';
        this._initializeDateRangePicker();

        this.datesInputClass = 'date-range';
        this.dateRangeErrorId = 'date-range-input-error';

        this.startTimeSelectId = 'start-time';
        this.selectStartTimeWrapper = `select2-${this.startTimeSelectId}-container`;
        this.startTimeErrorId = 'start-time-select-error';

        this.endTimeSelectId = 'end-time';
        this.endTimeErrorId = 'end-time-select-error';

        this.priceFromInputId = 'price-from';
        this.priceFromErrorId = 'price-from-input-error';

        this.priceToInputId = 'price-to';
        this.priceToErrorId = 'price-to-input-error';

        if(api !== false) {
            this.apiUrlGetWorkers = api.API.SERVICE.get.workers.all;
            this.apiUrlGetServices = api.API.WORKER.get.services.all;

            this.apiUrlGetAffiliatesAll = api.API.AFFILIATE.get.all;
            this.apiUrlGetWorkersAll = api.API.WORKER.get.all;
            this.apiUrlGetServicesAll = api.API.SERVICE.get.all;
        } else {
            this.apiUrlGetWorkers = API.USER.API.SERVICE.get.workers.all;
            this.apiUrlGetServices = API.USER.API.WORKER.get.services.all;

            this.apiUrlGetAffiliatesAll = API.USER.API.AFFILIATE.get.all;
            this.apiUrlGetWorkersAll = API.USER.API.WORKER.get.all;
            this.apiUrlGetServicesAll = API.USER.API.SERVICE.get.all;
        }
    }

    init() {
        /**
         * Get information for select elements of the form of searching
         * available schedules for appointments
         */
        this.getServices();
        this.getWorkers();
        this.getAffiliates();

        /**
         * Add listener to offer only valid workers for the selected service
         */
        this.addListenerChangeServiceName();

        /**
         * Add the listener to handle submission of the form of schedule searching
         */
        this.addListenerSubmitForm();

        /**
         * Make initial submission of the form to show the available schedules
         * in all services/departments for the current date
         */
        this.handleFormSubmission();
    }

    _initializeDateRangePicker() {
        const currentDate = new Date();

        const tomorrow = new Date();
        tomorrow.setDate(currentDate.getDate()+1);

        $(`#${this.dateRangeId}`).daterangepicker({
            locale: {
                format: 'DD/MM/YYYY', // Date format
            },
            opens: 'right',
            showDropdowns: false,
            startDate: currentDate,
            endDate: tomorrow,
            minDate: currentDate,  // Minimum selectable date (current date)
        });
    }

    listenerSubmitForm = () => {
        let dataToSend = this.collectDataToSend();

        //console.log(JSON.stringify(dataToSend));
        // Check if there are no div.error elements with text content
        const noErrorWithText = $("div.error").get().every(function (element) {
            return $(element).text().trim() === '';
        });

        // Handle form submission if there are no errors
        if (noErrorWithText && dataToSend) {
            this.handleFormSubmission();
        }
    }

    successCallbackSubmit(response) {
        this.renderer.render(response);
    }

    errorCallbackSubmit(response) {
        Notifier.showErrorMessage(response.error);
    }

    validatePriceRange() {
        const priceFromInput = document.getElementById(this.priceFromInputId)
        const priceToInput = document.getElementById(this.priceToInputId)

        const priceFrom = priceFromInput.value !== ''
            ? parseFloat(priceFromInput.value)
            : priceFromInput.value;

        const priceTo = priceToInput.value !== ''
            ? parseFloat(priceToInput.value)
            : priceToInput.value;

        const priceFromError = document.getElementById(this.priceFromErrorId);
        const priceToError = document.getElementById(this.priceToErrorId);

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

    validateTimeRange() {
        let validTimeRange = true;
        let selectStartWrapper = $(`#${this.selectStartTimeWrapper}`);

        let startTimeSelect = $(`#${this.startTimeSelectId}`);
        let endTimeSelect = $(`#${this.endTimeSelectId}`);

        let startTime = parseInt(startTimeSelect.val());
        let endTime = parseInt(endTimeSelect.val());

        let errorStart = $(`#${this.startTimeErrorId}`)

        let startGreaterEnd = startTime > endTime;
        if (startGreaterEnd) {
            validTimeRange = false;
            selectStartWrapper.addClass('border-error');

            errorStart.html(
                'The start time should be less or equal to the end time'
            );
            errorStart.addClass('text-danger')
        }

        if (validTimeRange) {
            if (selectStartWrapper.hasClass('border-error'))
                selectStartWrapper.removeClass('border-error');

            errorStart.html('');
            errorStart.removeClass('text-danger')

            return {
                'startTime': startTimeSelect.val(),
                'endTime': endTimeSelect.val()
            }
        }
        return false;
    }

    validateDateRange() {
        let dateRangeInput = $(`.${this.datesInputClass}`);
        let dateRange = dateRangeInput.val()
            .split('-')
            .map(
                (item) => this.dateRenderer.getUnixTimestamp(item)
            );

        let validDateRange = true;
        let error = $(`#${this.dateRangeErrorId}`);

        if (dateRange[0] > dateRange[1]) {
            validDateRange = false;
            dateRangeInput.addClass('border-danger');

            error.html(
                'The start date should be less or equal to the end date'
            );
            error.addClass('text-danger')
        }

        if (validDateRange) {
            if (dateRangeInput.hasClass('border-danger'))
                dateRangeInput.removeClass('border-danger');

            error.html('');
            error.removeClass('text-danger');

            return dateRange;
        }
        return false;
    }

    collectDataToSend(idAssoc = false) {
        let dataToSend = {
            'service_id': '',
            'worker_id': '',
            'affiliate_id': '',
            'start_date': '',
            'end_date': '',
            'start_time': '',
            'end_time': '',
            'price_bottom': '',
            'price_top': ''
        }

        dataToSend.service_id =
            document.getElementById(this.serviceNameSelectId).value
                .trim();

        dataToSend.worker_id =
            document.getElementById(this.workerNameSelectId).value
                .trim();

        dataToSend.affiliate_id =
            document.getElementById(this.affiliateSelectId).value
                .trim();

        /**
         * @type {*|jQuery|HTMLElement}
         *
         * Get and check DATE range
         */
        let dateRange = this.validateDateRange();
        if (dateRange) {
            dataToSend.start_date = dateRange[0];
            dataToSend.end_date = dateRange[1];
        }

        /**
         *
         * Get and check the TIME range
         */
        let timeRange = this.validateTimeRange()
        if (timeRange) {
            dataToSend.start_time = timeRange.startTime;
            dataToSend.end_time = timeRange.endTime;
        }

        /**
         * Get and validate PRICE range
         */
        let priceRange = this.validatePriceRange();
        if (priceRange) {
            dataToSend.price_bottom = priceRange.priceBottom;
            dataToSend.price_top = priceRange.priceTop;
        }

        if (dateRange && timeRange && priceRange) {
            return dataToSend;
        }
        return false;
    }

    /**
     * Populate all available workers for the selected Service Name
     *
     * response.data {
     *     0: {
     *         id:
     *         name:
     *     }
     *     ....
     * }
     */
    addListenerChangeServiceName() {
        let serviceNameSelect = $(`#${this.serviceNameSelectId}`);

        serviceNameSelect.on('select2:select', (e) => {
            let value = e.params.data.id;
            //console.log(value);
            /**
             * If nothing is selected ot user reset the filter
             * we load all workers into appropriate select
             */
            if (!value) {
                this.requester.get(
                    this.apiUrlGetWorkersAll,
                    this.successCallbackGetWorkers.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
            /**
             * If user select some service name
             * we load into workers selector only workers
             * who can provide selected service
             */
            else {
                this.requester.get(
                    `${this.apiUrlGetWorkers}?service_id=${value}`,
                    this.successCallbackGetWorkers.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
        })
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

    successCallbackGetWorkers(response) {
        let workersSelect = $(`#${this.workerNameSelectId}`);
        this._populateSelectOptions(workersSelect, response.data)
    }


    /**
     * Populate all available services for the selected worker
     *
     * response.data {
     *     0: {
     *         id:
     *         name:
     *     }
     *     ....
     * }
     */
    addListenerChangeWorkerName() {
        let workerNameSelect = $(`#${this.workerNameSelectId}`);

        workerNameSelect.on('select2:select', (e) => {
            let value = e.params.data.id;
            //console.log(value);
            /**
             * If nothing is selected or user reset the filter
             * we load all services into appropriate select
             */
            if (!value) {
                this.requester.get(
                    this.apiUrlGetServicesAll,
                    this.successCallbackGetServices.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
            /**
             * If user select some worker name
             * we load into services selector only services
             * which the selected worker can provide
             */
            else {
                this.requester.get(
                    `${this.apiUrlGetServices}
                        worker_id=${value.trim()}`,
                    this.successCallbackGetServices.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
        })
    }

    successCallbackGetServices(response) {
        let servicesSelect = $(`#${this.serviceNameSelectId}`);
        this._populateSelectOptions(servicesSelect, response.data)
    }


    /**
     * Populate service
     */
    getServices() {
        this.requester.get(
            this.apiUrlGetServicesAll,
            this.successCallbackGetAllServices.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    /**
     *
     * @param response = {
     *     success: true,
     *     data: {
     *         id:
     *         name:
     *      }
     */
    successCallbackGetAllServices(response) {
        let servicesSelect = $(`#${this.serviceNameSelectId}`);
        this._populateSelectOptions(servicesSelect, response.data)
    }

    /**
     * Populate workers
     */
    getWorkers() {
        this.requester.get(
            this.apiUrlGetWorkersAll,
            this.successCallbackGetAllWorkers.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }
    /**
     *
     * @param response = {
     *     success: true,
     *     data: {
     *         id:
     *         name:
     *      }
     */
    successCallbackGetAllWorkers(response) {
        let workersSelect = $(`#${this.workerNameSelectId}`);
        this._populateSelectOptions(workersSelect, response.data)
    }


    /**
     * Populate affiliates
     */
    getAffiliates() {
        this.requester.get(
            this.apiUrlGetAffiliatesAll,
            this.successCallbackGetAllAffiliates.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }
    /**
     *
     * @param response = {
     *     success: true,
     *     data: {
     *         id:
     *         name:
     *      }
     */
    successCallbackGetAllAffiliates(response) {
        let affiliatesSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliatesSelect, response.data)
    }

    /**
     * --------------------------------------Schedule---------------------------------------
     */
}
export default SearchScheduleForm;