import EditWorkerForm from "../../../../admin/forms/worker/EditWorkerForm.js";
import API from "../../../../api.js";
import WorkersTable from "../../../../classes/table/extends/WorkersTable.js";
import Notifier from "../../../../classes/notifier/Notifier.js";
import CONST from "../../../../../../constants.js";
import FormBuilder from "../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../classes/modal/FormModal.js";

class EditWorkerPersonalInfo extends EditWorkerForm
{
    constructor(requester, optionBuilder, submitApiUrl)
    {
        super(requester, new FormModal(new FormBuilder()), optionBuilder, new WorkersTable(requester));
        this.submitActionUrl = submitApiUrl;
        this.submitButtonId = 'edit-worker-details-submit';

        this.mainPhotoInputId = 'main-photo-input';
        this.descriptionTextareaId = 'description-textarea';

        this.apiGetWorker = API.WORKER.API.PROFILE['personal-info'].get;
        this.imgPath = CONST.workerImgFolder;
        this.apiGetWorkerId = API.WORKER.API.PROFILE.id;

        this.apiGetPositions = API.WORKER.API.POSITION.get.one;
        this.apiGetRoles = API.WORKER.API.ROLE.get.one;
    }

    setUpForm() {
        this._initForm();
        this._initGenderSelect2();
        this.getWorkerId();
    }

    _initGenderSelect2() {
        $(`#${this.genderSelectId}`).select2({
            placeholder: "Choose one",
            allowClear: true,
        });
    }

    getWorkerId() {
        this.requester.get(
            this.apiGetWorkerId,
            (response) => {
                this.getObjectData(response.data.id);
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }

    /**
     * @param data = {
     *     id:
     *     name:
     *     surname:
     *     email: [disabled]
     *     position_id: [disabled]
     *     role_id: [disabled]
     *     gender:
     *     age:
     *     years_of_experience: [disabled]
     *     salary: [disabled]
     *     filename:
     *     description:
     * }
     * @private
     */
    _populateForm(data) {
        super._populateForm(data);

        $(`#${this.emailInputId}`).attr('disabled', true);
        $(`#${this.positionSelectId}`).attr('disabled', true);
        $(`#${this.roleSelectId}`).attr('disabled', true);
        $(`#${this.salaryInputId}`).attr('disabled', true);
        $(`#${this.experienceInputId}`).attr('disabled', true);

        /**
         * Populate main photo
         */
        let path = data.filename ?
                          `${this.imgPath}${data.id}/${data.filename}`
                          : CONST.noPhoto;

        this._setDefaultImage(this.mainPhotoInputId, path);

        /**
         * Populate description
         */
        $(`#${this.descriptionTextareaId}`).val(data.description);
    }


    /**
     *
     * @param imageInputId
     * @param imagePath
     * @private
     *
     * set preview of the image, but not the value of input (type file)
     */
    _setDefaultImage(imageInputId, imagePath) {
        let imageInputDropify = $(`#${imageInputId}`);
        imageInputDropify.attr("data-height", 200);
        imageInputDropify.attr("data-default-file", imagePath);
        imageInputDropify.dropify();
    }
}
export default EditWorkerPersonalInfo;