
import ScheduleHtmlBuilder from "./ScheduleHtmlBuilder.js";
class WorkerScheduleHtmlBuilder extends ScheduleHtmlBuilder {
    constructor() {
        super();
    }
    createScheduleCard(
        scheduleId, userId, serviceId, affiliateId,
        serviceName, price, currency,
        userEmail, date, startTime, endTime, address, orderId
    )
    {
        let userLabel = userId !== null ? 'User: '
            : '<span class="text-success">Available for order!</span>';

        let userLink = userId !== null ? `<span>
                                                    <a href="#" 
                                                         class="profile-url text-default" target="_blank">
                                                        ${userEmail}
                                                    </a>
                                                  </span>`
            : '';

        let iconOne = userId !== null ? 'fe-check' : 'fe-edit-3';
        let iconTwo = userId !== null ? 'fe-x' : 'fe-trash-2';

        let ID = orderId !== null ?
                                `<span>Order ID: </span>
                                ${orderId}` : '';

        return `<div class="card" id="schedule-card-${scheduleId}" 
                        data-schedule-id="${scheduleId}"
                        data-user-id="${userId}" 
                        data-service-id="${serviceId}"
                        data-affiliate-id="${affiliateId}">
                    <div class="card-header custom-card-header border-bottom-0 ">
                        <h5 class="main-content-label my-auto tx-medium mb-0">
                            ${serviceName}
                        </h5>
                        <div class="card-options">
                            <i class="fe ${iconOne}"
                                data-schedule-id="${scheduleId}"
                                 data-order-id="${orderId}">
                            </i>
                            <i class="fe ${iconTwo}" 
                                data-schedule-id="${scheduleId}"
                                data-order-id="${orderId}">
                            </i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center pt-1 mt-auto
                               card-worker-wrapper">                        
                        <div>
                            <div class="pt-1 pb-1">
                                <b>                         
                                    ${ID}
                                </b>    
                            </div>
                            <span class="d-block text-muted">
                                <span>Price: </span>
                                <span class="price">${price}</span>
                                <span class="currency">${currency}</span>
                            </span>
                            <div>
                                <span>${userLabel} </span>
                                ${userLink}
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
}
export default WorkerScheduleHtmlBuilder;