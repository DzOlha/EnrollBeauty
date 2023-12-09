class AppointmentsTable extends Table {
    constructor(
        requester, confirmationModal,
        dateRenderer, timeRenderer
    ) {
        super(
            requester,
            '/api/user/getUserComingAppointments?'
        );
        this.tableId = 'table-body';
        this.confirmationModal = confirmationModal;
        this.dateRenderer = dateRenderer;
        this.timeRenderer = timeRenderer;
        this.apiCancelOrder = '/api/user/cancelServiceOrder';

        this.searchScheduleButtonId = 'submit-search-button';
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

            row.append(`<td>${this.dateRenderer.render(item.start_datetime)}</td>`);
            row.append(`<td>${this.timeRenderer.render(item.start_datetime)}</td>`);
            row.append(`<td>${this.timeRenderer.render(item.end_datetime)}</td>`);
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

            this.confirmationModal.show(
                'Confirmation!',
                ``,
                `Please confirm that you would like to cancel the appointment with id ${id}`
            )

            this.confirmationModal.submit(handleConfirmClick, id);
            this.confirmationModal.close();
        }
        let handleConfirmClick = (id) => {
            this.requester.post(
                this.apiCancelOrder,
                {'order_id': id},
                this._successCancelOrder.bind(this),
                this._errorCancelOrder.bind(this)
            );
        }

        cancelButtons.forEach((cancelButton) => {
            cancelButton.addEventListener('click', handleCancelAppointment);
        })
    }

    _successCancelOrder(response) {
        this.confirmationModal.hide();
        /**
         * Research available schedules
         */
        $(`#${this.searchScheduleButtonId}`).click();

        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);

        /**
         * Update the table of upcoming appointments
         */
        this.sendApiRequest(this.itemsPerPage, Cookie.get(this.currentPageCookie));
    }

    _errorCancelOrder(response) {
        //this.confirmationModal.hide();
        Notifier.showErrorMessage(response.error);
    }
}