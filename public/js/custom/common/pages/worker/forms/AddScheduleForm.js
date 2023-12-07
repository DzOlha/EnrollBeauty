class AddScheduleForm extends Form {
    constructor(requester, modalForm, optionBuilder, pricingTable) {
        super(
            '',
            '',
            '/api/worker/addSchedule',
            requester
        );
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.pricingTable = pricingTable;
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


        this.select2ContainerClass = 'select2-container';

        this.serviceParentClass = 'service-selector-parent';
        this.affiliateParentClass = 'affiliate-selector-parent';

        this.modalBodyClass = 'modal-body';

        this.apiGetServicesAffiliates = '/api/worker/getServicesAffiliates?';
    }

    _initDatepicker() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        $(`#${this.dayInputId}`).pDatePicker({
            container: modalBody,
            dateFormat: 'dd/mm/yy'
        });
    }

    _initTimepickers() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        const startTimeInput = $(`#${this.startTimeInputId}`);
        const endTimeInput = $(`#${this.endTimeInputId}`);

        startTimeInput.timepicker({
            dropdownParent: modalBody,
            maxTime: '20:59',
            minTime: '09:00',
        });

        endTimeInput.timepicker({
            dropdownParent: modalBody,
            maxTime: '20:59',
            minTime: '09:00',
        });

        // Attach a change event handler to the start time input
        startTimeInput.on('change', function () {
            // Get the selected times
            const startTime = startTimeInput.timepicker('getTime');
            const endTime = endTimeInput.timepicker('getTime');

            // Compare the times
            if (startTime && endTime && startTime.getTime() > endTime.getTime()) {
                $(`#${this.startTimeInputId}-error`).html("The start time can not be greater than end time!")
                // If start time is greater than end time, set end time to start time
                //endTimeInput.timepicker('setTime', startTime);
            }
        });

        // Attach a change event handler to the end time input
        endTimeInput.on('change', function () {
            // Get the selected times
            const startTime = startTimeInput.timepicker('getTime');
            const endTime = endTimeInput.timepicker('getTime');

            // Compare the times
            if (startTime && endTime && endTime.getTime() < startTime.getTime()) {
                $(`#${this.endTimeInputId}-error`).html("The end time can not be less than start time!")
                // If end time is less than start time, set start time to end time
                //startTimeInput.timepicker('setTime', endTime);
            }
        });
    }

    _initTimescales() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        const startTimeInput = $(`#${this.startTimeInputId}`);
        const endTimeInput = $(`#${this.endTimeInputId}`);

        startTimeInput.timescale({
            minute_gap: 15,
            value_gap: 2,
            long_scale_height: 32,
            short_scale_height: 24,
            offset: 4
        });

        endTimeInput.timescale({
            minute_gap: 15,
            value_gap: 2,
            long_scale_height: 32,
            short_scale_height: 24,
            offset: 4
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
            placeholder: "Hour",
        });
        $(`#${this.startTimeMinuteId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Minute",
        });
        $(`#${this.endTimeHourId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Hour",
        });
        $(`#${this.endTimeMinuteId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Minute",
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
        this.getServicesAffiliates();
        this.modalForm.close();
        this._initDatepicker();
        this.addListenerSubmitForm();
    }

    getServicesAffiliates() {
        this.requester.get(
            this.apiGetServicesAffiliates,
            this.successCallbackGetServicesAffiliates.bind(this),
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }

    successCallbackGetServicesAffiliates(response) {
        let serviceSelect = $(`#${this.serviceSelectId}`);
        this._populateSelectOptions(serviceSelect, response.data.services);

        let affiliateSelect = $(`#${this.affiliateSelectId}`);
        this._populateSelectOptions(affiliateSelect, response.data.affiliates);

        this._initSelect2();
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
        console.log(wrappers);
        console.log(wrapper);
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
            "Start Hour is required field!",
            'start_hour',
            this.startTimeHourErrorId,
            0
        );
        let startMinute = this.validateSelect2(
            this.startTimeMinuteId,
            this.startTimeParentClass,
            "Start Minute is required field!",
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
            "End Hour is required field!",
            'end_hour',
            this.endTimeHourErrorId,
            0
        );
        let endMinute = this.validateSelect2(
            this.endTimeMinuteId,
            this.endTimeParentClass,
            "End Minute is required field!",
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
        console.log(value);
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
            result.error = "The day can not be from the past!";
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
        if (startDateTime > endDateTime) {
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
        console.log(data);

        if (data) {
            e.currentTarget.insertAdjacentHTML(
                'beforebegin', OptionBuilder.createGifLoader()
            );
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    $(`#gif-loader`).remove();
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }

    successCallbackSubmit(response) {
        $(`#gif-loader`).remove();
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
        // this.sche.sendApiRequest(
        //     this.pricingTable.getItemsPerPage(),
        //     Cookie.get('currentPage')
        // );
    }

}