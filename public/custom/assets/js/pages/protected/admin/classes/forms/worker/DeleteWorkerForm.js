import DeleteModal from "../../../../../../classes/modal/DeleteModal.js";

class DeleteWorkerForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-worker-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeleteWorkerForm;