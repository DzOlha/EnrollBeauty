import Form from "../../user/forms/Form.js";
import Notifier from "../../classes/notifier/Notifier.js";
import GifLoader from "../../classes/loader/GifLoader.js";
import API from "../../../../common/pages/api.js";
class AddScheduleForm extends Form {
    constructor(requester, modalForm, optionBuilder, searchForm) {
        super(
            '',
            '',
            API.WORKER.API.SCHEDULE.add,
            requester
        );
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.searchForm = searchForm;
        this.addScheduleTriggerId = 'add-schedule-trigger';

        this.serviceSelectId = 'service-select';
        this.affiliateSelectId = 'affiliate-select';
        this.dayInputId = 'day-input';
        this.dayDatePickerInputClass = 'date-picker-input';

        this.startTimeHourErrorId = 'start-time-hour-input-error';
        this.startTimeMinuteErrorId = 'start-time-minute-input-error';
        this.endTimeHourErrorId = 'end-time-hour-input-error';
        this.endTimeMinuteErrorId = 'end-time-minute-input-error';

        this.startTimeParentClass = 'start-time-selector-parent';
        this.endTimeParentClass = 'end-time-selector-parent';

        this.startTimeHourId = 'start-time-hour';
        this.startTimeMinuteId = 'start-time-minute';
        this.endTimeHourId = 'end-time-hour';
        this.endTimeMinuteId = 'end-time-minute';

        this.disabledEndHours = {};
        this.disabledStartHours = {};

        this.select2ContainerClass = 'select2-container';

        this.serviceParentClass = 'service-selector-parent';
        this.affiliateParentClass = 'affiliate-selector-parent';

        this.modalBodyClass = 'modal-body';

        this.apiGetServicesForWorker = API.WORKER.API.PROFILE.service.get.all;
        this.apiGetAffiliates = API.WORKER.API.AFFILIATE.get.all;
        this.apiGetFilledTimeIntervals = API.WORKER.API.SCHEDULE.get["busy-time-intervals"];
    }

