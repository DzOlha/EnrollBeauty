import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";

class AffiliatesTable extends Table
{
    constructor(requester, apiUrl, dateRenderer) {
        super(
            requester,
            apiUrl + '?'
        )
        this.dateRenderer = dateRenderer;
        this.tableId = 'table-body';
        this.dataIdAttribute = 'data-affiliate-id';
        this.dataManagerId = 'data-manager-id';
        this.profileManager = 'profile-affiliate-manager';
    }

    /**
     *
     * @param item = {
     *      'id':
     *      'name':
     *      'country':
     *      'city':
     *      'address':
     *      'manager_id':
     *      'manager_name':
     *      'manager_surname':
     *      'created_date':
     * }
     * @returns {*|jQuery|HTMLElement}
     */
    populateRow(item) {
        let row = $(`<tr ${this.dataIdAttribute} = "${item.id}">`);

        row.append(`<td>${item.id}</td>`);

        row.append(`<td>
                            ${item.name}
                      </td>`);

        row.append(`<td>
                            ${item.country}
                      </td>`);

        row.append(`<td>
                            ${item.city}
                      </td>`);

        row.append(`<td>
                            ${item.address}
                      </td>`);

        let managerName = item?.manager_name ? item?.manager_name + ' ' + item?.manager_surname
                        : 'No Manager';

        let class_ = item?.manager_name ? 'bg-success' : 'bg-secondary';

        row.append(`<td ${this.dataManagerId}="${item?.manager_id}">
                            <span class="btn ${class_} button-in-cell" id="${this.profileManager}-${item.id}">
                                ${managerName}
                            </span>
                      </td>`);

        let date = this.dateRenderer.renderDatetime(item.created_date);
        row.append(`<td>
                            ${date}
                      </td>`);

        row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
                           ${this.dataIdAttribute} = "${item.id}"
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
     *      'country':
     *      'city':
     *      'address':
     *      'manager_id':
     *      'manager_name':
     *      'manager_surname':
     *      'created_date':
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


            //this.manageCallback(item.id);

        });
    }
}
export default AffiliatesTable;