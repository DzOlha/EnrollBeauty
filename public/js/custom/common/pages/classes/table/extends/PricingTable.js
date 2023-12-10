
import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";
import API from "../../../../../common/pages/api.js";

class PricingTable extends Table {
    constructor(requester) {
        super(
            requester,
            API.WORKER.API.PROFILE["service-pricing"].get.all + "?"
        )
        this.tableId = 'table-body';
    }
    /**
     *  response.data =
     *  0: {
     *      'id':
     *      'name': it's service name
     *      'price':
     *      'currency':
     *      'updated_datetime': in the 'November 25, 15:00' format (F j, H:i)
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
            // Create a table row for each of pricing item
            let row = $(`<tr data-pricing-id = "${item.id}">`);

            row.append(`<td>${item.id}</td>`);

            row.append(`<td>${item.name}</td>`);

            let price = item.price + ' ' + item.currency;
            row.append(`<td>${price}</td>`);

            row.append(`<td>${item.updated_datetime}</td>`);

            row.append(`<td>
                        <a class="btn ripple btn-manage manage-button manage-pricing"
                           id="manage-${item.id}"
                           data-service-id="${item.service_id}"
                           data-service-price="${item.price}"
                           href="">
                            <i class="fe fe-eye me-2"></i>
                            Manage
                        </a>
                    </td>`);

            row.append('</tr>');

            // Append the row to the table body
            $(`#${this.tableId}`).append(row);
        });
        // this.addListenerCancelAppointment();
    }
}
export default PricingTable;