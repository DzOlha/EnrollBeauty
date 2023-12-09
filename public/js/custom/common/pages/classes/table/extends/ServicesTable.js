
class ServicesTable extends Table {
    constructor(requester) {
        super(
            requester,
            '/api/worker/service/get/all?'
        )
        this.tableId = 'table-body';
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
            // Create a table row for each of service item
            let row = $(`<tr data-pricing-id = "${item.id}">`);

            row.append(`<td>${item.id}</td>`);

            row.append(`<td>${item.name}</td>`);

            row.append(`<td data-department-id="${item.department_id}">
                            ${item.department_name}
                      </td>`);

            row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
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