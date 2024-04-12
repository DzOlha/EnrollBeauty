import DeleteModal from "../../../../../../classes/modal/DeleteModal.js";

class DeletePositionForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-position-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeletePositionForm;