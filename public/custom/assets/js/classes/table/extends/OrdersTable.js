import Table from "../Table.js";
import Notifier from "../../notifier/Notifier.js";
import OptionBuilder from "../../builder/OptionBuilder.js";
import TimeRenderer from "../../renderer/extends/TimeRenderer.js";
import DateRenderer from "../../renderer/extends/DateRenderer.js";
import CopyHelper from "../../helper/CopyHelper.js";
import UrlRenderer from "../../renderer/extends/UrlRenderer.js";
import AddressRenderer from "../../renderer/extends/AddressRenderer.js";

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
        this.tooltipClass = 'my-tooltip';
        this.tooltipData = 'data-my-tooltip';
        this.copySuccess = 'You have successfully copied the hover text!';
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

        row.append(`<td data-service-id="${item.service_id}"
                        class="${this.tooltipClass}" 
                        ${this.tooltipData}="${item.department_name}">
                            ${item.service_name}
                    </td>`);


        let profileUrl = UrlRenderer.renderWorkerPublicProfileUrl(
                                    item.worker_name, item.worker_surname, item.worker_id
                                );

        row.append(`<td data-worker-id="${item.worker_id}" 
                        ${this.tooltipData}="${item.worker_email}" class="${this.tooltipClass}">
                        <a href="${profileUrl}" target="_blank">
                            ${item.worker_name} ${item.worker_surname}
                        </a>
                    </td>`);

        row.append(`<td data-user-id="${item.user_id}" 
                        ${this.tooltipData}="${item.worker_email}" class="${this.tooltipClass}">
                            ${item.user_name} ${item.user_surname}
                    </td>`);

        row.append(`<td>
                            ${item.price} ${item?.currency}
                    </td>`);

        // 12 April 2024, 12:00 - 13:00
        row.append(`<td class="${this.tooltipClass}" 
                        ${this.tooltipData}="${AddressRenderer.render(item.city, item.address)}">
                            ${DateRenderer.ymdToDmy(item.day)}, 
                            ${TimeRenderer.hmsToHm(item.start_time)} - 
                            ${TimeRenderer.hmsToHm(item.end_time)}
                    </td>`);


        row.append(OptionBuilder.createStatusCell(item?.status));


        // row.append(`<td>
        //                 <a class="btn ripple btn-manage manage-button"
        //                    id="manage-${item.id}"
        //                    ${this.dataIdAttribute}="${item.id}"
        //                    href="">
        //                     <i class="fe fe-eye me-2"></i>
        //                     Manage
        //                 </a>
        //             </td>`);

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
     *         worker_email:
     *         user_id:
     *         user_name:
     *         user_surname:
     *         user_email:
     *         department_id:
     *         department_name:
     *         affiliate_id:
     *         city:
     *         address:
     *         price:
     *         currency:
     *         day: //12 April 2024, 12:00 - 13:00
     *         start_time:
     *         end_time:
     *         status: [-1, 0, 1]
     *     },
     *     totalRowsCount:
     *     totalSum:
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
        this.showTooltipEmailOnHover();
        this.massActionCallback();
    }

    showTooltipEmailOnHover() {
        let tooltips = document.querySelectorAll(
            `.${this.tooltipClass}`
            );
        let size = tooltips.length;

        for(let i = 0; i < size; i++)
        {
            //console.log(tooltips[i]);
            let originalText = tooltips[i].textContent;
            let originalWidth = tooltips[i].getBoundingClientRect().width;
            let text = tooltips[i].getAttribute(this.tooltipData);

            tooltips[i].addEventListener('mouseover', () => {
                tooltips[i].textContent = text;
                tooltips[i].style.maxWidth = originalWidth + 'px';
            });

            tooltips[i].addEventListener('click', () => {
                let copied = CopyHelper.handleCopy(text);
                if(copied) {
                    Notifier.showSuccessMessage(this.copySuccess);
                }
            });

            tooltips[i].addEventListener('mouseout', () => {
                tooltips[i].textContent = originalText;
            });
        }
    }
}
export default OrdersTable