import WorkerPublicProfileBuilder from "../../../../../../classes/builder/WorkerPublicProfileBuilder.js";


class WorkerPublicProfile
{
    constructor(requester, apiGetWorkerPublicProfile) {
        this.requester = requester;

        this.titleBlockId = 'profile-title-block';
        this.detailBlockId = 'profile-detail-block';
        this.descriptionBlockId = 'profile-description-block';

        this.apiGetWorkerPublicProfile = apiGetWorkerPublicProfile;
    }

    init() {
        this.getProfile();
    }
    getProfile() {
        let id = this._getWorkerId();
        this.requester.get(
            `${this.apiGetWorkerPublicProfile}?id=${id}`,
            this.successCallbackProfile.bind(this),
            this.errorCallbackProfile.bind(this)
        )
    }

    successCallbackProfile(response) {
        this.populateProfilePage(response.data);
    }

    errorCallbackProfile(response) {
        Notifier.showErrorMessage(response.error);
    }

    populateProfilePage(data) {
        let parentTitleBlock = document.getElementById(this.titleBlockId);
        let parentDetailBlock = document.getElementById(this.detailBlockId);
        let parentAboutBlock = document.getElementById(this.descriptionBlockId);

        parentTitleBlock.insertAdjacentHTML(
            'afterbegin', WorkerPublicProfileBuilder.createProfileTitleBlock(data)
        );

        parentDetailBlock.insertAdjacentHTML(
            'afterbegin', WorkerPublicProfileBuilder.createProfileDetailBlock(data)
        );

        parentAboutBlock.insertAdjacentHTML(
            'afterbegin', WorkerPublicProfileBuilder.createDescriptionBlock(data)
        );
    }

    _getWorkerId() {
        /**
         * Get the current url in the format
         *
         * http://enrollbeauty/web/open/worker/profile/olha-dziuhal-61
         *                                             name-surname-id
         */
        const url = window.location.href;
        const parts = url.split('/');

        // Get the last part of the URL
        const NameSurnameId = parts[parts.length - 1];

        const arr = NameSurnameId.split('-');

        return arr[2];
    }
}
export default WorkerPublicProfile;