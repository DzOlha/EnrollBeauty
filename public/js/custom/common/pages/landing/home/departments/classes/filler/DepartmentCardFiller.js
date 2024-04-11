import Notifier from "../../../../../classes/notifier/Notifier.js";

class DepartmentCardFiller
{
    constructor(requester, builder, apiGetDepartments) {
        this.limit = 6;
        this.requester = requester;
        this.builder = builder;
        this.apiGetDepartments = apiGetDepartments;
    }
    init() {
        this.getDepartments();
    }
    getDepartments() {
        this.requester.get(
            `${this.apiGetDepartments}?limit=${this.limit}`,
            this.successCallbackGet.bind(this),
            this.errorCallbackGet.bind(this)
        )
    }

    successCallbackGet(response) {
        this._populateCards(response.data);
    }

    errorCallbackGet(response) {
        Notifier.showErrorMessage(response.error);
    }

    /**
     *
     * @param data = {
     *     0: {
     *         id:
     *         name:
     *         photo_filename:
     *     }
     *     ...................
     * }
     * @private
     */
    _populateCards(data) {
        let parent = this.builder.getParent();
        if(!parent) return;

        for(const key in data) {
            /**
             * {id: , name: , photo_filename:}
             * @type {string | undefined}
             */
            let department = data[key];

            parent.insertAdjacentHTML(
                'beforeend',
                this.builder.createDepartmentCard(department)
            );
        }
    }
}
export default DepartmentCardFiller;