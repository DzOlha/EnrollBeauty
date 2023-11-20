class AppointmentsTable extends Table {
    constructor() {
        super(
            '/api/user/getUserComingAppointments?'
        );
        this.tableId = 'table-body';
        this.confirmationModal = new ConfirmationModal();
        this.apiCancelOrder = '/api/user/cancelServiceOrder';
    }

    manageAll() {
        super.manageAll();
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

            row.append(`<td>${DateRenderer.render(item.start_datetime)}</td>`);
            row.append(`<td>${TimeRenderer.render(item.start_datetime)}</td>`);
            row.append(`<td>${TimeRenderer.render(item.end_datetime)}</td>`);
            row.append(`<td>${item.price + ' ' + item.currency}</td>`);

            row.append(`<td>
                        <a class="btn ripple btn-manage cancel-button"
                           id="cancel-${item.id}"
                           data-appointment-id = "${item.id}"
                           href="">
                            <i class="fe fe-eye me-2"></i>
                            Cancel
                        </a>
                    </td>`);

            row.append('</tr>');

            // Append the row to the table body
            $(`#${this.tableId}`).append(row);
        });
        this.addListenerCancelAppointment();
    }

    addListenerCancelAppointment() {
        let cancelButtons = Array.from(
            document.getElementsByClassName('cancel-button')
        );
        // cancelButton.removeEventListener('click', handleShopIconClick); // Remove previous listener
        const handleCancelAppointment = (e) => {
            e.preventDefault();

            let id = e.currentTarget.getAttribute('data-appointment-id');
            console.log(id);
            console.log('click cancel');

            this.confirmationModal.show(
                'Confirmation!',
                ``,
                `Please confirm that you would like to cancel the appointment with id ${id}`
            )

            this.confirmationModal.submit(handleConfirmClick, id);
            this.confirmationModal.close();
        }
        let handleConfirmClick = (id) => {
            console.log('handleConfirmClick + ' + id);
            this.requestor.post(
                this.apiCancelOrder,
                {'order_id': id},
                this._successCancelOrder.bind(this),
                this._errorCancelOrder.bind(this)
            );
        }

        cancelButtons.forEach((cancelButton) => {
            //cancelButton.removeEventListener('click', handleCancelAppointment);
            //let old = cancelButton.cloneNode(true);
            //cancelButton.replaceWith(old);
            console.log('forEach');
            cancelButton.addEventListener('click', handleCancelAppointment);
        })
    }

    _successCancelOrder(response) {
        this.confirmationModal.hide();
        Notifier.showSuccessMessage(response.success);
        this.sendApiRequest(this.itemsPerPage, Cookie.get('currentPage'));
    }

    _errorCancelOrder(response) {
        //this.confirmationModal.hide();
        Notifier.showErrorMessage(response.error);
    }
}