import DeleteModal from "../../../../../../classes/modal/DeleteModal.js";

class DeleteDepartmentForm
{
    constructor(requester, apiUrl, formBuilder)
    {
        this.deleteModal = new DeleteModal(
            requester, apiUrl, formBuilder, 'data-department-id'
        );
    }

    addListenerDelete(id) {
        this.deleteModal.addListenerDelete(id);
    }
}
export default DeleteDepartmentForm;