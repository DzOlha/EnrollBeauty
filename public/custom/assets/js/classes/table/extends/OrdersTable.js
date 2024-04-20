import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";
import OptionBuilder from "../../builder/OptionBuilder.js";

class OrdersTable extends Table
{
    constructor(requester, apiUrl)
    {
        super(requester, apiUrl + '?');

        this.tableId = 'table-body';
        this.extraModalTrigger = 'show-extra-modal';
        this.dataIdAttribute = 'data-id';

        this.tdCheckClass = `td-checkbox-cell`;
        this.checkboxClass = 'checkbox';

        this.data = '';
        this.paginationCountId = 'pagination-count-select';

        this.totalSumId = 'total-orders-sum';
        this.totalCountId = 'total-orders-count';
    }
    setMassActionCallback(callback) {
        this.massActionCallback = callback;
    }
    getItemsPerPage() {
        let count = document.getElementById(this.paginationCountId);

        if(count) {
            this.itemsPerPage = count.value;
            return Number(count.value);
        }
        return this.defaultPaginationRows;

        //return this.itemsPerPage;
    }

    observeNumberRowsOnThePage() {

    }

    setData(data) {
        this.data = data;
    }
    getData() {
        return this.data;
    }

    getApiUrlFormat(itemsPerPage, currentPage, orderByField, orderDirection) {
        return `${this.apiUrl}${$.param(this.getData())}&limit=${itemsPerPage}&page=${currentPage}&order_field=${orderByField}&order_direction=${orderDirection}`;
    }

    populateRow(item){
        // Create a table row for each of service item
        let row = $(`<tr ${this.dataIdAttribute} = "${item.id}">`);

        row.append(
            OptionBuilder.createCellWithCheckbox(
                item?.id, this.dataIdAttribute,
                this.checkboxClass, this.tdCheckClass
            )
        );

        row.append(`<td data-service-id="${item.service_id}">
                            ${item.service_name}
                    </td>`);


        row.append(`<td data-worker-id="${item.worker_id}">
                            ${item.worker_name} ${item.worker_surname}
                    </td>`);

        row.append(`<td data-user-id="${item.user_id}">
                            ${item.user_name} ${item.user_surname}
                    </td>`);

        row.append(`<td>
                            ${item.price} ${item?.currency}
                    </td>`);

        // 12 April 2024, 12:00 - 13:00
        row.append(`<td>
                            ${item.day}, ${item.start_time} - ${item.end_time}
                    </td>`);


        row.append(OptionBuilder.createStatusCell(item?.status));


        row.append(`<td>
                        <a class="btn ripple btn-manage manage-button"
                           id="manage-${item.id}"
                           ${this.dataIdAttribute}="${item.id}"
                           href="">
                            <i class="fe fe-eye me-2"></i>
                            Manage
                        </a>
                    </td>`);

        row.append('</tr>');
        return row;
    }

    /**
     * @param response
     * = {
     *     0: {
     *         id:
     *         service_id:
     *         service_name:
     *         worker_id:
     *         worker_name:
     *         worker_surname:
     *         user_id:
     *         user_name:
     *         user_surname:
     *         price:
     *         currency:
     *         day: //12 April 2024, 12:00 - 13:00
     *         start_time:
     *         end_time:
     *         status: [-1, 0, 1]
     *     },
     *     totalRowsCount:
     * }
     */
    populateTable(response) {
        // Iterate through the received JSON data and populate the table.
        $(`#${this.tableId}`).html('');
        if (response.error) {
            Notifier.showErrorMessage(response.error);
            return;
        }
        /**
         * Populate total sum of selected orders
         * @type {HTMLElement}
         */
        let sum = document.getElementById(this.totalSumId);
        if(sum) {
            sum.innerText =  Number(response.data['totalSum'] ?? 0).toFixed(2) + ' UAH';
        }

        /**
         * Populate total count of selected orders
         */
        let count = document.getElementById(this.totalCountId);
        if(count) {
            count.innerText = Number(response.data['totalRowsCount'] ?? 0).toFixed(0);
        }

        /**
         * Fill the table with data
         */
        $.each(response.data, (index, item) => {
            // just skip the number of all rows
            if (index === this.totalRowsCountCookie || index === 'totalSum') {
                return true;
            }
            let row = this.populateRow(item);
            // Append the row to the table body
            $(`#${this.tableId}`).append(row);

            if(!this.manageCallback) {
                this.manageCallback(item.id);
            }
        });
        this.massActionCallback();
    }
}
export default OrdersTable