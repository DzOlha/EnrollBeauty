
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
        this.startTimeInputId = 'start-time-input';
        this.endTimeInputId = 'end-time-input';

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
            dropdownParent:  modalBody,
            dateFormat: 'dd/mm/yy',
            minDate: new Date()
        });
    }
    _initTimepickers() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        const startTimeInput = $(`#${this.startTimeInputId}`);
        const endTimeInput = $(`#${this.endTimeInputId}`);

        startTimeInput.timepicker({
            dropdownParent:  modalBody,
            maxTime: '20:59',
            minTime:'09:00',
        });

        endTimeInput.timepicker({
            dropdownParent:  modalBody,
            maxTime: '20:59',
            minTime:'09:00',
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
            dropdownParent:  modalBody,
            placeholder: "Choose one service",
        });
    }
    _initAffiliateSelect2(modalBody) {
        $(`#${this.affiliateSelectId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Choose one affiliate",
        });
    }
    _initTimeSelect2(modalBody) {
        $(`#${this.startTimeHourId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Hour",
        });
        $(`#${this.startTimeMinuteId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Minute",
        });
        $(`#${this.endTimeHourId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Hour",
        });
        $(`#${this.endTimeMinuteId}`).select2({
            dropdownParent:  modalBody,
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

    _checkSelectAndSetErrorBorder(value, wrapperClass, errorId, errorMessage) {
        let wrapper = $(`.${wrapperClass} .${this.select2ContainerClass}`);
        let errorBlock = $(`#${errorId}`);
        if(!value) {
            wrapper.addClass('border-error');
            errorBlock.html(errorMessage);
            return false;
        } else {
            wrapper.removeClass('border-error');
            errorBlock.html('');
        }
        return true;
    }

    validateServiceAffiliateSelects() {
        let serviceId = this.validateSelect2(
            this.serviceSelectId,
            "Service is required field!",
            'service_id'
        );
        let affiliateId = this.validateSelect2(
            this.affiliateSelectId,
            "Affiliate is required field!",
            'affiliate_id'
        );

        if(serviceId && affiliateId) {
            return {
                ...serviceId, ...affiliateId
            }
        }
        return false;
    }
    validateSelect2(id, message, key) {
        let select = document.getElementById(id);
        /**
         * Validate select
         */
        let value = select.value.trim();
        let valid = this._checkSelectAndSetErrorBorder(
            value, this.serviceParentClass,
            `${id}-error`, message
        );
        if(valid) {
            return {
                key: value,
            }
        } else {
            return false;
        }
    }

    validateDay() {

    }
}