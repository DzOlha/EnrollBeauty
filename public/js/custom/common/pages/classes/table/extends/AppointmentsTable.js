import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";
import Cookie from "../../cookie/Cookie.js";
import API from "../../../../../common/pages/api.js";

class AppointmentsTable extends Table {
    constructor(
        requester, confirmationModal,
        dateRenderer, timeRenderer
    ) {
        super(
            requester,
            API.USER.API.ORDER.service.upcoming.get.all + '?'
        );
        this.tableId = 'table-body';
        this.confirmationModal = confirmationModal;
        this.dateRenderer = dateRenderer;
        this.timeRenderer = timeRenderer;
        this.apiCancelOrder = API.USER.API.ORDER.service.cancel;

        this.searchScheduleButtonId = 'submit-search-button';
    }

    setCancelOrderCallback(callback, context)
    {
        this.cancelOrderCallback = callback.bind(context);
    }

    POPULATE() {
        super.POPULATE();
    }

    /**
     *  response.data =
     *  0: {
     *      'id':
     *      'service_id':
     *      'service_name':
     *      'worker_id':
     *      'worker_name':
     *      'worker_surname':
     *      'affiliate_id':
     *      'affiliate_city':
     *      'affiliate_address':
     *      'start_datetime':
     *      'end_datetime':
     *      'price':
     *      'currency':
     * }
     * ....
     * 'totalRowsCount':
     * @param response
     */
    populateTable(response) {
        // Iterate through the received JSON data and populate the table.
        $(`#${this.tableId}`).html('');
        if (response.error) {
            Notifier.showErrorMessage(response.error);
            return;
        }
        $.each(response.data, (index, item) => {
            // just skip the number of all rows
            if (index === this.totalRowsCountCookie) {
                return true;
            }
            // Create a table row for each of appointments
            let row = $(`<tr data-appointment-id = "${item.id}">`);

            row.append(`<td>${item.id}</td>`);

            row.append(`<td data-service-id = "${item.service_id}">
                            ${item.service_name}
                      </td>`);

            row.append(`<td data-worker-id = "${item.worker_id}">
                            ${item.worker_name + ' ' + item.worker_surname}
                      </td>`);

            row.append(`<td data-affiliate-id = "${item.affiliate_id}">
                            ${item.affiliate_city + ', ' + item.affiliate_address}
                      </td>`);

            let day = this.dateRenderer.render(item.start_datetime);
            let startTime = this.timeRenderer.render(item.start_datetime);
            let endTime = this.timeRenderer.render(item.end_datetime);

            row.append(`<td>${day}</td>`);
            row.append(`<td>${startTime}</td>`);
            row.append(`<td>${endTime}</td>`);

            let price = item.price + ' ' + item.currency;
            row.append(`<td>${price}</td>`);

            row.append(`<td>
                        <a class="btn ripple btn-manage cancel-button"
                           id="cancel-${item.id}"
                           data-appointment-id = "${item.id}"
                           data-service-name = "${item.service_name}"
                           data-day = "${day}"
                           data-start-time="${startTime}"
                           data-end-time="${endTime}"
                           data-price="${price}"
                           href="">
                            <i class="fe fe-eye me-2"></i>
                            Cancel
                        </a>
                    </td>`);

            row.append('</tr>');

            // Append the row to the table body
            $(`#${this.tableId}`).append(row);

            this.cancelOrderCallback(item.id);
        });
    }
}
export default AppointmentsTable;