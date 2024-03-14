import API from "../../../../api.js";
import Notifier from "../../../../classes/notifier/Notifier.js";

class SettingsPublicProfile
{
    constructor(requester)
    {
        this.requester = requester;
        this.profileRootPage = API.OPEN.WEB.WORKER.profile;
        this.triggerId = 'show-public-profile-button';

        this.listenerAttr = 'data-listener';

        this.apiGetCurrentWorker = API.WORKER.API.PROFILE.get
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

                let profilePageUrl = `${this.profileRootPage}/${name}-${surname}-${id}`;
                window.open(profilePageUrl, '_blank');
            },
            (response) => {
                Notifier.showErrorMessage(response.error)
            }
        )
    }
}
export default SettingsPublicProfile;