    _initDatepicker() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        $(`#${this.dayInputId}`).pDatePicker({
            container: modalBody,
            dateFormat: 'dd/mm/yy'
        });
    }

    _initSelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        this._initServiceSelect2(modalBody);
        this._initAffiliateSelect2(modalBody);

        this._initTimeSelect2(modalBody);
    }

    _initServiceSelect2(modalBody) {
        $(`#${this.serviceSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one service",
        });
    }

    _initAffiliateSelect2(modalBody) {
        $(`#${this.affiliateSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one affiliate",
        });
    }

    _initTimeSelect2(modalBody) {
        $(`#${this.startTimeHourId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Hours",
        });
        $(`#${this.startTimeMinuteId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Minutes",
        });
        $(`#${this.endTimeHourId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Hours",
        });
        $(`#${this.endTimeMinuteId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Minutes",
        });
    }

    addListenerShowAddScheduleForm() {
        let trigger = document.getElementById(this.addScheduleTriggerId);
        trigger.addEventListener('click', this.handleShowAddScheduleForm);
    }

    handleShowAddScheduleForm = () => {
        this.modalForm.setSelectors('modalAddSchedule');
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Add New Schedule Item',
            this.modalForm.formBuilder.createAddScheduleForm(),
            'Add'
        );

        this._initSelect2();
        this.getServices();
        this.getAffiliates();
        this.addListenerChangeDay();
        this.modalForm.close();
        this._initDatepicker();
        this.addListenerSubmitForm();
    }

    getServices() {
        this.requester.get(
            this.apiGetServicesForWorker,
            this.successCallbackGetServices.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    successCallbackGetServices(response) {
        let serviceSelect = $(`#${this.serviceSelectId}`);
        this._populateSelectOptions(serviceSelect, response.data);

        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        this._initServiceSelect2(modalBody);
    }

    getAffiliates() {
        this.requester.get(
            this.apiGetAffiliates,
            this.successCallbackGetAffiliates.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    successCallbackGetAffiliates(response) {
        let affiliateSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliateSelect, response.data);

        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        this._initAffiliateSelect2(modalBody);
    }

    addListenerChangeDay() {
        let _this = this;
        // Assuming `dayInputId` is the ID of the input associated with the date picker
        $(`#${this.dayInputId}`).on('pDatePickerChange', function () {
            // Use $(this).val() to get the value of the date picker input
            _this._resetSelect2();
            _this.getFilledTimeIntervals($(this).val());
            // console.log('I changed');
        });
    }

    getFilledTimeIntervals(day) {
        this.requester.get(
            `${this.apiGetFilledTimeIntervals}?day=${day}`,
            this.successCallbackGetFilledTimeIntervals.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }

    _enableAllOptions(selectId) {
        console.log('_enableAllOptions - ' + selectId);
        $(`#${selectId} option`).prop('disabled', false);
        //$(`#${selectId}`).trigger('change');
    }
    _disableHours(selectHourId, startHour, startMinute, endHour, endMinute) {
        console.log('_disableHours ' + selectHourId);

        $(`#${selectHourId} option`).each(function() {

            let currentHour = $(this).val();
            /**
             * Disable hours current option if the condition is met
             *
             * New schedule item can not start or end withing hours of another schedule item
             */
            if(currentHour === endHour && endMinute === '45'
                || (currentHour === startHour && startMinute === '00' && startHour !== endHour)
                || currentHour > startHour && currentHour < endHour)
            {
                $(this).prop('disabled', true);
            }
        })
        //$(`#${selectHourId}`).trigger('change');
    }
    _disableMinutes(busyTimeIntervals, clickedHour, minuteSelectId, hourSelectType) {
        console.log('_disableMinutes ' + minuteSelectId);

        /**
         * Reset value of minute if we change hour
         */
        $(`#${minuteSelectId}`).val('');

        for (const key in busyTimeIntervals) {
            let interval = busyTimeIntervals[key];

            const start = interval.start_time.split(':');
            const end = interval.end_time.split(':');

            let startHour = start[0], endHour = end[0],
                startMinute = start[1], endMinute = end[1];

            if(clickedHour === startHour) {
                $(`#${minuteSelectId} option`).each(function() {
                    /**
                     * New schedule item can not start withing the time period of another schedule item
                     */
                    let val = $(this).val();
                    if (val >= startMinute) {
                        /**
                         * if start_time = 14:00:00
                         * but end_time = 14:30:00
                         *
                         * disable only 00, 15, 30 (without 45, because we work within one hour)
                         */
                        if(endHour === startHour) {
                            if(val <= endMinute) {
                                $(this).prop('disabled', true);
                            }
                        } else {
                            $(this).prop('disabled', true);
                        }
                    }
                })
            }
            if(clickedHour === endHour) {
                $(`#${minuteSelectId} option`).each(function() {

                        /**
                         * New schedule item can start at the time the other schedule item ends
                         */
                        if(hourSelectType === 'start') {
                            let val = $(this).val();
                            if (val < endMinute) {
                                /**
                                 * if start_time = 17:15:00
                                 *    end_time = 17:45:00
                                 *
                                 * we disable only: 15, 30, 45 (without 00, because we work within one hour)
                                 */
                                if(endHour === startHour) {
                                    if(val >= startMinute) {
                                        $(this).prop('disabled', true);
                                    }
                                } else {
                                    $(this).prop('disabled', true);
                                }
                            }
                        } else {
                            /**
                             * New schedule item can not end at the same time with another schedule items
                             */

                            let val = $(this).val();
                            if (val <= endMinute) {
                                /**
                                 * if start_time = 17:15:00
                                 *    end_time = 17:45:00
                                 *
                                 * we disable only: 15, 30, 45 (without 00, because we work within one hour)
                                 */
                                if(endHour === startHour) {
                                    if(val >= startMinute) {
                                        $(this).prop('disabled', true);
                                    }
                                } else {
                                    $(this).prop('disabled', true);
                                }
                            }
                        }
                    })
            }
        }
        //$(`#${minuteSelectId}`).trigger('change');
    }
    _initialHourDisabling = (busyTimeIntervals) => {
        console.log('_initialHourDisabling');
        /**
         * Reset all disabled options
         */
        this._enableAllOptions(this.startTimeHourId);
        this._enableAllOptions(this.endTimeHourId);

        // Loop through each busy time interval and disable conflicting options
        for (const key in busyTimeIntervals) {
            let interval = busyTimeIntervals[key];
            //console.log(interval);
            /**
             * @type {string[]}
             * Example:
             * 14:30:00 -> [14, 30, 00]
             */
            const start = interval.start_time.split(':');
            /**
             * @type {string[]}
             * Example:
             * 17:45:00 -> [17, 45, 00]
             */
            const end = interval.end_time.split(':');

            this._disableHours(this.startTimeHourId, start[0], start[1], end[0], end[1]);
            this._disableHours(this.endTimeHourId, start[0], start[1], end[0], end[1]);
        }
    }

    _disableEndHours(busyTimeIntervals, startHourSelected, endHourSelectId) {
        console.log('disableEndHours ' + startHourSelected);

        this._enabledOptionFromList(this.disabledEndHours, endHourSelectId);
        console.log(this.disabledEndHours);
        let _this = this;

        for (const key in busyTimeIntervals) {
            let interval = busyTimeIntervals[key];
            //console.log(interval);
            const start = interval.start_time.split(':');
            const end = interval.end_time.split(':');

            let startHour = start[0], endHour = end[0];

            /**
             * Example: start_time: 14:30:00,
             *          end_time: 17:45:00
             * If we select start hour as 14:15, so
             * we should disable the opportunity to select end time >= 17:45
             */
            if(startHourSelected <= startHour) {
                /**
                 * Reset value of end hour if we change start hour
                 */
                $(`#${endHourSelectId}`).val('');

                $(`#${endHourSelectId} option`).each(function(index) {
                    console.log(index);
                    let value = $(this).val();
                    if(value >= endHour) {
                        $(this).prop('disabled', true);
                        _this.disabledEndHours[index] = value;
                    }
                })
            }
            // else if(startHourSelected >= endHour) {
            //     $(`#${endHourSelectId} option`).each(function() {
            //         if($(this).val() <= endHour) {
            //             $(this).prop('disabled', true);
            //         }
            //     })
            // }
        }
    }

    _enabledOptionFromList(list, selectId) {
        $(`${selectId} option`).each(function(index) {
            if(typeof list.index !== 'undefined') {
                $(this).prop('disabled', false);
            }
        })
        list = {};
    }

    _disableStartHours(busyTimeIntervals, endHourSelected, startHourSelectId) {
        console.log('disableStartHours ' + endHourSelected);

        this._enabledOptionFromList(this.disabledStartHours, startHourSelectId);
        console.log(this.disabledStartHours);

        let _this = this;

        for (const key in busyTimeIntervals) {
            let interval = busyTimeIntervals[key];
            //console.log(interval);
            const start = interval.start_time.split(':');
            const end = interval.end_time.split(':');

            let startHour = start[0], endHour = end[0];

            /**
             * Example: start_time: 14:30:00,
             *          end_time: 17:45:00
             * If we select end hour as 18:00, so
             * we should disable the opportunity to select start time <= 14:30
             */
            if(endHourSelected <= startHour) {
                /**
                 * Reset value of start hour if we change end hour
                 */
                $(`#${startHourSelectId}`).val('');
                $(`#${startHourSelectId} option`).each(function(index) {
                    let value = $(this).val();
                    if(value <= startHour) {
                        $(this).prop('disabled', true);
                        _this.disabledStartHours[index] = value;
                    }
                })
            }
            // else if(endHourSelected >= endHour) {
            //     $(`#${startHourSelectId} option`).each(function() {
            //         if($(this).val() >= endHour) {
            //             $(this).prop('disabled', true);
            //         }
            //     })
            // }
        }
    }

    _resetSelect2() {
        $(`#${this.startTimeHourId}`).val('').trigger('change');
        $(`#${this.startTimeMinuteId}`).val('').trigger('change');
        $(`#${this.endTimeHourId}`).val('').trigger('change');
        $(`#${this.endTimeMinuteId}`).val('').trigger('change');
    }

    successCallbackGetFilledTimeIntervals(response)
    {
        console.log('successCallbackGetFilledTimeIntervals');
        if(response.data.length === 0) {
            /**
             * Reset all disabled options
             */
            console.log('All time slots are free!');
            this._enableAllOptions(this.startTimeHourId);
            this._enableAllOptions(this.startTimeMinuteId);
            this._enableAllOptions(this.endTimeHourId);
            this._enableAllOptions(this.endTimeMinuteId);
        } else {
            this._initialHourDisabling(response.data);
        }
        let _this = this;

        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        // Attach change event listeners to start and end time Select2 elements
        $(`#${this.startTimeHourId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Hours",
        })
            .on('select2:select', function() {
                //_this._disableEndHours(response.data, $(this).val(), _this.endTimeHourId);
                /**
                 * Reset all disabled options
                 */
                _this._enableAllOptions(_this.startTimeMinuteId);
                _this._disableMinutes(
                    response.data, $(this).val(), _this.startTimeMinuteId, 'start'
                );
                $(`#${_this.startTimeMinuteId}`).select2({
                    dropdownParent: modalBody,
                    placeholder: "Minutes",
                })
                // $(`#${_this.endTimeHourId}`).select2({
                //     dropdownParent: modalBody,
                //     placeholder: "Hours",
                // })
                //$('select').select2();
            });

        $(`#${this.endTimeHourId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Hours",
        })
            .on('select2:select', function()  {
                //_this._disableStartHours(response.data, $(this).val(), _this.startTimeHourId);
                /**
                 * Reset all disabled options
                 */
                _this._enableAllOptions(_this.endTimeMinuteId);
                _this._disableMinutes(
                    response.data, $(this).val(), _this.endTimeMinuteId, 'end'
                );
                $(`#${_this.endTimeMinuteId}`).select2({
                    dropdownParent: modalBody,
                    placeholder: "Minutes",
                })
                // $(`#${_this.startTimeHourId}`).select2({
                //     dropdownParent: modalBody,
                //     placeholder: "Hours",
                // })
            });
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

    _checkSelectAndSetErrorBorder(value, wrapperClass, errorId, errorMessage, index = null) {
        let wrappers = document.querySelectorAll(
            `.${wrapperClass} .${this.select2ContainerClass}`
        );
        let wrapper = null;
        if (wrappers.length > 1) {
            wrapper = wrappers[index];
        } else {
            wrapper = wrappers[0];
        }
        let errorBlock = $(`#${errorId}`);
        if (!value) {
            wrapper.classList.add('border-error');
            errorBlock.html(errorMessage);
            return false;
        } else {
            wrapper.classList.remove('border-error');
            errorBlock.html('');
        }
        return true;
    }

    validateServiceAffiliateSelects() {
        let serviceId = this.validateSelect2(
            this.serviceSelectId,
            this.serviceParentClass,
            "Service is required field!",
            'service_id'
        );
        let affiliateId = this.validateSelect2(
            this.affiliateSelectId,
            this.affiliateParentClass,
            "Affiliate is required field!",
            'affiliate_id'
        );
        //let myId = this.select2.validate('my_id');

        if (serviceId && affiliateId) {
            return {
                ...serviceId, ...affiliateId
            }
        }
        return false;
    }

    validateSelect2(selectorId, wrapperClass, message, key, errorSelectorId = null, index = null) {
        let select = document.getElementById(selectorId);
        /**
         * Validate select
         */
        let value = select.value.trim();
        let errorId = errorSelectorId !== null ? errorSelectorId : `${selectorId}-error`;
        let valid = this._checkSelectAndSetErrorBorder(
            value, wrapperClass,
            errorId, message, index
        );
        if (valid) {
            let result = {};
            result[key] = value;

            return result
        } else {
            return false;
        }
    }


    validateStartTime() {
        let startHour = this.validateSelect2(
            this.startTimeHourId,
            this.startTimeParentClass,
            "Start Hours is required field!",
            'start_hour',
            this.startTimeHourErrorId,
            0
        );
        let startMinute = this.validateSelect2(
            this.startTimeMinuteId,
            this.startTimeParentClass,
            "Start Minutes is required field!",
            'start_minute',
            this.startTimeMinuteErrorId,
            1
        );

        if (startHour && startMinute) {
            //12, 30 -> 12:30:00
            let startTime = startHour['start_hour'] + ':' + startMinute['start_minute'] + ":00";
            return {
                'start_time': startTime
            }
        }
        return false;
    }

    validateEndTime() {
        let endHour = this.validateSelect2(
            this.endTimeHourId,
            this.endTimeParentClass,
            "End Hours is required field!",
            'end_hour',
            this.endTimeHourErrorId,
            0
        );
        let endMinute = this.validateSelect2(
            this.endTimeMinuteId,
            this.endTimeParentClass,
            "End Minutes is required field!",
            'end_minute',
            this.endTimeMinuteErrorId,
            1
        );

        if (endHour && endMinute) {
            //12, 30 -> 12:30:00
            let endTime = endHour['end_hour'] + ':' + endMinute['end_minute'] + ":00";
            return {
                'end_time': endTime
            }
        }
        return false;
    }

    dayValidationCallback = (value) => {
        let result = {};
        let datepickerInput = document.getElementsByClassName(this.dayDatePickerInputClass)[0];
        //console.log(value);
        if (!value) {
            result.error = "The day is required field!";
            datepickerInput.classList.add('border-danger');
            return result;
        }
        // Get the current date
        let currentDate = new Date();
        //midnight time
        currentDate.setHours(0, 0, 0, 0);

        let day = new Date(value);

        if (day < currentDate) {
            result.error = "The day can not be in the past!";
            datepickerInput.classList.add('border-danger');
            return result;
        }
        if (datepickerInput.classList.contains('border-danger')) {
            datepickerInput.classList.remove('border-danger');
        }
        return result;
    }

    validateDay() {
        // yyyy-mm-dd
        return this.validateInput(
            this.dayInputId,
            'day',
            this.dayValidationCallback
        )
    }

    validateInput(selectorId, key, validationCallback, errorSelectorId = null) {
        let input = document.getElementById(selectorId);

        let error = null;
        if (errorSelectorId !== null) {
            error = document.getElementById(`${errorSelectorId}`);
        } else {
            error = document.getElementById(`${selectorId}-error`);
        }


        let value = input.value.trim();
        let valid = true;

        let callback = validationCallback(value);
        if (callback.error) {
            valid = false;
            input.classList.add('border-danger');
            error.innerHTML = callback.error;
        }

        if (valid) {
            if (input.classList.contains('border-danger')) {
                input.classList.remove('border-danger');
            }
            error.innerHTML = '';
        }

        /**
         * Result
         */
        if (valid) {
            let result = {};
            result[key] = value;

            return result
        } else {
            return false;
        }
    }

    /**
     * Validate if the day chosen as today, the selected time
     * interval should be greater than the current time
     *
     * and check the start time to be less than end time
     */
    validateCurrentTime(day, startTime, endTime) {
        let startTimeError = document.getElementById(this.startTimeMinuteErrorId);
        let rangeTimeError = document.getElementById(this.startTimeHourErrorId);
        let endTimeError = document.getElementById(this.endTimeMinuteErrorId);

        let startTimeValid = true;
        let endTimeValid = true;
        let validTimeRange = true;

        // Get the current date and time
        let currentDate = new Date();

        // Parse the chosen day, startTime, and endTime strings into Date objects
        let chosenDate = new Date(day);
        let startDateTime = new Date(`${day}T${startTime}`);
        let endDateTime = new Date(`${day}T${endTime}`);

        // Check if the chosen day is the current day
        if (
            chosenDate.getFullYear() === currentDate.getFullYear() &&
            chosenDate.getMonth() === currentDate.getMonth() &&
            chosenDate.getDate() === currentDate.getDate()
        ) {
            if (startDateTime < currentDate) {
                startTimeValid = false;
                startTimeError.innerHTML = "Start time can not be in the past!";
            }
            if (endDateTime < currentDate) {
                endTimeValid = false;
                endTimeError.innerHTML = "End time can not be in the past!";
            }
        }
        if (startDateTime >= endDateTime) {
            validTimeRange = false;
            rangeTimeError.innerHTML = "Start time should be less than end time!";
        }

        if(startTimeValid) {
            startTimeError.innerHTML = '';
        }
        if(endTimeValid) {
            endTimeError.innerHTML = '';
        }
        if(validTimeRange) {
            rangeTimeError.innerHTML = '';
        }

        return startTimeValid && endTimeValid && validTimeRange;
    }

    validateFormData() {
        let serviceAffiliate = this.validateServiceAffiliateSelects();
        let day = this.validateDay();
        let startTime = this.validateStartTime();
        let endTime = this.validateEndTime();

        if (serviceAffiliate && day && startTime && endTime) {
            let validTime = this.validateCurrentTime(
                day['day'], startTime['start_time'], endTime['end_time']
            );
            if (validTime) {
                return {
                    ...serviceAffiliate, ...day, ...startTime, ...endTime
                }
            }
        }
        return false;
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
        //console.log(data);

        if (data) {
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
         * Regenerate the schedule of pricing to show the newly added pricing there
         */
        this.searchForm.regenerateTheScheduleByServiceIdAndDay(
            response.data.service_id, response.data.day
        );
    }

}
export default AddScheduleForm;