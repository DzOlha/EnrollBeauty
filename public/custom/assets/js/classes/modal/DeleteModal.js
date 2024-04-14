import Notifier from "../../classes/notifier/Notifier.js";

class DeleteModal
{
    constructor(requester, apiUrl, formBuilder, dataAttributeId) {
        this.requester = requester;
        this.apiUrl = apiUrl;
        this.formBuilder = formBuilder;
        this.dataAttributeId = dataAttributeId;

        this.deleteBase = 'delete';
        this.menuWrapperId = 'modal-icons-menu';

        this.deleteSubmitId = 'delete-confirm-submit';
        this.deleteCancelId = 'delete-confirm-close';

        this.deleteModalId = 'delete-confirmation-block';

        this.closeModalSelector = '.modal-body > .close-modal';

        this.clickedClass = 'clicked';
    }

    addListenerDelete(id) {
        let btn = document.getElementById(
            `${this.deleteBase}-${id}`
        );
        btn.addEventListener('click', this.showConfirmationBlock)
    }
    showConfirmationBlock = (e) => {
        /**
         * Handle the second click on the delete icon
         */
        if(e.currentTarget.classList.contains(this.clickedClass)) {
            this.closeDeleteConfirmation();
            return;
        }

        /**
         * Mark icon as clicked
         */
        e.currentTarget.classList.add(this.clickedClass)

        let id = e.currentTarget.getAttribute(this.dataAttributeId);

        let parent = document.getElementById(this.menuWrapperId);

        let div = document.createElement('div');
        div.innerHTML = this.formBuilder.createDeleteConfirmationBlock(id);

        parent.append(div.firstChild);

        this.addListenerSubmit(id);
        this.addListenerCancel(id);
    }

    addListenerCancel(id) {
        let cancel = document.getElementById(
            `${this.deleteCancelId}-${id}`
        );
        cancel.addEventListener('click', () => {
            this.closeDeleteConfirmationBlock(id);
        })
    }
    closeDeleteConfirmationBlock(id) {
        let deletionModal = document.getElementById(
            `${this.deleteModalId}-${id}`
        );
        deletionModal.remove();

        let btn = document.getElementById(
            `${this.deleteBase}-${id}`
        );
        btn.classList.remove(this.clickedClass);
    }
    closeDeleteConfirmation() {
        let deletionModal = document.querySelector(
            `.${this.deleteModalId}`
        );
        deletionModal.remove();

        let btn = document.querySelector(
            `.modal-body .${this.deleteBase}`
        );
        btn.classList.remove(this.clickedClass);
    }
    addListenerSubmit(id) {
        let btn = document.getElementById(
            `${this.deleteSubmitId}-${id}`
        );
        btn.addEventListener('click', () => {
            this.requester.delete(
                this.apiUrl,
                {'id': id},
                (response) => {
                    /**
                     * Show Success Message
                     */
                    Notifier.showSuccessMessage(response.success);

                    /**
                     * Close the main modal (remove it)
                     */
                    this.closeModal();

                    /**
                     * Remove the deleted item from the frontend
                     */
                    this.removeDeletedItemFromFrontend(response.data.id)

                },
                (response) => {
                    /**
                     * Show error message
                     */
                    Notifier.showErrorMessage(response.error);

                    /**
                     * Close delete confirmation modal-block
                     */
                    this.closeDeleteConfirmation();
                }
            )
        })
    }
    closeModal() {
        $(`${this.closeModalSelector}`).click();
    }
    removeDeletedItemFromFrontend(id)
    {
        let row = document.querySelector(
            `tr[${this.dataAttributeId}='${id}']`
        );
        row.remove();
    }
}
export default DeleteModal;