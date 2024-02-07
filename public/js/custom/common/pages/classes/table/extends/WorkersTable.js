
import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";
import API from "../../../../../common/pages/api.js";

class WorkersTable extends Table {
    constructor(requester) {
        super(
            requester,
            API.ADMIN.API.WORKER.get.all + '?'
        )
        this.tableId = 'table-body';
    }

    populateRow(item) {
        let row = $(`<tr data-worker-id = "${item.id}">`);

        row.append(`<td>${item.id}</td>`);

        row.append(`<td>
                            ${item.name}
                      </td>`);

        row.append(`<td>
                            ${item.surname}
                      </td>`);

        row.append(`<td>
                            ${item.email}
                      </td>`);

        row.append(`<td>
                            ${item.position}
                      </td>`);

        row.append(`<td>
                            ${Number(item.salary).toFixed(1)}
                      </td>`);

        row.append(`<td>
                            ${Number(item.experience).toFixed(2)} years
                      </td>`);

        row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
                           data-worker-id = "${item.id}"
                           href="">
                            <i class="fe fe-eye me-2"></i>
                            Manage
                        </a>
                    </td>`);

        row.append('</tr>');

        return row;
    }
    /**
     *  response.data =
     *  0: {
     *      'id':
     *      'name':
     *      'surname':
     *      'email':
     *      'position':
     *      'salary':
     *      'experience':
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
            // Create a table row for each of workers
           let row = this.populateRow(item);

            // Append the row to the table body
            $(`#${this.tableId}`).append(row);

            this.manageCallback(item.id);
        });
    }
}
export default WorkersTable;