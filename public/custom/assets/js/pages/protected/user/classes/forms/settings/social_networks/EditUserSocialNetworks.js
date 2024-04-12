import EditWorkerSocialNetworks
    from "../../../../../worker/classes/forms/settings/social_networks/EditWorkerSocialNetworks.js";

class EditUserSocialNetworks extends EditWorkerSocialNetworks
{
    constructor(requester, apiGetWorkerId, apiGetSocial, apiSubmit) {
        super(requester, apiGetWorkerId, apiGetSocial, apiSubmit);
        this.submitBtnId = 'edit-user-social-submit';
    }
    validateFormData() {
        let instagram = this.validateSocialNetworkLink(this.instagramId);
        let facebook = this.validateSocialNetworkLink(this.facebookId);
        let tiktok = this.validateSocialNetworkLink(this.tikTokId);
        let youtube = this.validateSocialNetworkLink(this.youTubeId);

        if(instagram !== false && facebook !== false
            && tiktok !== false && youtube !== false)
        {
            return {
                'Instagram': instagram,
                'Facebook': facebook,
                'TikTok': tiktok,
                'YouTube': youtube
            }
        }
        return false;
    }
}
export default EditUserSocialNetworks;