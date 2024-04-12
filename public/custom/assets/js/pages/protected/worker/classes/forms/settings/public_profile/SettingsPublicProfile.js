import UrlRenderer from "../../../../../../../classes/renderer/extends/UrlRenderer.js";
import Notifier from "../../../../../../../classes/notifier/Notifier.js";


class SettingsPublicProfile
{
    constructor(requester, apiGetCurrentWorker)
    {
        this.requester = requester;
        this.triggerId = 'show-public-profile-button';

        this.listenerAttr = 'data-listener';

        this.apiGetCurrentWorker = apiGetCurrentWorker;
    }
    init() {
        this.addListenerClickTrigger();
    }
    addListenerClickTrigger() {
        let btn = document.getElementById(this.triggerId);
        if(!btn.getAttribute(this.listenerAttr)) {
            btn.addEventListener('click', this.handleShowPublicProfile);
            btn.setAttribute(this.listenerAttr, 'true');
        }
    }
    handleShowPublicProfile = (e) => {
        e.preventDefault();

        this.requester.get(
            this.apiGetCurrentWorker,
            (response) => {
                let name = response.data.name.toLowerCase();
                let surname = response.data.surname.toLowerCase();
                let id = response.data.id;

                let profilePageUrl = UrlRenderer.renderWorkerPublicProfileUrl(
                    name, surname, id
                );
                window.open(profilePageUrl, '_blank');
            },
            (response) => {
                Notifier.showErrorMessage(response.error)
            }
        )
    }
}
export default SettingsPublicProfile;