import DeleteModal from "../../../../../../classes/modal/DeleteModal.js";


class DeleteAffiliateForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-affiliate-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeleteAffiliateForm;