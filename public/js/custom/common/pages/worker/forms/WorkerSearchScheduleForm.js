
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
            'price_top': ''
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

        if (dateRange && timeRange && priceRange) {
            return dataToSend;
        }
        return false;
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
}