import Table from "../Table.js";
import API from "../../../api.js";
import Notifier from "../../notifier/Notifier.js";

class DepartmentsTable extends Table
{
    constructor(requester) {
        super(
            requester,
            API.ADMIN.API.DEPARTMENT.get["all-limited"] + '?'
        )
        this.tableId = 'table-body';
        this.dataIdAttribute = 'data-department-id';
        this.dataNameAttribute = 'data-department-name';
        this.workersTrigger = 'show-workers';
    }
    setShowServicesCallback(callback, context){
        this.showServicesCallback = callback.bind(context);
    }
    populateRow(item){
        // Create a table row for each of service item
        let row = $(`<tr ${this.dataIdAttribute} = "${item.id}">`);

        row.append(`<td>${item.id}</td>`);

        row.append(`<td>${item.name}</td>`);

        row.append(`<td id="${this.workersTrigger}-${item.id}" ${this.dataIdAttribute}="${item.id}">
                        <button class="btn bg-secondary" type="button">Show Workers</button>
                    </td>`)

        row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
                           ${this.dataIdAttribute}="${item.id}"
                           ${this.dataNameAttribute}="${item.name}"
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
     *      'name': it's department name
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
            let row = this.populateRow(item);
            // Append the row to the table body
            $(`#${this.tableId}`).append(row);

            this.manageCallback(item.id);
            //this.showServicesCallback(item.id);
        });
    }
}
export default DepartmentsTable;