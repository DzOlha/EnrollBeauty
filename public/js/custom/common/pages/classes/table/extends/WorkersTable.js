
class WorkersTable extends Table {
    constructor(requester) {
        super(
            requester,
            '/api/admin/getWorkers?'
        )
        this.tableId = 'table-body';
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
            if (index === 'totalRowsCount') {
                return true;
            }
            // Create a table row for each of workers
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

            // Append the row to the table body
            $(`#${this.tableId}`).append(row);
        });
        // this.addListenerCancelAppointment();
    }
}