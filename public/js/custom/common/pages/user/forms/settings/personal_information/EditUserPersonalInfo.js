import CONST from "../../../../../../constants.js";
import Notifier from "../../../../classes/notifier/Notifier.js";
import Input from "../../../../classes/element/Input.js";
import AjaxImageFiller from "../../../../classes/helper/AjaxImageFiller.js";
import ImageDropify from "../../../../classes/element/ImageDropify.js";
import User from "../../../profile/User.js";

class EditUserPersonalInfo
{
    constructor(requester, apiGetObjectId, apiGetObject, apiSubmitUrl) {
        this.requester = requester;

        this.apiSubmitUrl = apiSubmitUrl;
        this.apiGetObject = apiGetObject;
        this.apiGetObjectId = apiGetObjectId;

        this.formTabId = 'personalInformation';
        this.formShowTriggerId = 'personalInformation-trigger';

        this.mainPhotoInputId = 'main-photo-input';
        this.nameInputId = 'name-input';
        this.surnameInputId = 'surname-input';
        this.emailInputId = 'email-input';

        this.submitBtnId = 'edit-user-details-submit';
        this.listenerDataAttribute = 'data-listener-set';

        this.dataIdAttribute = 'data-user-id';

        this.inputFileWrapperSelector = `#${this.formTabId} .dropify-wrapper`;
        this.imgPath = CONST.userImgFolder;
    }

    init() {
        this.getUserId();
        this.addListenerOnClickTab();
        this.addListenerSubmitForm();
    }

    addListenerOnClickTab() {
        let trigger = document.getElementById(this.formShowTriggerId);
        let listener = trigger.getAttribute(this.listenerDataAttribute);
        if(!listener) {
            trigger.addEventListener('click', () => {
                trigger.setAttribute(this.listenerDataAttribute, 'true');
                this.getUserId();
            })
        }
    }

    getUserId() {
        this.requester.get(
            this.apiGetObjectId,
            (response) => {
                this.getObjectData(response.data.id);
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }
    getObjectData(id) {
        this.requester.get(
            `${this.apiGetObject}?id=${id}`,
            this.successCallbackGetObject.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackGetObject(response) {
        this._populateForm(response.data);
    }

    _populateForm(data) {
        $(`#${this.nameInputId}`).val(data.name);
        $(`#${this.surnameInputId}`).val(data.surname);
        $(`#${this.emailInputId}`).val(data.email);

        /**
         * Set id of the user on the submit button
         * @type {HTMLElement}
         */
        let submit = document.getElementById(this.submitBtnId);
        submit.setAttribute(this.dataIdAttribute, data.id);


        /**
         * Populate main photo
         */
        let image = new ImageDropify(
            this.inputFileWrapperSelector, this.mainPhotoInputId, 'main'
        );
        let path = data.filename
                            ? `${this.imgPath}${data.id}/${data.filename}`
                            : CONST.noPhoto;

        image.set(this.mainPhotoInputId, path);
    }

    addListenerSubmitForm() {
        let btn = document.getElementById(this.submitBtnId);
        if(!btn.getAttribute(this.listenerDataAttribute)) {
            btn.addEventListener('click', this.handleSubmitForm);
            btn.setAttribute(this.listenerDataAttribute, 'true');
        }
    }

    handleSubmitForm = (e) => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     name:
         *     surname:
         *     email:
         *     photo:
         * }
         */
        let data = this.validateForm();

        if(data) {
            /**
             * Get the id of the current user that we are editing
             */
            data.append('id', e.currentTarget.getAttribute(this.dataIdAttribute));

            this.requester.postFiles(
                this.apiSubmitUrl,
                data,
                this.successCallbackSubmit.bind(this),
                this.errorCallbackSubmit.bind(this)
            )
        }
    }

    nameValidationCallback = (value) => {
        let result = {};

        if(!value) {
            result.error = "Name is the required field!";
            return result;
        }

        let pattern = /^[A-Za-zА-Яа-яіїІЇ]{3,}$/;
        if(!pattern.test(value)) {
            result.error = "Name must be at least 3 characters long and contain only letters"
        }

        return result;
    }

    surnameValidationCallback = (value) => {
        let result = {};

        if(!value) {
            result.error = "Surname is the required field!";
            return result;
        }

        let pattern = /^[A-Za-zА-Яа-яіїІЇ]{3,}$/;
        if(!pattern.test(value)) {
            result.error = "Surname must be at least 3 characters long and contain only letters"
        }

        return result;
    }

    emailValidationCallback = (value) => {
        let result = {};

        if(!value) {
            result.error = 'Email is the required field!';
            return result;
        }

        let pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        if(!pattern.test(value)) {
            result.error = 'Please enter an email address in the format myemail@mailservice.domain';
        }

        return result;
    }

    mainPhotoValidationCallback = (value) => {
        let image = new ImageDropify(
            this.inputFileWrapperSelector, this.mainPhotoInputId, 'main'
        );
        return image.validate(value);
    }

    validateForm()  {
        let name = Input.validateInput(
            this.nameInputId, 'name', this.nameValidationCallback
        );

        let surname = Input.validateInput(
            this.surnameInputId, 'surname', this.surnameValidationCallback
        );

        let email = Input.validateInput(
            this.emailInputId, 'email', this.emailValidationCallback
        );

        let mainPhoto = Input.validateInput(
            this.mainPhotoInputId, 'photo', this.mainPhotoValidationCallback, true
        )

        if(name && surname && email && mainPhoto) {
            let data = {
                ...name, ...surname, ...email, ...mainPhoto
            }
            return AjaxImageFiller._populateFormDataObject(data);
        }
    }

    successCallbackSubmit(response) {
        Notifier.showSuccessMessage(response.success);

        /**
         * Regenerate Name Surname + Photo of the user at the left menu sidebar
         */
        let user = new User(this.requester);
        user.getUserInfo();

        /**
         * Updated info in the current form
         */
        this.getObjectData(response.data.id);
    }

    errorCallbackSubmit(response) {
        Notifier.showErrorMessage(response.error);
    }

}
export default EditUserPersonalInfo;