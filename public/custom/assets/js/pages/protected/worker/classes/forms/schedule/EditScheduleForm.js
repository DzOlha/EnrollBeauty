import AddScheduleForm from "./AddScheduleForm.js";
import Notifier from "../../../../../../classes/notifier/Notifier.js";
import DatePicker from "../../../../../../classes/element/DatePicker.js";
import GifLoader from "../../../../../../classes/loader/GifLoader.js";
import Select2 from "../../../../../../classes/element/Select2.js";


class EditScheduleForm extends AddScheduleForm
{
    constructor(
        requester, submitUrl, apiGetServicesForWorker,
        apiGetAffiliates, apiGetFilledTimeIntervals,
        modalForm, optionBuilder, searchForm,

        getScheduleOne,
        scheduleBuilder, dateRenderer, timeRenderer
    ) {
        super(
            requester, submitUrl, apiGetServicesForWorker,
            apiGetAffiliates, apiGetFilledTimeIntervals,
            modalForm, optionBuilder, searchForm
        );
        this.scheduleBuilder = scheduleBuilder;
        this.dateRenderer = dateRenderer;
        this.timeRenderer = timeRenderer;

        this.cardBaseId = 'schedule-card';
        this.editIconClass = 'fe-edit-3';

        this.dataIdAttribute = 'data-schedule-id';
        this.apiGetSchedule = getScheduleOne;
    }
    getEditIcon(id) {
        return document.querySelector(
            `#${this.cardBaseId}-${id} .${this.editIconClass}`
        );
    }
    setDeleteCallback(callback, context) {
        this.deleteCallback = callback.bind(context);
    }
    addListenerEdit(id) {
        let btn = this.getEditIcon(id);
        btn.addEventListener('click', this.handleShowEditForm)
    }
    handleShowEditForm = (e) =>
    {
        /**
         * Get the id of the schedule we want to edit
         */
        this.scheduleId = e.currentTarget.getAttribute(this.dataIdAttribute);

        this.modalForm.setSelectors('modalEditSchedule');
        this.submitButtonId = this.modalForm.modalSubmitId;

        this.modalForm.show(
            'Edit Schedule Item',
            this.modalForm.formBuilder.createAddScheduleForm(),
            'Edit'
        );

        /**
         * Save the schedule id into data attribute of the submit button
         * @type {HTMLElement}
         */
        let submit = document.getElementById(this.submitButtonId);
        submit.setAttribute(this.dataIdAttribute, this.scheduleId);

        this._initForm(this.scheduleId);
        this.modalForm.close();
        this.addListenerSubmitForm();
    }

    _initForm(id) {
        this._initSelect2();
        this.getServices();
        this.getAffiliates();
        this.addListenerChangeDay();
        this._initDatepicker();

        /**
         * Get data
         */
        this.getObjectDetails(id);
    }
    getFilledTimeIntervals(day) {
        this.requester.get(
            `${this.apiGetFilledTimeIntervals}?day=${day}&schedule_id=${this.scheduleId}`,
            this.successCallbackGetFilledTimeIntervals.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    getObjectDetails(id) {
        this.requester.get(
            `${this.apiGetSchedule}?schedule_id=${id}`,
            this.successGetScheduleCallback.bind(this),
            this.errorGetScheduleCallback.bind(this)
        );
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *          schedule_id:
     *          order_id: nullable
     *          service_id:
     *          department_id:
     *          service_name:
     *          user_id: nullable
     *          user_email: nullable
     *          affiliate_id:
     *          city:
     *          address:
     *          day: yyyy-mm-dd
     *          start_time:
     *          end_time:
     *          price:
     *          currency:
     *     }
     * }
     * @protected
     */
    successGetScheduleCallback(response) {
        /**
         * Populate the form with the current data of the schedule
         */

        /**
         * Set service id
         */
        this._setSelect2(
            this.serviceSelectId,
            response.data.service_id,
            "Choose one service"
        );

        /**
         * Set affiliate id
         */
        this._setSelect2(
            this.affiliateSelectId,
            response.data.affiliate_id,
            "Choose one affiliate"
        );

        /**
         * Set day
         *
         * yyyy-mm-dd
         */
        let dayPicker = new DatePicker(this.dayInputId);
        dayPicker.set(response.data.day);

        // const datePickerInstance = $(`#${this.dayInputId}`).data("plugin_pDatePicker");
        // const selectedDate = new Date(response.data.day);
        // datePickerInstance.setSelectedDate(selectedDate, true);

        //$(`#${this.dayInputId}`).val(response.data.day).trigger('pDatePickerChange');

        /**
         * Set start time
         */
        // [hh, mm, ss]
        let startTimeArr = response.data.start_time.split(':');


        this._setSelect2(this.startTimeHourId, startTimeArr[0], 'Hours');
        this._setSelect2(this.startTimeMinuteId, startTimeArr[1], 'Minutes');

        /**
         * Set end time
         */
            // [hh, mm, ss]
        let endTimeArr = response.data.end_time.split(':');

        this._setSelect2(this.endTimeHourId, endTimeArr[0], 'Hours');
        this._setSelect2(this.endTimeMinuteId, endTimeArr[1], 'Minutes');
    }
    errorGetScheduleCallback(response) {
        GifLoader.hide(this.requestTimeout);
        Notifier.showErrorMessage(response.error);
    }

    _setSelect2(selectId, value, placeholder) {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        Select2._setSelect2(selectId, value, placeholder, modalBody);
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     service_id:
         *     affiliate_id:
         *     day: yyyy-mm-dd
         *     start_time: hh:ii:ss
         *     end_time: hh:ii:ss
         * }
         */
        let data = this.validateFormData();

        /**
         * Add the schedule id to the data sent to the server
         */
        data.id = e.currentTarget.getAttribute(this.dataIdAttribute);

        if (data) {
            this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.put(
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
         * Regenerate the schedule item card
         */
        this._regenerateScheduleCard(response.data)
    }

    /**
     * @param data = {
     *          schedule_id:
     *          order_id: nullable
     *          service_id:
     *          department_id:
     *          service_name:
     *          user_id: nullable
     *          user_email: nullable
     *          affiliate_id:
     *          city:
     *          address:
     *          day:
     *          start_time:
     *          end_time:
     *          price:
     *          currency:
     *     }
     * }
     * @protected
     */
    _regenerateScheduleCard(data) {
        let oldCard = document.getElementById(
            `${this.cardBaseId}-${data.schedule_id}`
        );

        let newCard = this.scheduleBuilder.createScheduleCard(
            data.schedule_id, data.user_id, data.service_id,
            data.affiliate_id, data.service_name,
            data.price,data.currency,
            data.user_email,
            this.dateRenderer.render(data.day),
            this.timeRenderer.renderShortTime(data.start_time),
            this.timeRenderer.renderShortTime(data.end_time),
            `c. ${data.city}, ${data.address}`,
            data.order_id
        );
        let div = document.createElement('div');
        div.insertAdjacentHTML('afterbegin', newCard);

        oldCard.replaceWith(div.firstChild);

        this.addListenerEdit(data.schedule_id);
        this.deleteCallback(data.schedule_id);
    }
}
export default EditScheduleForm;