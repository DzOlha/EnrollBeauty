import Notifier from "../../../../../classes/notifier/Notifier.js";

class WorkerCardFiller
{
    constructor(requester, builder, apiGetWorkers) {
        this.limit = 3;
        this.requester = requester;
        this.builder = builder;
        this.apiGetWorkers = apiGetWorkers;
    }

    init() {
        this.getWorkers();
    }
    getWorkers() {
        this.requester.get(
            `${this.apiGetWorkers}?limit=${this.limit}`,
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
     *         surname:
     *         filename:
     *         position:
     *     }
     *     ...................
     * }
     * @private
     */
    _populateCards(data) {
        let leftParent = this.builder.getParentForTwoLeftCards();
        if(!leftParent) return;

        let rightParent = this.builder.getParentForOneRightCard();
        if(!rightParent) return;

        let bottomLeftCard = false;

        let leftCompleted = false;

        /**
         * Top left card -> bottom left card -> right centered card
         */
        for(const key in data) {
            let worker = data[key];

            if(leftCompleted === true) {
                /**
                 * Populate the last card placed at right side
                 */
                rightParent.insertAdjacentHTML(
                    'beforeend',
                    this.builder.createWorkerCard(worker, true)
                );
                break;
            }

            if(bottomLeftCard === true){
                /**
                 * Populate the bottom left card
                 */
                leftParent.insertAdjacentHTML(
                    'beforeend',
                    this.builder.createWorkerCard(worker, true)
                );
                leftCompleted = true;
            } else {
                /**
                 * Populate the top left card
                 */
                leftParent.insertAdjacentHTML(
                    'beforeend',
                    this.builder.createWorkerCard(worker)
                );
                bottomLeftCard = true;
            }
        }
    }
}
export default WorkerCardFiller;