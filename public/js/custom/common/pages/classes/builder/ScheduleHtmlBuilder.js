
class ScheduleHtmlBuilder {
    constructor() {
    }
    static createScheduleCard(
        serviceName, price, currency,
        worker, date, startTime, endTime, address
        )
    {
        return `<div class="card-body">
                    <div class="card-header custom-card-header border-bottom-0 ">
                        <h5 class="main-content-label my-auto tx-medium mb-0">
                            ${serviceName}
                        </h5>
                        <div class="card-options">
                            <i class="far fa-heart me-1"></i>
                            <i class="fe fe-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center pt-3 mt-auto
                               card-worker-wrapper">                            
                        <div>
                            <span class="d-block text-muted">
                                <span>Price: </span>
                                <span class="price">${price}</span>
                                <span class="currency">${currency}</span>
                            </span>
                            <div>
                                <span>Master: </span>
                                <span>
                                    <a href="" class="worker text-default">${worker}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-schedule-content">
                        <div class="time-value">
                            <div class="date">
                                <i class="fe fe-calendar"></i>                             
                                <span class="date">${date}</span>
                            </div>
                            <div class="time">
                                <i class="fe fe-clock"></i>
                                <span>
                                    <span class="start-time">${startTime}</span>
                                    <span>-</span>
                                    <span class="end-time">${endTime}</span>
                                </span>
                            </div>

                            <div class="affiliate-address">
                                <i class="fe fe-map-pin"></i>
                                 <span class="address">
                                     ${address}
                                 </span>
                            </div>
                        </div>
                    </div>
               </div>`
    }
    static createTabWeekdayContentPage(
        tabId, isActive = false
    ) {
        let active = isActive === true ? 'active' : '';
        return ` <div class="tab-pane ${active}" id="${tabId}">
                    <div class="row row-sm time-separation-wrapper">
                        <div class="row row-sm">
                            <div class="col-lg-3 time-interval-value"
                                 data-start-interval="9"
                                 data-end-interval="12"
                            >
                                9:00
                            </div>
                            <div class="col-lg-3 time-interval-value"
                                 data-start-interval="12"
                                 data-end-interval="15"
                            >
                                12:00
                            </div>
                            <div class="col-lg-3 time-interval-value"
                                 data-start-interval="15"
                                 data-end-interval="18"
                            >
                                15:00
                            </div>
                            <div class="col-lg-3 time-interval-value"
                                 data-start-interval="18"
                                 data-end-interval="21"
                            >
                                18:00
                            </div>
                        </div>
                        <div class="row row-sm time-interval-wrapper">
                            <div class="col-lg-3 time-interval 9-12"
                                 data-start-interval="9"
                                 data-end-interval="12"
                            >
<!--                            Schedule Card-->
<!--                            Schedule Card-->
<!--                            Schedule Card-->
                            </div>
                            <div class="col-lg-3 time-interval 12-15"
                                 data-start-interval="12"
                                 data-end-interval="15"
                            >
<!--                            Schedule Card-->
<!--                            Schedule Card-->
                            </div>
                            <div class="col-lg-3 time-interval 15-18"
                                 data-start-interval="15"
                                 data-end-interval="18"
                            >
<!--                            Schedule Card-->
<!--                            Schedule Card-->
<!--                            Schedule Card-->
<!--                            Schedule Card-->
                            </div>
                            <div class="col-lg-3 time-interval 18-21"
                                 data-start-interval="18"
                                 data-end-interval="21"
                            >
<!--                            Schedule Card-->
                            </div>
                        </div>
                    </div>
                </div>`
    }

    static createTabWeekdayMenuLi(
        weekday, tabId, date, isActive = false, disable = false
    ) {
        let disabled = disable === true ? 'disabled' : '';
        let active = isActive === true ? 'active' : '';
        return `<li class="">
                    <a href="#"
                       class="${active} ${disabled}"
                       data-bs-toggle="tab"
                       data-date="${date}"
                       >
                        ${weekday}
                    </a>
                </li>`
    }
    static createTabDepartmentContentPage(
        tabPaneId,
    ) {
        return `<div class="tab-pane active" id="${tabPaneId}">
                    <div class="card-body">
                        <div class="">
                            <div class="panel panel-primary tabs-style-3">
                                <div class="tab-menu-heading weekday-menu-heading">
                                    <div class="tabs-menu">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs me-3">
<!--                                           Weekday menu li-->
<!--                                           Weekday menu li-->
<!--                                           Weekday menu li-->
<!--                                           Weekday menu li-->
<!--                                           Weekday menu li-->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabs-style-3">
                            <div class="tab-content">
<!--                                Tab weekday content page-->
<!--                                Tab weekday content page-->
<!--                                Tab weekday content page-->
<!--                                Tab weekday content page-->
<!--                                Tab weekday content page-->
                            </div>
                        </div>
                    </div>
                </div>`
    }

    static createTabDepartmentMenuLi(
        departmentName, departmentId, tabId, menuItemId,
        isActive = false, disable = false,
    ) {
        let active = isActive === true ? 'active' : '';
        let disabled = disable === true ? 'disabled' : '';
        return `<li>
                    <a href="#${tabId}"
                       class="nav-link ${active} ${disabled} mt-1"
                       data-bs-toggle="tab"
                       data-id="${departmentId}"
                       id="${menuItemId}"
                       >
                       ${departmentName}
                    </a>
                 </li>`
    }

    static createAvailableSchedulePage() {
        return `<div class="tab-menu-heading main-menu-heading">
                    <div class="tabs-menu1">
                        <!-- Tabs -->
                        <ul class="nav panel-tabs main-nav-line"
                            id="departments-menu-wrapper">
<!--                            Department menu li-->
<!--                            Department menu li-->
<!--                            Department menu li-->
                        </ul>
                    </div>
                </div>
                <div class="panel-body tabs-menu-body main-content-body-right border">
                    <div class="tab-content">
<!--                        Tab department content page-->
<!--                        Tab department content page-->
<!--                        Tab department content page-->
<!--                        Tab department content page-->
                    </div>
                </div>`
    }
}