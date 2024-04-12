import Notifier from "../../../../../../../classes/notifier/Notifier.js";
import CONST from "../../../../../../../config/contants/constants.js";
import Input from "../../../../../../../classes/element/Input.js";


class EditWorkerSocialNetworks
{
    constructor(requester, apiGetWorkerId, apiGetSocial, apiSubmit) {
        this.requester = requester;
        this.submitActionUrl = apiSubmit;

        this.formTabId = 'socialNetworks';
        this.formShowTriggerId = 'socialNetworks-trigger';

        this.apiGetWorkerSocial = apiGetSocial;

        this.submitBtnId = 'edit-worker-social-submit';

        this.instagramId = 'instagram-input';
        this.tikTokId = 'tikTok-input';
        this.linkedInId = 'linkedIn-input';
        this.facebookId = 'facebook-input';
        this.githubId = 'github-input';
        this.telegramId = 'telegram-input';
        this.youTubeId = 'youTube-input';

        this.apiGetWorkerId = apiGetWorkerId;

        this.listenerDataAttribute = 'data-listener';
        this.dataIdAttribute = 'data-social-id';
    }

    init() {
        this.addListenerOnClickTab();
        this.addListenerSubmitForm();
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
                this.getWorkerSocialNetworks(response.data.id);
            },
            (response) => {
                Notifier.showErrorMessage(response.error);
            }
        )
    }

    getWorkerSocialNetworks(id) {
        this.requester.get(
            `${this.apiGetWorkerSocial}?id=${id}`,
            this.successCallbackGetWorkerSocial.bind(this),
            this.errorCallbackGetWorkerSocial.bind(this)
        )
    }

    successCallbackGetWorkerSocial(response) {
        this._populateForm(response.data);
    }

    errorCallbackGetWorkerSocial(response) {
        Notifier.showErrorMessage(response.error);
    }

    _populateForm(data) {
        if(!data) return;

        /**
         * Set the identifier of the social networks row
         * of the requested worker on the submit button
         * @type {HTMLElement}
         */
        let submit = document.getElementById(this.submitBtnId);
        submit.setAttribute(this.dataIdAttribute, data.id);

        /**
         * Instagram
         */
        $(`#${this.instagramId}`).val(
            (data.Instagram !== null && data.Instagram !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Instagram + data.Instagram
                : ''
        );
        this.addListenerInputEnd(this.instagramId);

        /**
         * TikTok
         */
        $(`#${this.tikTokId}`).val(
            (data.TikTok !== null && data.TikTok !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.TikTok + data.TikTok
                : ''
        );
        this.addListenerInputEnd(this.tikTokId);

        /**
         * LinkedIn
         */
        $(`#${this.linkedInId}`).val(
            (data.LinkedIn !== null && data.LinkedIn !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.LinkedIn + data.LinkedIn
                : ''
        );
        this.addListenerInputEnd(this.linkedInId);

        /**
         * Facebook
         */
        $(`#${this.facebookId}`).val(
            (data.Facebook !== null && data.Facebook !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Facebook + data.Facebook
                : ''
        );
        this.addListenerInputEnd(this.facebookId);

        /**
         * Github
         */
        $(`#${this.githubId}`).val(
            (data.Github !== null && data.Github !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Github + data.Github
                : ''
        );
        this.addListenerInputEnd(this.githubId);

        /**
         * Telegram
         */
        $(`#${this.telegramId}`).val(
            (data.Telegram !== null && data.Telegram !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Telegram + data.Telegram
                : ''
        );
        this.addListenerInputEnd(this.telegramId);

        /**
         * YouTube
         */
        $(`#${this.youTubeId}`).val(
            (data.YouTube !== null && data.YouTube !== '')
                ? CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.YouTube + data.YouTube
                : ''
        );
        this.addListenerInputEnd(this.youTubeId);
    }

    addListenerInputEnd(inputId)
    {
        let input = document.getElementById(inputId);
        if(!input) return;
        this.setSocialIconLink(inputId, input);

        let listener = input.getAttribute(this.listenerDataAttribute);
        if(!listener) {
            input.setAttribute(this.listenerDataAttribute, true);
            input.addEventListener('change', () => {
               this.setSocialIconLink(inputId, input);
            })
        }
    }

    setSocialIconLink(inputId, input) {
        let icon = document.getElementById(`icon-${inputId}`);
        if(!icon) return;
        //console.log(input.value);

        let validLink = this.validateSocialNetworkLink(inputId);
        //console.log(validLink);

        if(input.value && validLink) {
            $(`#${inputId}`).addClass('active');
            icon.classList.add('active');
            icon.setAttribute('href', input.value);
        } else {
            $(`#${inputId}`).removeClass('active');
            icon.classList.remove('active');
        }
    }

    validateSocialNetworkLink(singleInputId) {
        let input = Input.validateInput(
            singleInputId,
            'value',
            (value) => {
                let socialName = $(`#${singleInputId}`).prop('name');
                return this.validationSocialProfileCallback(value, socialName)
            })

        if(input) {
            return input.value;
        }
        return false;
    }

    validationSocialProfileCallback = (value, socialName) => {
        let result = {}

        if(!value) {
            return result;
        }
        let pattern = CONST.WORKER_SOCIAL_NETWORKS_URLS_REGEX[socialName];

        if(!pattern.test(value)) {
            result.error = `Invalid url provided for ${socialName} profile!`;
            return result;
        }

        return result;
    }

    validateFormData() {
        let instagram = this.validateSocialNetworkLink(this.instagramId);
        let facebook = this.validateSocialNetworkLink(this.facebookId);
        let tiktok = this.validateSocialNetworkLink(this.tikTokId);
        let youtube = this.validateSocialNetworkLink(this.youTubeId);
        let linkedin = this.validateSocialNetworkLink(this.linkedInId);
        let github = this.validateSocialNetworkLink(this.githubId);
        let telegram = this.validateSocialNetworkLink(this.telegramId);

        if(instagram !== false && facebook !== false && tiktok !== false && youtube !== false
            && linkedin !== false && github !== false && telegram !== false)
        {
            return {
                'Instagram': instagram,
                'Facebook': facebook,
                'TikTok': tiktok,
                'YouTube': youtube,
                'LinkedIn': linkedin,
                'Github': github,
                'Telegram': telegram
            }
        }
        return false;
    }

    addListenerSubmitForm()
    {
        let submit = document.getElementById(this.submitBtnId);
        let listener = submit.getAttribute(this.listenerDataAttribute);
        if(!listener) {
            submit.setAttribute(this.listenerDataAttribute, true);
            submit.addEventListener('click', this.listenerSubmitForm)
        }
    }

    listenerSubmitForm = (e) => {
        let data = this.validateFormData();

        if(data) {
            data.id = e.currentTarget.getAttribute(this.dataIdAttribute);
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }
    successCallbackSubmit(response) {
        Notifier.showSuccessMessage(response.success);
    }
}
export default EditWorkerSocialNetworks;