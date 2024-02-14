import CONST from "../../../../../../constants.js";

class ModalBuilder {
    createModalForm(modalId) {
        return `<div class="modal modal-form" id="${modalId}">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content tx-size-sm" style="z-index: 20">
                            <div class="modal-body tx-center pd-y-20 pd-x-20"> 
                                <button class="close-modal float-end" id="${modalId}-close"
                                        type="button">
                                    <img src="${CONST.closeIcon}"/>
                                </button>
                
                                <h4 class="tx-semibold mg-b-20"
                                    id="${modalId}-headline">
                
                                </h4>
                                <div class="column" id="${modalId}-content">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `
    }
    createContentWrapper(tabId, tabName) {
        return `<div class="panel panel-primary tabs-style-2">
                    <div class=" tab-menu-heading">
                        <div class="tabs-menu1">
                          
                            <ul class="nav panel-tabs main-nav-line">
                                <li>
                                    <a href="#${tabId}" class="nav-link active mt-1" 
                                        data-bs-toggle="tab" id="${tabId}Trigger">
                                        ${tabName}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body main-content-body-right border">
                        <div class="tab-content">
                        
                            <div class="tab-pane active" id="${tabId}">
                                <div class="mentions-wrapper-cards" id="${tabId}-wrapper">
                                   
                                </div>
                                <button aria-label="Upload more"
                                        class="btn ripple pd-x-25 upload-more"
                                        id="upload-more-${tabId}"
                                        type="button">
                                    Upload more
                                </button>
                            </div> 
                        </div>
                    </div>
                </div>`
    }

    /**
     *
     * @param item = {
     *     id:
     *     name:
     *     surname:
     *     filename:
     *     position:
     *     email:
     * }
     * @returns {string}
     */
    createWorkerItem(item) {
        let image = item.filename ? `${CONST.workerImgFolder}${item.id}/${item.filename}`
                           : CONST.noPhoto;

        return `<div class="worker-card" id="worker-card-${item.id}">
                        <div class="card overflow-hidden custom-card ">
                            <a href="${CONST.workerSystemProfile}?id=${item.id}" 
                                class="img-wrapper" target="_blank">
                                <img alt="Image" class="img-fluid b-img" src="${image}">
                            </a>
                            <div class="card-body">
                                <h5 class="main-content-label tx-dark tx-medium mg-b-10">
                                    <a href="${CONST.workerSystemProfile}?id=${item.id}" target="_blank">
                                            <div class="worker-card-name">${item.name} ${item.surname}</div>
                                    </a>
                                </h5>
                               
                                <div class="d-flex align-items-center mt-auto">
                                    <div> 
                                        <small class="d-block text-muted">${item.position}</small>
                                        <small class="d-block text-muted">${item.email}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>`
    }

    createUploadMore(buttonId) {
        return `<button aria-label="Upload more"
                        class="btn ripple pd-x-25 upload-more"
                        id="${buttonId}"
                        type="button">
                    Upload more
                </button>`
    }
}
export default ModalBuilder;