
class TableLoader {
    static loaderId = 'dataTableLoader';

    /**
     * Return requestTimeout variable
     * @param table
     * @param loader
     * @param timeout
     * @returns {number}
     */
    static show(
        table,
        loader = null,
        timeout = 800
    ) {
        let _loader = loader ?? TableLoader.loaderId;
        return setTimeout(() => {
            // Hide the table and show the loader
            $(`#${table}`).hide();
            $(`#${_loader}`).show();
        }, timeout);
    }

    /**
     *
     * @param table
     * @param requestTimeout
     * @param loader
     */
    static hide(
        table,
        requestTimeout,
        loader,
    ) {
        let _loader = loader ?? TableLoader.loaderId;
        clearTimeout(requestTimeout);
        $(`#${_loader}`).hide();
        $(`#${table}`).show();
    }
}
export default TableLoader;