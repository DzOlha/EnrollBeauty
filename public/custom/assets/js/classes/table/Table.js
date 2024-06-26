import Cookie from "../cookie/Cookie.js";
import TableLoader from "../loader/TableLoader.js";
import CONST from "../../config/contants/constants.js";


class Table {
    constructor(requester, apiUrl, orderByField = null, orderDirection = null) {
        this.apiUrl = apiUrl; // Store the API URL
        this.totalRowsCount = 1;
        this.itemsPerPage = this.getItemsPerPage();
        this.defaultPaginationRows = 20;
        this.totalPages = Math.ceil(this.totalRowsCount / this.itemsPerPage);

        this.sortedBy = orderByField ?? 'id';
        this.sortingOrder = orderDirection ?? 'asc';

        this.tableId = 'data-table';
        this.currentPageCookie =  'currentPage';
        this.totalRowsCountCookie = 'totalRowsCount';

        this.arrowDown = CONST.arrowDown;
        this.arrowUp = CONST.arrowUp;

        this.requester = requester;
    }

    setManageCallback(callback, context) {
        this.manageCallback = callback.bind(context);
    }

    POPULATE() {
        let current = Cookie.get( this.currentPageCookie);
        if (!current || !Number.isInteger(current)) {
            current = this.setInitialCookie();
        }
        this.sendApiRequest(this.itemsPerPage, current, this.sortedBy, this.sortingOrder);

        this.observeNumberRowsOnThePage(this.itemsPerPage, current);
        this.replaceUpDownArrows();

        // Set a handler to reset the cookie on page unload
        window.addEventListener('beforeunload', () => {
            this.resetCookie();
        });
    }

    regenerate() {
        this.sendApiRequest(this.getItemsPerPage(), Cookie.get(this.currentPageCookie));
    }

    resetCookie() {
        Cookie.remove( this.currentPageCookie);
        Cookie.remove(this.totalRowsCountCookie);
    }

    setInitialCookie() {
        Cookie.set( this.currentPageCookie, 1);
        return 1;
    }

    setApiUrl(apiUrl) {
        this.apiUrl = apiUrl;
    }

    getTotalPages() {
        return Math.ceil(this.totalRowsCount / this.itemsPerPage);
    }

    getItemsPerPage() {
        let count = document.querySelector('span[title]');
        if(count !== null) {
            return Number(count.innerText);
        }
        return this.defaultPaginationRows;
        //return this.itemsPerPage;
    }

