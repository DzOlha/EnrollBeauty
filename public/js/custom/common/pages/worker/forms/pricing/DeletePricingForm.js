import DeleteModal from "../../../classes/modal/DeleteModal.js";

class DeletePricingForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-pricing-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeletePricingForm;