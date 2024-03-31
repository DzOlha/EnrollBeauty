import EditWorkerForm from "../../../../admin/forms/worker/EditWorkerForm.js";
import API from "../../../../api.js";
import WorkersTable from "../../../../classes/table/extends/WorkersTable.js";
import Notifier from "../../../../classes/notifier/Notifier.js";
import CONST from "../../../../../../constants.js";
import FormBuilder from "../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../classes/modal/FormModal.js";
import Input from "../../../../classes/element/Input.js";
import Worker from "../../../profile/Worker.js";
import Select2 from "../../../../classes/element/Select2.js";
import AjaxImageFiller from "../../../../classes/helper/AjaxImageFiller.js";

class EditWorkerPersonalInfo extends EditWorkerForm
{
    constructor(requester, optionBuilder, submitApiUrl)
    {
        super(requester, new FormModal(new FormBuilder()), optionBuilder, new WorkersTable(requester));
        this.submitActionUrl = submitApiUrl;

        this.formTabId = 'personalInformation';
        this.formShowTriggerId = 'personalInformation-trigger';
        this.submitButtonId = 'edit-worker-details-submit';

        this.mainPhotoInputId = 'main-photo-input';
        this.descriptionTextareaId = 'description-textarea';

        this.inputFileWrapperSelector = `#${this.formTabId} .dropify-wrapper`;
        this.allowedImageExtensions = ['jpg', 'svg', 'png', 'jpeg'];

        this.apiGetWorker = API.WORKER.API.PROFILE['personal-info'].get;
        this.imgPath = CONST.workerImgFolder;
        this.apiGetWorkerId = API.WORKER.API.PROFILE.id;

        this.apiGetPositions = API.WORKER.API.POSITION.get.one;
        this.apiGetRoles = API.WORKER.API.ROLE.get.one;

        this.listenerDataAttribute = 'data-listener';
    }

    setUpForm() {
        this._initForm();
        this._initGenderSelect2();
        this.getWorkerId();
        this.addListenerOnClickTab();
        this.addListenerSubmitForm();
    }

    _initGenderSelect2() {
        $(`#${this.genderSelectId}`).select2({
            placeholder: "Choose one",
            allowClear: true,
        });
    }

    addListenerOnClickTab() {
        let trigger = document.getElementById(this.formShowTriggerId);
        let listener = trigger.getAttribute(this.listenerDataAttribute);
        if(!listener) {
            trigger.addEventListener('click', () => {
                trigger.setAttribute(this.listenerDataAttribute, true);
                this.getWorkerId();
            })
        }
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

        /**
         * Set id of the worker on the submit button
         * @type {HTMLElement}
         */
        let submit = document.getElementById(this.submitButtonId);
        submit.setAttribute(this.dataIdAttribute, data.id);

        /**
         * Disable parameters that the worker can not edit
         */
        $(`#${this.emailInputId}`).attr('disabled', true);
        $(`#${this.positionSelectId}`).attr('disabled', true);
        $(`#${this.roleSelectId}`).attr('disabled', true);
        $(`#${this.salaryInputId}`).attr('disabled', true);

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

    _setSelect2(selectId, value, placeholder) {
        Select2._setSelect2(selectId, value, placeholder);
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
        let imageInputDropify = $(`#${imageInputId}`).dropify(
            {
                defaultFile: imagePath
            });
        imageInputDropify = imageInputDropify.data('dropify');
        imageInputDropify.resetPreview();
        imageInputDropify.clearElement();
        imageInputDropify.settings.defaultFile = imagePath;
        imageInputDropify.destroy();
        imageInputDropify.init();
    }

    addListenerSubmitForm() {
        let submit = document.getElementById(this.submitButtonId);
        let listener = submit.getAttribute(this.listenerDataAttribute);
        if(!listener) {
            submit.addEventListener('click', (e) => {
                submit.setAttribute(this.listenerDataAttribute, true);

                this.listenerSubmitForm(e);
            })
        }
    }

    listenerSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     name:
         *     surname:
         *     email:
         *     position_id: [delete]
         *     role_id: [delete]
         *     gender:
         *     age:
         *     experience:
         *     salary: [delete]
         *     description:
         *     photo:
         * }
         */
        let data = this.validateInputs();

        if(data) {
            /**
             * Get the id of the current worker that we are editing
             */
            data.append('id', e.currentTarget.getAttribute(this.dataIdAttribute));

            //console.log(data);
            //this.requestTimeout = GifLoader.showBeforeBegin(e.currentTarget);
            this.requester.postFiles(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    //GifLoader.hide(this.requestTimeout);
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }

    descriptionValidationCallback = (value) => {
        let result = {}

        if(!value) {
            return result;
        }

        if(value.length <= 10) {
            result.error = 'The description field should be longer than 10 characters!';
            return result;
        }
        return result;
    }

    mainPhotoValidationCallback = (value) => {
        let result = {};

        let wrapper = document.querySelector(this.inputFileWrapperSelector)
        if(!value) {
            return result;
        }

        const fileName = value.name;
        const fileExtension = fileName.split('.').pop();

        if (!this.allowedImageExtensions.includes(fileExtension)) {
            wrapper.classList.add('error');
            result.error = "Your main photo should be one of the following format: .jpg, .jpeg, .png, .svg"
            return result;
        }
        if(wrapper.classList.contains('error')) {
            wrapper.classList.remove('error');
        }
        return result;
    }

    validateInputs() {
        /**
         * Validation:
         * {
         *     name:
         *     surname:
         *     email:
         *     position_id: [to delete]
         *     role_id: [to delete]
         *     gender:
         *     age:
         *     experience:
         *     salary: [to delete]
         * }
         */
        let data = super.validateInputs();

        /**
         * Validation of the description of the worker
         */
        let description = Input.validateInput(
            this.descriptionTextareaId, 'description', this.descriptionValidationCallback
        );

        /**
         * Photo validation
         * @type {{}|boolean}
         */
        let mainPhoto = Input.validateInput(
            this.mainPhotoInputId, 'photo', this.mainPhotoValidationCallback, true
        )

        /**
         * If any errors occurred -> return false
         */
        if(!data || description === false || mainPhoto === false) {
            return false;
        }

        /**
         * Remove from data all fields that the worker do not have right to change
         */
        delete data.position_id;
        delete data.role_id;
        delete data.salary;

        /**
         * {
         *     name:
         *     surname:
         *     email:
         *     gender:
         *     age:
         *     experience:
         *     description:
         *     photo:
         * }
         * @type {{valueOf: ((() => boolean)|*)}}
         */
        let toReturn =  {
            ...data, ...description, ...mainPhoto
        }

        return AjaxImageFiller._populateFormDataObject(toReturn);
    }

    successCallbackSubmit(response) {
        Notifier.showSuccessMessage(response.success);

        /**
         * Regenerate Name Surname + Photo of the user at the left menu sidebar
         */
        let worker = new Worker(this.requester);
        worker.getUserInfo();
    }
}
export default EditWorkerPersonalInfo;