import DeleteModal from "../../../classes/modal/DeleteModal.js";

class DeleteServiceForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-service-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeleteServiceForm;