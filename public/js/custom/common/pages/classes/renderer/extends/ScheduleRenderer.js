class ScheduleRenderer {
    constructor(requester, builder, appointmentsTable, confirmationModal) {
        this.requester = requester;
        this.builder = builder;
        this.ordersTable = appointmentsTable;

        this.scheduleWrapperId = 'main-schedule-wrapper';

        this.departmentsMenuId = 'departments-menu-wrapper';
        this.departmentsContentWrapperId = 'department-tab-content-wrapper';

        this.departmentTabPaneClass = 'department-tab-pane';
        this.weekdayTabPaneClass = 'weekday-tab-pane'

        this.departmentMenuItemClass = 'department-menu-item';
        this.weekdayMenuItemClass = 'weekday-menu-item';

        this.weekdaysMenuId = 'weekdays-menu-wrapper';
        this.weekdaysContentId = 'weekday-tab-content-wrapper';

        this.daysMenuClass = 'panel-tabs';
        this.daysContentWrapperClass = 'tab-content';

        this.timeIntervalClass = 'time-interval';
        this.from9To12Class = '_9_12';
        this.from12To15Class = '_12_15';
        this.from15To18Class = '_15_18';
        this.from18To21Class = '_18_21';

        this.departmentTabContentBase = 'department-tab-content';
        this.departmentMenuItemBase = 'department-tab-menu-item';

        this.weekdayMenuItemBase = 'weekday-tab-menu-item';
        this.weekdayTabContentBase = 'weekday-tab-content';

        /**
         * Modal
         */
        this.confirmationModal = confirmationModal;

        /**
         * API
         */
        this.apiOrderSchedule = '/api/user/orderServiceSchedule';
    }

    /**
     * response example
     *  {
     *      success: true,
     *      data: {
     *          schedule: {
     *              0: {
     *                schedule_id: ,
     *                service_id: ,
     *                department_id: ,
     *                service_name: ,
     *                worker_id: ,
     *                worker_name: ,
     *                worker_surname: ,
     *                affiliate_id: ,
     *                city: ,
     *                address: ,
     *                day: ,
     *                start_time: ,
     *                end_time: ,
     *                price: ,
     *                currency:
     *              },
     *              1: {
     *
     *              }
     *              .........
     *          },
     *          departments: {
     *              0: {
     *                  id:
     *                  name:
     *              }
     *              .........
     *          },
     *          active_department: {
     *              id:
     *              name:
     *          },
     *          active_day: start_date,
     *          end_day: end_date,
     *      }
     *  }
     */
    render(response) {
        //console.log(' render(response)');
        //console.log(JSON.stringify(response));
        let wrapper = document.getElementById(this.scheduleWrapperId);
        wrapper.innerHTML = '';

        /**
         * Insert departments tabs menu and wrappers for tabs' content
         * @type {string}
         */
        let departmentInnerTabs = ScheduleHtmlBuilder.createAvailableSchedulePage();
        wrapper.insertAdjacentHTML('beforeend', departmentInnerTabs);

        /**
         * Populate departments menu with names of the departments
         * @type {HTMLElement}
         */
        let departmentsMenuWrapper = document.getElementById(this.departmentsMenuId);
        departmentsMenuWrapper.innerHTML = '';

        let departmentsContentWrapper = document.getElementById(
            this.departmentsContentWrapperId
        );
        departmentsContentWrapper.innerHTML = '';

        /**
         * Insert the blueprint of the departments menu and schedule content
         * into the page structure
         */
        response.data.departments.forEach((department) => {
            //console.log('response.data.departments.forEach((department)');
            /**
             * Set the tab id value which allows us to determine the identifier
             * of the content page (with schedule) for specific department
             * and show such content by clicking on the menu item
             * @type {string}
             */
            let tabId = `${this.departmentTabContentBase}-${department.id}`;

            /**
             * Create menu item by clicking on which we open the page content
             *  with schedules for the department
             * @type {string}
             */
            let menuItemId = `${this.departmentMenuItemBase}-${department.id}`;

            /**
             * Detect if the menu item of the department should be set as active
             * @type {boolean}
             */
            let active = department.id === response.data.active_department.id;

            /**
             * Create the item of the menu for navigation between different departments
             * @type {string}
             */
            let menuItem = ScheduleHtmlBuilder.createTabDepartmentMenuLi(
                department.name, department.id, tabId, menuItemId,
                this.departmentMenuItemClass, active
            );

            /**
             * Create the page with schedule content for corresponding department tab from menu
             * @type {string}
             */
            let contentPage = ScheduleHtmlBuilder.createTabDepartmentContentPage(
                tabId, this.weekdaysMenuId, this.weekdaysContentId, active
            );

            /**
             * Insert the manu item of the department and the corresponding
             * content schedule page into the wrapper structure
             */
            departmentsMenuWrapper.insertAdjacentHTML('beforeend', menuItem);
            departmentsContentWrapper.insertAdjacentHTML('beforeend', contentPage);

            /**
             * Set event listener to be able to navigate between schedule for
             * different departments within the selected period without need to
             * send additional requests to the server
             * @type {HTMLElement}
             */
            let a = document.getElementById(menuItemId);
            a.addEventListener('click', () => {
                //console.log('department a.click');
                /**
                 * #department-tab-content-{id} -> department-tab-content-{id}
                 * @type {string}
                 */
                let contentTabId = a.getAttribute('href').substring(1);

                /**
                 * Get department_id number {id}
                 * @type {string}
                 */
                let departmentId = a.getAttribute('data-id');
                // console.log(contentTabId);
                // console.log(departmentId);

                /**
                 * Filter the initial server response to leave only schedules
                 * that belongs to the department with {id} = departmentId
                 */
                let filteredSchedules = response.data.schedule.filter(
                    schedule => schedule.department_id == departmentId
                );
                //console.log(JSON.stringify(filteredSchedules));

                /**
                 * Fill the schedule content page for the current department id
                 * with the information about days of search and schedules available
                 * withing time intervals
                 */
                this.populateActiveDepartmentScheduleTab(
                    contentTabId, departmentId, response.data.active_day,
                    response.data.end_day, filteredSchedules
                );
            })
        })

        /**
         * Get .department-menu-item.active element
         * to detect the {id} of the active department and
         * then populate it with information about days
         * and available schedules withing time intervals
         * @type {Element}
         */
        let activeDepartmentTab = document.querySelector(
            `.${this.departmentMenuItemClass}.active`
        );

        /**
         * get {id} of active department menu
         * @type {string}
         */
        let activeDepartmentId = activeDepartmentTab.getAttribute('data-id');

        /**
         * #department-tab-content-{id} -> department-tab-content-{id}
         * @type {string}
         */
        let activeTabId = activeDepartmentTab.getAttribute('href').substring(1);

        /**
         * Filter the initial server response to leave only schedules
         * that belongs to the department with {id} = activeDepartmentId
         */
        let filteredSchedules = response.data.schedule
            .filter(
                schedule => schedule.department_id == activeDepartmentId
            );

        /**
         * Fill the schedule content page for the active department id
         * with the information about days of search and schedules available
         * withing time intervals
         */
        this.populateActiveDepartmentScheduleTab(
            activeTabId, activeDepartmentId, response.data.active_day,
            response.data.end_day, filteredSchedules
        );
    }

    /**
     * @param schedules
     * {
     *  0: {
     *    schedule_id: ,
     *    service_id: ,
     *    department_id: ,
     *    service_name: ,
     *    worker_id: ,
     *    worker_name: ,
     *    worker_surname: ,
     *    affiliate_id: ,
     *    city: ,
     *    address: ,
     *    day: ,
     *    start_time: ,
     *    end_time: ,
     *    price: ,
     *    currency:
     *    ........
     * }
     * @param activeDepartmentTabId department-tab-content-{department_id}
     * @param startDate YYYY-MM-DD
     * @param endDate YYYY-MM-DD
     * @param departmentId department_id
     */
    populateActiveDepartmentScheduleTab(
        activeDepartmentTabId, departmentId, startDate, endDate, schedules
    ) {
        //console.log('populateActiveDepartmentScheduleTab');
        //console.log('activeDepartmentTabId = ' + activeDepartmentTabId);
        let daysMenuWrapper = document.querySelector(
            `#${activeDepartmentTabId} .${this.daysMenuClass}`
        );
        daysMenuWrapper.innerHTML = '';

        let daysContentWrapper = document.querySelector(
            `#${activeDepartmentTabId} .${this.daysContentWrapperClass}`
        );
        daysContentWrapper.innerHTML = '';

        let days = DateRenderer.getDatesBetween(startDate, endDate);
        days.forEach((day) => {
            //console.log('days.forEach((day)');
            /**
             * day is in YYYY-MM-DD format
             *
             * Get short weekday code like 'Mn' or 'Fr'
             * @type {string}
             */
            let shortWeekDayCode = DateRenderer.getDayOfWeek(day);

            /**
             * Get '2 Month' format
             * @type {string}
             */
            let date = DateRenderer.shortRender(day);

            /**
             * Concatenate value to get something like '2 November: Tu'
             * to display it on frontend
             * @type {string}
             */
            let weekday = `${date}: ${shortWeekDayCode}`;

            /**
             * Use day in {id}_YYYY-MM-DD format as a tab id to identify
             * which schedule page to display
             * @type {*}
             */
            let dayTabId = '_' + departmentId + '_' + day;

            /**
             * id = weekday-tab-menu-item-{id}_YYYY-MM-DD
             * @type {string}
             */
            let menuItemId = `${this.weekdayMenuItemBase}-${departmentId}${dayTabId}`;

            /**
             * Check if the day is active
             * @type {boolean}
             */
            let active = day === startDate;

            /**
             * Create menu item to navigate for the specific day schedule tab
             * @type {string}
             */
            let menuItem = ScheduleHtmlBuilder.createTabWeekdayMenuLi(
                weekday, dayTabId, this.weekdayMenuItemClass, menuItemId, day, active
            );

            /**
             * Create the content page with schedule for the day with
             * time separation by 9-12, 12-15, 15-18, 18-21 intervals
             * @type {string}
             */
            let contentPage = ScheduleHtmlBuilder.createTabWeekdayContentPage(
                dayTabId, active
            );

            daysMenuWrapper.insertAdjacentHTML('beforeend', menuItem);
            daysContentWrapper.insertAdjacentHTML('beforeend', contentPage);

            /**
             * Set event listener to be able to navigate between schedule for
             * different days within the selected period without need to
             * send additional requests to the server
             * @type {HTMLElement}
             */
            let a = document.getElementById(menuItemId);
            a.addEventListener('click', () => {
                //console.log('weekday a.click');

                /**
                 * #_{id}_YYYY-MM-DD -> _id_YYYY-MM-DD
                 * @type {string}
                 */
                let contentTabId = a.getAttribute('href').substring(1);

                this.populateActiveDayScheduleTab(
                    activeDepartmentTabId, contentTabId, schedules
                );
            })
        })

        /**
         * Get the active weekday .weekday-menu-item.active
         * @type {Element}
         */
        let activeWeekdayTab = document.querySelector(
            `#${activeDepartmentTabId} .${this.weekdayMenuItemClass}.active`
        );

        /**
         * Get something like #_id_YYYY-MM-DD -> _id_YYYY-MM-DD
         * @type {string}
         */
        let activeDayTabId = activeWeekdayTab.getAttribute('href')
            .substring(1);

        /**
         * Fill the time intervals with schedule cards
         */
        this.populateActiveDayScheduleTab(
            activeDepartmentTabId, activeDayTabId, schedules
        );
    }

    /**
     * @param schedules
     * {
     *     0: {
     *    schedule_id: ,
     *    service_id: ,
     *    department_id: ,
     *    service_name: ,
     *    worker_id: ,
     *    worker_name: ,
     *    worker_surname: ,
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
        //console.log('populateActiveDayScheduleTab');
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
            let startTime = parseInt(schedule.start_time);
            let endTime = parseInt(schedule.end_time);

            let date = DateRenderer.render(schedule.day);
            let scheduleCard = ScheduleHtmlBuilder.createScheduleCard(
                schedule.schedule_id, schedule.worker_id, schedule.service_id,
                schedule.affiliate_id, schedule.service_name,
                schedule.price, schedule.currency,
                `${schedule.worker_name} ${schedule.worker_surname}`,
                date,
                TimeRenderer.renderShortTime(schedule.start_time),
                TimeRenderer.renderShortTime(schedule.end_time),
                `c. ${schedule.city}, ${schedule.address}`
            )
            //console.log(scheduleCard);

            if (startTime >= 9 && startTime <= 12) {
                if (endTime <= 12) {
                    _9_12.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _12_15.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            if (startTime >= 12 && startTime <= 15) {
                if (endTime <= 15) {
                    _12_15.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _15_18.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            if (startTime >= 15 && startTime <= 18) {
                if (endTime <= 18) {
                    _15_18.insertAdjacentHTML('beforeend', scheduleCard);
                } else {
                    _18_21.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }
            if (startTime >= 18 && startTime <= 21) {
                if (endTime <= 21) {
                    _18_21.insertAdjacentHTML('beforeend', scheduleCard);
                }
            }

            /**
             * Add listeners on shop/like icons
             */
            this.addListenerOnOrderSchedule(schedule.schedule_id);
            this.addListenerOnLikeSchedule(schedule.schedule_id);
        })
        //console.log('-------------------------------------------------------------------------------------------------------------------');
    }

    addListenerOnOrderSchedule(scheduleId) {
        let shopIcon = document.querySelector(
            `#schedule-card-${scheduleId} .fe-shopping-cart`
        );

        if (shopIcon === null) return;

        /**
         * Create order for service
         */
        const handleShopIconClick = () => {
            let scheduleId = shopIcon.getAttribute('data-schedule-id');

            let selectedCard = document.getElementById(`schedule-card-${scheduleId}`);
            let card = selectedCard.cloneNode(true);

            this.confirmationModal.show(
                'Confirmation!',
                card,
                'Please confirm that you would like to order the selected item from available schedules.'
            )

            this.confirmationModal.submit(handleConfirmClick, scheduleId);
            this.confirmationModal.close();
        }

        const handleConfirmClick = (id) => {
            this.requester.post(
                this.apiOrderSchedule,
                {'schedule_id': id},
                this._successOrderSchedule.bind(this),
                this._errorOrderSchedule.bind(this)
            );
        }

        shopIcon.removeEventListener('click', handleShopIconClick); // Remove previous listener
        shopIcon.addEventListener('click', handleShopIconClick);
    }

    _successOrderSchedule(response) {
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
        this.ordersTable.sendApiRequest(this.ordersTable.itemsPerPage, Cookie.get('currentPage'));
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);
    }

    _errorOrderSchedule(response) {
        Notifier.showErrorMessage(response.error);
    }

    addListenerOnLikeSchedule(scheduleId) {
        let likeIcon = document.querySelector(
            `#schedule-card-${scheduleId} .fa-heart`
        );
        if (likeIcon === null) return;
        /**
         * Add schedule to the liked/saved ones
         */
        likeIcon.addEventListener('click', () => {
        });
    }

}