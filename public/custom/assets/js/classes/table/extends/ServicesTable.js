import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";

class ServicesTable extends Table {
    constructor(requester, apiUrl, disabledWorkersBtn = false) {
        super(
            requester,
            apiUrl + '?'
        )
        this.tableId = 'table-body';
        this.extraModalTrigger = 'show-extra-modal';
        this.dataIdAttribute = 'data-service-id';

        this.disabledWorkersBtn = disabledWorkersBtn;
    }

    setShowWorkersCallback(callback, context){
        this.showWorkersCallback = callback.bind(context);
    }

    populateRow(item){
        // Create a table row for each of service item
        let row = $(`<tr ${this.dataIdAttribute} = "${item.id}">`);

        row.append(`<td>${item.id}</td>`);

        row.append(`<td>${item.name}</td>`);

        row.append(`<td data-department-id="${item.department_id}">
                            ${item.department_name}
                      </td>`);

        let disabled = this.disabledWorkersBtn ? 'disabled' : '';
        row.append(`<td>
                        <button class="btn bg-secondary button-in-cell ${disabled}" type="button"
                                id="${this.extraModalTrigger}-${item.id}" 
                                ${this.dataIdAttribute}="${item.id}">
                            View Workers
                        </button>
                    </td>`)

        row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
                           data-service-id="${item.id}"
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
     *      'name': it's service name
     *      'department_id':
     *      'department_name':
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

            if(!this.disabledWorkersBtn) {
                this.showWorkersCallback(item.id);
            }
        });
        // this.addListenerCancelAppointment();
    }
}
export default ServicesTable;