import FormModal from "../../../../../../../classes/modal/FormModal.js";
import Notifier from "../../../../../../../classes/notifier/Notifier.js";

class ViewWorkersModal extends FormModal {
    constructor(
        requester, formBuilder, apiGetWorkers, dataIdAttribute, dataId
    ) {
        super(formBuilder);
        this.requester = requester;
        this.btnTriggerId = 'show-extra-modal';
        this.limit = 4;
        this.pageWorker = 1;

        this.tabId = 'workers';
        this.tabName = 'Workers';
        this.dataIdAttribute = dataIdAttribute;
        this.dataId = dataId

        this.uploadMoreWorkersId = `upload-more-workers`;

        this.uploadWorkersParentId = `workers-wrapper`;

        this.apiGetWorkers = apiGetWorkers;
    }
    setSelectors() {
        this.modalId = `modalMentions`;
        this.modalHeadlineId = `${this.modalId}-headline`;
        this.modalContentId = `${this.modalId}-content`;
        this.modalCloseId = `${this.modalId}-close`;
        this.modalSubmitId = `${this.modalId}-submit`;
    }
    addListenerOnClose() {
        let btn = document.getElementById(this.modalCloseId);
        btn.addEventListener('click' , () => {
            this.pageWorker = 1;
        })
    }
    addListenerShow(id) {
        let modal = document.getElementById(
            `${this.btnTriggerId}-${id}`
        );
        modal.addEventListener('click', this.handleShowModal);
    }
    handleShowModal = (e) => {
        this.setSelectors();
        let id = e.currentTarget.getAttribute(this.dataIdAttribute);
        this.show(
            'Informational Modal',
            this.formBuilder.createContentWrapper(this.tabId, this.tabName),
            ''
        );
        this.getWorkers(id, this.pageWorker);
        this.addEventListenerOnUploadMore(
            id, this.uploadMoreWorkersId, this.getWorkers
        );
        this.close();
        this.addListenerOnClose();
    }
    getWorkers(id, page) {
        this.requester.get(
            `${this.apiGetWorkers}?${this.dataId}=${id}&limit=${this.limit}&page=${page}`,
            this.successCallbackWorkers.bind(this),
            (message) => {
                page = 1;
                Notifier.showErrorMessage(message)
            }
        )
    }
    successCallbackWorkers(response) {
        this.populateUploadMore(
            this.uploadWorkersParentId, this.uploadMoreWorkersId,
            response.data, this.pageWorker === 1,
            response.data[this.dataId],
            this.getWorkers.bind(this),
            this.pageWorker
        );
    }

    addEventListenerOnUploadMore(id, uploadMoreId, callback, page) {
        let uploadMore = document.getElementById(uploadMoreId);
        uploadMore.addEventListener('click', () => {
            if(uploadMoreId === this.uploadMoreWorkersId) {
                this.pageWorker = page;
            }
            callback(id, page);
        });
    }

    populateUploadMore(
        parentId, uploadMoreId, data,
        isFirstUpload = true, id, callback,
        page
    ) {
        let parent = document.getElementById(parentId);
        /**
         * if we upload just the first portion of information
         */
        if(isFirstUpload === true) {
            parent.innerHTML = '';
        }
        //remove old upload more button
        let uploadMore = document.getElementById(uploadMoreId);
        if(uploadMore !== null) {
            uploadMore.remove();
        }

        let totalRowsCount = data['totalRowsCount'];
        let leftToUpload = totalRowsCount - (page*this.limit);

        if(leftToUpload > 0) {
            // insert new upload more button
            let toUpload = leftToUpload > this.limit ? this.limit : leftToUpload;
            parent.insertAdjacentHTML(
                'afterend',
                this.formBuilder.createUploadMore(
                    uploadMoreId
                )
            );
            page++;
            this.addEventListenerOnUploadMore(id, uploadMoreId, callback, page);
        }
        // console.log(data);
        for(const key in data) {
            let item = data[key];
            if(key === 'totalRowsCount') {
                totalRowsCount = data['totalRowsCount'];
                continue;
            }
            if(key === this.dataId) continue;

            let card = this.formBuilder.createWorkerItem(item);

            parent.insertAdjacentHTML('beforeend', card);
        }
    }

}
export default ViewWorkersModal;