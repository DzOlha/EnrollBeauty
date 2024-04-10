import SearchScheduleForm from "../../../user/forms/schedule/SearchScheduleForm.js";
import API from "../../../../../common/pages/api.js";
class SearchWorkerScheduleForm extends SearchScheduleForm {
    constructor(
        requester, scheduleRenderer,
        optionBuilder, dateRenderer
    ) {
        super(
            requester, scheduleRenderer,
            optionBuilder, dateRenderer
        );
        this.apiUrlGetAllWorkerServices = API.WORKER.API.PROFILE.service.get.all;
        this.apiUrlGetAllAffiliates = API.WORKER.API.AFFILIATE.get.all;

        this.onlyOrderedCheckboxId = 'only-ordered-checkbox';
        this.onlyFreeCheckboxId = 'only-free-checkbox';

        this.submitActionUrl = API.WORKER.API.SCHEDULE.search;
    }

    init(){
        /**
         * Get information for select elements of the form of searching
         * available schedules for appointments
         */
        this.getServicesForTheWorker();
        this.getAffiliates();

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

        $(`#${this.dateRangeId}`).daterangepicker({
            locale: {
                format: 'DD/MM/YYYY', // Date format
            },
            opens: 'right',
            showDropdowns: false,
            startDate: currentDate,
            endDate: currentDate
        });
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

    getServicesForTheWorker() {
        this.requester.get(
            this.apiUrlGetAllWorkerServices,
            this.successCallbackGetServices.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }
    getAffiliates() {
        this.requester.get(
            this.apiUrlGetAllAffiliates,
            this.successCallbackGetAffiliates.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackGetServices(response) {
        let servicesSelect = $(`#${this.serviceNameSelectId}`);
        this._populateSelectOptions(servicesSelect, response.data)
    }
    successCallbackGetAffiliates(response) {
        let affiliatesSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliatesSelect, response.data)
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

    // regenerateTheScheduleByServiceIdAndDay(serviceId, day) {
    //     this._setServiceSelectValue(serviceId);
    //     let free = document.getElementById(this.onlyFreeCheckboxId);
    //     free.checked = true;
    //     this._setDateRangeByDate(day);
    //     this.handleFormSubmission();
    // }
    regenerateTheScheduleByDay(day) {
        /**
         * Display free schedule items
         * @type {HTMLElement}
         */
        let free = document.getElementById(this.onlyFreeCheckboxId);
        free.checked = true;

        /**
         * Display ordered schedule items
         * @type {HTMLElement}
         */
        let ordered = document.getElementById(this.onlyOrderedCheckboxId);
        ordered.checked = true;

        /**
         * Set the day for which to show the worker's schedule
         */
        this._setDateRangeByDate(day);

        /**
         * Submit the form of searching the worker's schedule
         */
        this.handleFormSubmission();
    }
}
export default SearchWorkerScheduleForm;