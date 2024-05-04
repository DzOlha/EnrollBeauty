import ScheduleRenderer from "./ScheduleRenderer.js";
import AddressRenderer from "./AddressRenderer.js";

class WorkerScheduleRenderer extends ScheduleRenderer {
    constructor(
        requester,
        confirmationModal, htmlBuilder,
        dateRenderer, timeRenderer
    ) {
        super(requester,
            confirmationModal, htmlBuilder,
            dateRenderer, timeRenderer);
    }

    setCancelOrderCallback(callback, context) {
        this.cancelOrderCallaback = callback.bind(context);
    }
    setCompleteOrderCallback(callback, context) {
        this.completeOrderCallaback = callback.bind(context);
    }

    setEditScheduleCallback(callback, context) {
        this.editScheduleCallback = callback.bind(context);
    }

    setDeleteScheduleCallback(callback, context) {
        this.deleteScheduleCallback = callback.bind(context);
    }

    render(response) {
        //console.log(' render(response)');
        //console.log(JSON.stringify(response));
        let wrapper = document.getElementById(this.scheduleWrapperId);
        wrapper.innerHTML = '';

        /**
         * Insert departments tabs menu and wrappers for tabs' content
         * @type {string}
         */
        let departmentInnerTabs = this.htmlBuilder.createAvailableSchedulePage();
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
        let department = {
            id: 0,
            name: 'Schedule'
        }
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
        let active = true;

        /**
         * Create the item of the menu for navigation between different departments
         * @type {string}
         */
        let menuItem = this.htmlBuilder.createTabDepartmentMenuLi(
            department.name, department.id, tabId, menuItemId,
            this.departmentMenuItemClass, active
        );

        /**
         * Create the page with schedule content for corresponding department tab from menu
         * @type {string}
         */
        let contentPage = this.htmlBuilder.createTabDepartmentContentPage(
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
         * Fill the schedule content page for the active department id
         * with the information about days of search and schedules available
         * withing time intervals
         */
        this.populateActiveDepartmentScheduleTab(
            activeTabId, activeDepartmentId, response.data.active_day,
            response.data.end_day, response.data.schedule
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
        //console.log(JSON.stringify(schedules));
        //console.log('populateActiveDayScheduleTab children');
        /**
         * Filter schedules to leave only ones that are for the day, which tab is active now
         */
        let schedulesForActiveDay = schedules.filter(
            schedule => {
                /**
                 * _id_YYYY-MM-DD -> [0: '', 1: id, 2: YYYY-MM-DD]
                 */
                let splitted = activeDayTabId.split('_');

                return schedule.day === splitted[2];
            }
        );

        /**
         * Sorting schedules by start_time and end_time
         * to show by ascending order
         * */
        schedulesForActiveDay.sort((a, b) => {
            // Compare start_time
            let firstStart = this._timeToDecimal(a.start_time);
            let firstEnd = this._timeToDecimal(a.end_time);

            let secondStart = this._timeToDecimal(b.start_time);
            let secondEnd = this._timeToDecimal(b.end_time);

            let comparisonStart = 0;
            if(firstStart > secondStart) {
                comparisonStart = 1;
            } else {
                comparisonStart = -1;
            }

            //console.log(a);
            //console.log(b);
            // If start_time is the same, compare end_time
            if (comparisonStart === 0) {
                let comparisonEnd = 0;
                if(firstEnd > secondEnd) {
                    comparisonEnd = 1;
                } else {
                    comparisonEnd = -1;
                }
                return comparisonEnd
            }

            return comparisonStart;
        });

        //console.log('activeDayTabId = ' + activeDayTabId);
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
            //('schedulesForActiveDay.forEach((schedule)');
            //console.log('schedulesForDepartmentTab = ' + JSON.stringify(schedules));
            //console.log('scheduleForActiveDay = ' + JSON.stringify(schedulesForActiveDay));
            //console.log('scheduleItem = ' + JSON.stringify(schedule));
            let startTime = this._timeToDecimal(schedule.start_time);
            let endTime = this._timeToDecimal(schedule.end_time);

            let date = this.dateRenderer.render(schedule.day);

            let userId = schedule.hasOwnProperty('user_id') ? schedule.user_id : null;
            let userEmail = schedule.hasOwnProperty('user_email') ? schedule.user_email : null;
            let orderId = schedule.hasOwnProperty('order_id') ? schedule.order_id : null;

            //console.log(schedule.hasOwnProperty('user_id'));
            let scheduleCard = this.htmlBuilder.createScheduleCard(
                schedule.schedule_id, userId, schedule.service_id,
                schedule.affiliate_id, schedule.service_name,
                schedule.price, schedule.currency,
                userEmail,
                date,
                this.timeRenderer.renderShortTime(schedule.start_time),
                this.timeRenderer.renderShortTime(schedule.end_time),
                AddressRenderer.render(schedule.city, schedule.address),
                orderId
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
             * Add listeners on cancel/complete icons
             */
            if(schedule.hasOwnProperty('order_id')) {
                this.cancelOrderCallaback(schedule.schedule_id);
                this.completeOrderCallaback(schedule.schedule_id);
            } else {
                this.editScheduleCallback(schedule.schedule_id);
                this.deleteScheduleCallback(schedule.schedule_id);
            }
        })
        //console.log('-------------------------------------------------------------------------------------------------------------------');
    }
}
export default WorkerScheduleRenderer;