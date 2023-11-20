class SearchScheduleForm extends Form {
    constructor(scheduleRenderer) {
        super(
            '',
            'submit-search-button',
            '/api/user/searchSchedule',
            new Requestor()
        );
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

        this.apiUrlGetWorkers = '/api/user/getWorkersForService?';
        this.apiUrlGetServices = '/api/user/getServicesForWorker?';
        this.apiUrlGetAll = '/api/user/getServicesWorkersAffiliates?';

        this.apiUrlGetWorkersAll = '/api/user/getWorkersAll';
        this.apiUrlGetServicesAll = '/api/user/getServicesAll';

        this.submitActionUrl = '/api/user/searchSchedule';

        this.renderer = scheduleRenderer;
    }

    _initializeDateRangePicker() {
        const currentDate = new Date();

        $(`#${this.dateRangeId}`).daterangepicker({
            locale: {
                format: 'DD/MM/YYYY', // Date format
            },
            opens: 'right',
            showDropdowns: false,
            startDate: currentDate,
            endDate: currentDate,
            minDate: currentDate,  // Minimum selectable date (current date)
        });
    }

    listenerSubmitForm = () => {
        let dataToSend = this.collectDataToSend();

        console.log(JSON.stringify(dataToSend));
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
                (item) => DateRenderer.getUnixTimestamp(item)
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
            console.log(value);
            /**
             * If nothing is selected ot user reset the filter
             * we load all workers into appropriate select
             */
            if (!value) {
                this.requestor.get(
                    this.apiUrlGetWorkersAll,
                    this.successCallbackGetWorkers.bind(this),
                    this.errorCallbackSubmit.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
            /**
             * If user select some service name
             * we load into workers selector only workers
             * who can provide selected service
             */
            else {
                this.requestor.get(
                    `${this.apiUrlGetWorkers}service_id=${value}`,
                    this.successCallbackGetWorkers.bind(this),
                    this.errorCallbackSubmit.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
        })
    }

    _populateSelectOptions(parent, data) {
        parent.html('');
        parent.append(OptionBuilder.createOptionLabel());

        data.forEach((item) => {
            parent.append(OptionBuilder.createOption(
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
            console.log(value);
            /**
             * If nothing is selected ot user reset the filter
             * we load all services into appropriate select
             */
            if (!value) {
                this.requestor.get(
                    this.apiUrlGetServicesAll,
                    this.successCallbackGetServices.bind(this),
                    this.errorCallbackSubmit.bind(this),
                    this.errorCallbackSubmit.bind(this)
                )
            }
            /**
             * If user select some worker name
             * we load into services selector only services
             * which the selected worker can provide
             */
            else {
                this.requestor.get(
                    `${this.apiUrlGetServices}
                        worker_id=${value.trim()}`,
                    this.successCallbackGetServices.bind(this),
                    this.errorCallbackSubmit.bind(this),
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
     * Populate service, workers, affiliates selectors
     */
    getAllSelectInfo() {
        this.requestor.get(
            this.apiUrlGetAll,
            this.successCallbackGetAll.bind(this),
            this.errorCallbackSubmit.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    /**
     *
     * @param response = {
     *     success: true,
     *     data: {
     *         services: {
     *            {
     *               id:
     *               name
     *            }
     *          ....
     *         }
     *         workers:
     *         affiliates
     *     }
     * }
     */
    successCallbackGetAll(response) {
        let servicesSelect = $(`#${this.serviceNameSelectId}`);
        this._populateSelectOptions(servicesSelect, response.data.services)

        let workersSelect = $(`#${this.workerNameSelectId}`);
        this._populateSelectOptions(workersSelect, response.data.workers)

        let affiliatesSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliatesSelect, response.data.affiliates)
    }

    /**
     * --------------------------------------Schedule---------------------------------------
     */
}