    observeNumberRowsOnThePage(itemsPerPage, currentPage) {
        // Function to be executed when the 'title' attribute changes
        let handleAttributeChange = (mutationsList, observer) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'title') {
                    // The 'title' attribute has changed; call your function here

                    let newItemsPerPage = this.getItemsPerPage();

                    let totalPages = this.getTotalPages(Cookie.get(this.totalRowsCountCookie), newItemsPerPage);
                    let value = Math.ceil((Cookie.get( this.currentPageCookie) * itemsPerPage) / newItemsPerPage);
                    let newCurrentPage = value > totalPages ? totalPages : value;
                    itemsPerPage = newItemsPerPage;

                    //console.log("newCurrentPage() = " + newCurrentPage);
                    Cookie.set( this.currentPageCookie, newCurrentPage);

                    this.generatePaginationControls(totalPages, newCurrentPage);
                    this.sendApiRequest(this.getItemsPerPage(), newCurrentPage);
                    break;
                }
            }
        };
        // Target element to observe
        const targetElement = document.querySelector('span[title]');

        // Create a MutationObserver instance
        const observer = new MutationObserver(handleAttributeChange);

        // Start observing changes to the 'title' attribute of the target element
        observer.observe(targetElement, {attributes: true});
    }

    getApiUrlFormat(itemsPerPage, currentPage, orderByField, orderDirection) {
        return `${this.apiUrl}limit=${itemsPerPage}&page=${currentPage}&order_field=${orderByField}&order_direction=${orderDirection}`;
    }

    sendApiRequest(
        itemsPerPage, currentPage, orderByField = null, orderDirection = null
    ) {
        let requestTimeout = TableLoader.show(this.tableId);


        if (!orderByField || !orderDirection) {

            orderByField = this.sortedBy;
            orderDirection = this.sortingOrder;
        }

        //console.log('orderByField = ' + orderByField + "; orderDirection = " + orderDirection);

        $.getJSON(this.getApiUrlFormat(itemsPerPage, currentPage, orderByField, orderDirection),
            (response) => {
                // Clear the timeout since the response is received

                TableLoader.hide(this.tableId, requestTimeout);

                this.populateTable(response);

                // regenerate pagination
                this.totalRowsCount = response.data?.totalRowsCount;

                Cookie.set(this.totalRowsCountCookie, this.totalRowsCount);
                this.itemsPerPage = this.getItemsPerPage();
                this.totalPages = this.getTotalPages();
                this.generatePaginationControls(currentPage);
            });
    }

    populateTable(response) {
    }

    generatePaginationControls(currentPage) {
        const paginationControls = $('.pagination');
        currentPage = Number(currentPage);

        // Clear existing pagination controls
        paginationControls.empty();

        const totalPages = this.totalPages;
        const maxVisiblePages = 10; // Adjust this value as needed

        // Calculate start and end page numbers
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        // Ensure that the end page doesn't exceed the total pages
        startPage = Math.max(1, endPage - maxVisiblePages + 1);

        // Previous Page
        paginationControls.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage - 1}">Prev</a>
        </li>
    `);

        // Page Numbers
        for (let i = startPage; i <= endPage; i++) {
            paginationControls.append(`
            <li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
        }

        // Next Page
        paginationControls.append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
          <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>
    `);

        // Unbind previously attached click event handlers
        paginationControls.off('click', 'a.page-link');

        // Use event delegation to handle click events
        paginationControls.on('click', 'a.page-link', (event) => {
            event.preventDefault();
            const page = Number($(event.target).data('page'));
            this.loadPage(page);
        });
    }

    loadPage(page) {
        if (page >= 1 && page <= this.totalPages) {
            Cookie.set(this.currentPageCookie, page);
            this.sendApiRequest(this.getItemsPerPage(), page);
        }
    }

    replaceUpDownArrows() {
        //console.log('replaceUpDownArrows');
        let orders = {
            'initial': 'asc',
            'desc': 'asc',
            'asc': 'desc'
        }
        let arrowUp = this.arrowUp;
        let arrowDown = this.arrowDown;
        let icons = {
            'initial': arrowDown,
            'arrows_down.svg': arrowUp,
            'arrows_up.svg': arrowDown
        }
        //console.log(JSON.stringify(icons));
        let arrowColumns = Array.from(
            document.getElementsByClassName('arrow_column')
        );
        let sortArrows = Array.from(
            document.getElementsByClassName('sort_arrow')
        );
        let size = sortArrows.length;
        //console.log(oldActiveArrowColumn.innerText);
        for (let i = 0; i < size; i++) {
            let order, icon;
            arrowColumns[i].addEventListener('click', (e) => {
                let oldActiveArrowColumn = document.getElementsByClassName(
                    'arrow_column active'
                )[0];
                //console.log('replaceUpDownArrows -> click!');
                /**
                 * Check if the current active column was not clicked again to change sort order
                 * Click to change the field of sorting
                 */
                if (oldActiveArrowColumn.innerText !== arrowColumns[i].innerText) {
                    /**
                     * Clear the previous sorted column arrow
                     */
                    oldActiveArrowColumn.classList.remove('active');
                    oldActiveArrowColumn.querySelector('.sort_arrow')
                        .setAttribute('src', '');

                    /**
                     * Add class 'active' to the current clicked column to mark it as a sorting one
                     */
                    arrowColumns[i].classList.add('active');

                    /**
                     * Set the actual order direction for sorting dataset
                     */
                    let dataOrder = sortArrows[i].getAttribute('data-order');
                    order = dataOrder !== '' ? dataOrder : 'initial';


                    let currentSrc = sortArrows[i].getAttribute('src');

                    /**
                     * set the icon src (up or down)
                     */
                    icon = currentSrc !== '' ? currentSrc.split('/').pop() : 'initial';
                }
                /**
                 * Click to change the order of sorting
                 */
                else {
                    /**
                     * Set the actual order direction for sorting dataset
                     */
                    let dataOrder = sortArrows[i].getAttribute('data-order');
                    order = dataOrder !== '' ? dataOrder : 'initial';

                    let currentSrc = sortArrows[i].getAttribute('src');
                    /**
                     * set the icon src (up or down)
                     */
                    icon = currentSrc !== '' ? currentSrc.split('/').pop() : 'initial';
                }

                sortArrows[i].setAttribute('data-order', orders[order]);
                sortArrows[i].setAttribute('src', icons[icon]);

                this.sortedBy = sortArrows[i].getAttribute('data-column');
                this.sortingOrder = sortArrows[i].getAttribute('data-order');

                this.sendApiRequest(this.getItemsPerPage(), Cookie.get(this.currentPageCookie));
            })
        }
    }
}
export default Table;