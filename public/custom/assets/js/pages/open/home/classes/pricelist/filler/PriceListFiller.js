import Notifier from "../../../../../../classes/notifier/Notifier.js";


class PriceListFiller
{
    constructor(requester, builder, apiGetPriceList) {
        this.requester = requester;
        this.builder = builder;
        this.apiGetPriceList = apiGetPriceList;
    }
    init() {
        this.getPriceList();
    }

    getPriceList() {
        this.requester.get(
            this.apiGetPriceList,
            this.successCallbackGet.bind(this),
            this.errorCallbackGet.bind(this)
        )
    }

    successCallbackGet(response) {
        this._populatePriceList(response.data);
    }

    errorCallbackGet(response) {
        Notifier.showErrorMessage(response.error);
    }

    /**
     * @param data
     * @private
     *
     * data = {
     *     departments: {
     *         0: {
     *             id:
     *             name:
     *             services: {
     *                  0: {
     *                      id:
     *                      name:
     *                      min_price:
     *                      currency:
     *                  }
     *             }
     *         },
     *         ...........
     *     },
     * }
     */
    _populatePriceList(data) {
        /**
         * First, populate the menu
         */
        let menuParent = this.builder.getMenuParent();
        if(!menuParent) return;

        let isActive = 'active';

        for(const key in data?.departments) {
            /**
             * {id: , name: }
             * @type {string | undefined}
             */
            let department = data?.departments[key];

            menuParent.insertAdjacentHTML(
                'beforeend',
                this.builder._createMenuLi(department, isActive)
            );

            this.builder.getMenuItem(department?.id).addEventListener(
                'click', (e) => {
                    e.preventDefault();
                    /**
                     * Menu
                     */
                    this.builder.getMenuActiveItem().classList.remove('active');
                    this.builder.getMenuItem(department?.id).classList.add('active');

                    /**
                     * Content
                     */
                    this.builder.getContentActiveItem().classList.remove('active');
                    this.builder.getContentItem(department?.id).classList.add('active');
                })

            /**
             * Populate the corresponding content tab outline
             */
            let contentWrapper = this.builder.getContentWrapper();
            if (!contentWrapper) continue;

            contentWrapper.insertAdjacentHTML(
                'beforeend',
                this.builder._createContentBlockOutline(department?.id, isActive)
            );

            isActive = '';


            /**
             * Second, populate the outline with data for each content tab
             */
            let firstColParent = this.builder.getItemsCol1Parent(department?.id);
            let secondColParent = this.builder.getItemsCol2Parent(department?.id);

            let toFirstCol = true;
            for (const i in department?.services) {
                let service = department?.services[i];

                if (toFirstCol) {
                    /**
                     * Fill the first column
                     */
                    firstColParent.insertAdjacentHTML(
                        'beforeend',
                        this.builder._createContentBlockItem(
                            department?.id, service
                        )
                    );
                    toFirstCol = false;
                } else {
                    /**
                     * Fill the second column
                     */
                    secondColParent.insertAdjacentHTML(
                        'beforeend',
                        this.builder._createContentBlockItem(
                            department?.id, service
                        )
                    );
                    toFirstCol = true;
                }
            }
        }
    }
}
export default PriceListFiller;