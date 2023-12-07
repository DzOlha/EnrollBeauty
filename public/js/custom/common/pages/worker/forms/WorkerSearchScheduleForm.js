
class WorkerSearchScheduleForm extends SearchScheduleForm {
    constructor(
        requester, scheduleRenderer,
        optionBuilder, dateRenderer, apiUrl
    ) {
        super(
            requester, scheduleRenderer,
            optionBuilder, dateRenderer, apiUrl
        );
        this.apiUrlGetAll = '/api/worker/getServicesAffiliates?';

        this.onlyOrderedCheckboxId = 'only-ordered-checkbox';
        this.onlyFreeCheckboxId = 'only-free-checkbox';
    }
    collectDataToSend(idAssoc = false) {
        let dataToSend = {
            'service_id': '',
            'affiliate_id': '',
            'start_date': '',
            'end_date': '',
            'start_time': '',
            'end_time': '',
            'price_bottom': '',
            'price_top': '',
            'only_ordered': '',
            'only_free': ''
        }

        dataToSend.service_id =
            document.getElementById(this.serviceNameSelectId).value
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

        /**
         * Validate checkboxes
         */
        let validCheckboxes = this.validateCheckboxes();
        if(validCheckboxes) {
            dataToSend.only_ordered = validCheckboxes.only_ordered;
            dataToSend.only_free = validCheckboxes.only_free;
        }

        if (dateRange && timeRange && priceRange) {
            return dataToSend;
        }
        return false;
    }
    validateCheckboxes() {
        let ordered = document.getElementById(this.onlyOrderedCheckboxId);
        let free = document.getElementById(this.onlyFreeCheckboxId);

        let orderedValue = ordered.checked;
        let freeValue = free.checked;

        return {
            'only_ordered': orderedValue,
            'only_free': freeValue
        }
    }
    getServicesAffiliatesForTheWorker() {
        this.requester.get(
            this.apiUrlGetAll,
            this.successCallbackGetAll.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }
    successCallbackGetAll(response) {
        let servicesSelect = $(`#${this.serviceNameSelectId}`);
        this._populateSelectOptions(servicesSelect, response.data.services)

        let affiliatesSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliatesSelect, response.data.affiliates)
    }

    /**
     * SET functions
     */
    _setServiceSelectValue(serviceId) {
        $(`#${this.serviceNameSelectId}`).val(serviceId).trigger('change');
    }
    _convertDateFormat(inputDate) {
        const [year, month, day] = inputDate.split('-');
        const outputDate = `${day}/${month}/${year}`;
        return outputDate;
    }

    /**
     * date in yyyy-mm-dd format
     * @param date
     * @private
     */
    _setDateRangeByDate(date) {
        const datepicker = $(`#${this.dateRangeId}`);
        const formattedDate = this._convertDateFormat(date);
        datepicker.data('daterangepicker').setStartDate(formattedDate);
        datepicker.data('daterangepicker').setEndDate(formattedDate);
    }

    regenerateTheScheduleByServiceIdAndDay(serviceId, day) {
        this._setServiceSelectValue(serviceId);
        this._setDateRangeByDate(day);
        this.handleFormSubmission();
    }
}