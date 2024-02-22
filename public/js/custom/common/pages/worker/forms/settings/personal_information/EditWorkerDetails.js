import EditWorkerForm from "../../../../admin/forms/worker/EditWorkerForm.js";
import API from "../../../../api.js";
import WorkersTable from "../../../../classes/table/extends/WorkersTable.js";

class EditWorkerDetails extends EditWorkerForm
{
    constructor(requester, modalForm, optionBuilder, submitApiUrl)
    {
        super(requester, modalForm, optionBuilder, new WorkersTable(requester));
        this.submitActionUrl = submitApiUrl;
        this.submitButtonId = 'edit-worker-details';
        this.apiGetWorker = API.WORKER.API.PROFILE.get['personal-info'];
    }
}