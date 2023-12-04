
class WorkerScheduleRenderer extends ScheduleRenderer {
    constructor(
        requester, appointmentsTable,
        confirmationModal, htmlBuilder,
        dateRenderer, timeRenderer
    ) {
        super(requester, appointmentsTable,
            confirmationModal, htmlBuilder,
            dateRenderer, timeRenderer);
        this.apiCancelOrder = '/api/worker/cancelServiceOrder';
        this.apiCompleteOrder = '/api/worker/completeServiceOrder';
    }
    /**
     * @param schedules
     * {
     *     0: {
     *    schedule_id: ,
     *    service_id: ,
     *    department_id: ,
     *    service_name: ,
     *    user_id: ,
     *    user_email: ,
     *    affiliate_id: ,
     *    city: ,
     *    address: ,
     *    day: ,
     *    start_time: ,
     *    end_time: ,
     *    price: ,
     *    currency:
     *    ........
     * },
     * }
     * @param activeDayTabId id_YYYY-MM-DD
     * @param activeDepartmentTabId department-tab-content-{department_id}
     */
    populateActiveDayScheduleTab(
        activeDepartmentTabId, activeDayTabId, schedules
    ) {
        // console.log(JSON.stringify(schedules));
        // console.log('populateActiveDayScheduleTab children');
        let schedulesForActiveDay = schedules.filter(
            schedule => schedule.day === activeDayTabId.substring(3)
        );

        // console.log('activeDayTabId = ' + activeDayTabId);
        //console.log(`#${activeDepartmentTabId} #${activeDayTabId} .${this.timeIntervalClass}.${this.from12To15Class}`);

        /**
         * 9 - 12
         */
        let _9_12 = document.querySelector(
            `#${activeDepartmentTabId} #${activeDayTabId} .${this.timeIntervalClass}.${this.from9To12Class}`
        );
        _9_12.innerHTML = '';

        /**
         * 12 - 15
         */
        let _12_15 = document.querySelector(
            `#${activeDepartmentTabId} #${activeDayTabId} .${this.timeIntervalClass}.${this.from12To15Class}`
        );
        _12_15.innerHTML = '';

        /**
         * 15 - 18
         */
        let _15_18 = document.querySelector(
            `#${activeDepartmentTabId} #${activeDayTabId} .${this.timeIntervalClass}.${this.from15To18Class}`
        );
        _15_18.innerHTML = '';


        /**
         * 18 - 21
         */
        let _18_21 = document.querySelector(
            `#${activeDepartmentTabId} #${activeDayTabId} .${this.timeIntervalClass}.${this.from18To21Class}`
        );
        _18_21.innerHTML = '';

        schedulesForActiveDay.forEach((schedule) => {
            // console.log('schedulesForActiveDay.forEach((schedule)');
            // console.log('schedulesForDepartmentTab = ' + JSON.stringify(schedules));
            // console.log('scheduleForActiveDay = ' + JSON.stringify(schedulesForActiveDay));
            //console.log('scheduleItem = ' + JSON.stringify(schedule));
            let startTime = this._timeToDecimal(schedule.start_time);
            let endTime = this._timeToDecimal(schedule.end_time);

            let date = this.dateRenderer.render(schedule.day);
            let scheduleCard = this.htmlBuilder.createScheduleCard(
                schedule.schedule_id, schedule.user_id, schedule.service_id,
                schedule.affiliate_id, schedule.service_name,
                schedule.price, schedule.currency,
                `${schedule.user_email}`,
                date,
                this.timeRenderer.renderShortTime(schedule.start_time),
                this.timeRenderer.renderShortTime(schedule.end_time),
                `c. ${schedule.city}, ${schedule.address}`,
                schedule.order_id
            )
            //console.log(scheduleCard);

            if (startTime >= 9 && startTime < 12) {
                if (endTime <= 12) {
                    _9_12.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _12_15.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            if (startTime >= 12 && startTime < 15) {
                if (endTime <= 15) {
                    _12_15.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _15_18.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            if (startTime >= 15 && startTime < 18) {
                if (endTime <= 18) {
                    _15_18.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _18_21.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            // console.log(startTime);
            // console.log(endTime);
            if (startTime >= 18 && startTime < 21) {
                if (endTime <= 21) {
                    _18_21.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }

            /**
             * Add listeners on shop/like icons
             */
            this.addListenerOnCancelAppointment(schedule.schedule_id);
            this.addListenerOnCompleteAppoitment(schedule.schedule_id);
        })
        //console.log('-------------------------------------------------------------------------------------------------------------------');
    }
    addListenerOnCancelAppointment(scheduleId) {
        let cancelIcon = document.querySelector(
            `#schedule-card-${scheduleId} .fe-x`
        );

        if (cancelIcon === null) return;

        /**
         * Create order for service
         */
        const handleCancelIconClick = (e) => {
            let scheduleId = cancelIcon.getAttribute('data-schedule-id');
            let orderId = cancelIcon.getAttribute('data-order-id');

            let selectedCard = document.getElementById(`schedule-card-${scheduleId}`);
            let card = selectedCard.cloneNode(true);

            this.confirmationModal.show(
                'Confirmation!',
                 card,
                'Please, confirm that you would like to <b>cancel</b> the appointment for the selected schedule item.'
            )
            let data = {
                'order_id': orderId,
                'schedule_id': scheduleId
            }
            this.confirmationModal.submit(handleConfirmClick, data);
            this.confirmationModal.close();
        }

        const handleConfirmClick = (dataToSend) => {
            this.confirmationModal.showLoader();
            this.requester.post(
                this.apiCancelOrder,
                dataToSend,
                this._successCancelOrderSchedule.bind(this),
                this._errorCancelOrderSchedule.bind(this)
            );
        }

        cancelIcon.removeEventListener('click', handleCancelIconClick); // Remove previous listener
        cancelIcon.addEventListener('click', handleCancelIconClick);
    }
    _errorCancelOrderSchedule(response) {
        this.confirmationModal.hideLoader();
        Notifier.showErrorMessage(response.error);
    }
    _successCancelOrderSchedule(response) {
        this.confirmationModal.hideLoader();
        /**
         * Hide confirmation modal window
         */
        this.confirmationModal.hide();
        /**
         * Regenerate available schedules
         */
        $(`#submit-search-button`).click();
        /**
         * Regenerate orders table
         */
        //this.ordersTable.sendApiRequest(this.ordersTable.itemsPerPage, Cookie.get('currentPage'));
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);
    }

    addListenerOnCompleteAppoitment(scheduleId) {
        let checkIcon = document.querySelector(
            `#schedule-card-${scheduleId} .fe-check`
        );

        if (checkIcon === null) return;
        /**
         * Create order for service
         */
        const handleCheckIconClick = (e) => {
            let scheduleId = checkIcon.getAttribute('data-schedule-id');
            let orderId = checkIcon.getAttribute('data-order-id');

            let selectedCard = document.getElementById(`schedule-card-${scheduleId}`);
            let card = selectedCard.cloneNode(true);

            this.confirmationModal.show(
                'Confirmation!',
                card,
                'Please, confirm that you would like to <b>mark as completed</b> the appointment for the selected schedule item.'
            )
            let data = {
                'order_id': orderId,
            }
            this.confirmationModal.submit(handleConfirmClick, data);
            this.confirmationModal.close();
        }

        const handleConfirmClick = (dataToSend) => {
            this.confirmationModal.showLoader();
            this.requester.post(
                this.apiCompleteOrder,
                dataToSend,
                this._successCancelOrderSchedule.bind(this),
                this._errorCancelOrderSchedule.bind(this)
            );
        }

        checkIcon.removeEventListener('click', handleCheckIconClick); // Remove previous listener
        checkIcon.addEventListener('click', handleCheckIconClick);
    }
}