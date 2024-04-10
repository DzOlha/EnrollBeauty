
class PricelistBuilder
{
    constructor() {
        this.menuWrapperId = 'pricelist-menu-ul';
        this.contentWrapperId = 'pricelist-content-wrapper';
        this.menuTabClass = 'tab';
        this.activeClass = 'active';
    }

    _createMenuLi(department, active = '') {
        return `<li class="${this.menuTabClass} pricing-tab ${active}" id="menu-pricing-item-${department.id}">
                    <a href="#pricelist-content-block-${department.id}">
                        ${department.name}
                    </a>
                </li>`
    }

    getItemsCol1Parent(departmentId) {
        return document.getElementById(`pricelist-content-block-${departmentId}-col-1`);
    }

    getItemsCol2Parent(departmentId) {
        return document.getElementById(`pricelist-content-block-${departmentId}-col-2`);
    }

    getMenuParent() {
        return document.getElementById(this.menuWrapperId);
    }

    getMenuItem(departmentId) {
        return document.getElementById(`menu-pricing-item-${departmentId}`);
    }

    getMenuActiveItem() {
        return document.querySelector(`.${this.menuTabClass}.pricing-tab.active`);
    }
    getContentActiveItem() {
        return document.querySelector(`.content-inner.pricing-content.active`);
    }

    getContentItem(departmentId) {
        return document.getElementById(`pricelist-content-block-${departmentId}`);
    }

    getContentWrapper() {
        return document.getElementById(this.contentWrapperId);
    }

    _createContentBlockOutline(departmentId, active = '') {
        return `<div class="content-inner pricing-content ${active}" id="pricelist-content-block-${departmentId}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="">
                                <div class="ttm-pricelist-block-wrapper">
                                    <ul class="ttm-pricelist-block p-0" 
                                        id="pricelist-content-block-${departmentId}-col-1">
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                    </ul> <!-- .tm-pricelist-block -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <div class="ttm-pricelist-block-wrapper">
                                    <ul class="ttm-pricelist-block p-0" 
                                        id="pricelist-content-block-${departmentId}-col-2">
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                        <!-- _createContentBlockItem -->
                                    </ul> <!-- .tm-pricelist-block -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
    }

    _createContentBlockItem(
        departmentId, service,
    ) {
        return `<li id="pricelist-content-item-${departmentId}-${service?.id}"
                    data-service-id="${service?.id}">
                    <h3>${service?.name}</h3>
                    <p></p>
                    <span class="ttm-textcolor-skincolor service-price">
                        From ${service?.min_price + ' ' + service?.currency}
                    </span>
                </li>`
    }
}
export default PricelistBuilder;