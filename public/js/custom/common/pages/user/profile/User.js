
import Notifier from "../../classes/notifier/Notifier.js";
import API from "../../../../common/pages/api.js";
class User {
    constructor(requester) {
        this.requestor = requester;

        this.apiUserInfoUrl = API.USER.API.PROFILE.get;

        this.smallNameId = 'name-small';
        this.smallSurnameId = 'surname-small';

        this.largeNameId = 'name-large';
        this.largeSurnameId = 'surname-large';

        this.smallUserImgId = 'user-img-small';
        this.largeUserImgId = 'user-img-large';

        this.imgPath = '/public/images/custom/pages/user/';

        this.roleName = '[User]';
    }

    setApiUserInfoUrl(userId) {
        this.apiUserInfoUrl = `${API.USER.API.PROFILE.get}?user_id=${userId}`;
    }
    getUserInfo() {
        this.requestor.get(
            this.apiUserInfoUrl,
            this.successCallbackUserInfo.bind(this),
            this.errorCallbackUserInfo.bind(this)
        )
    }

    /**
     * @param response
     *
     * response =
     * 'data': {
     *      'id':
     *      'name':
     *      'surname':
     *      'email':
     *      'filename':
     * }
     */
    successCallbackUserInfo(response) {
        this.populateSmallUserInfo(
            response.data.name, response.data.surname + "\n" + this.roleName, response.data?.filename
        );
        this.populateLargeUserInfo(
            this.roleName + " " + response.data.name, response.data.surname, response.data?.filename
        );
    }

    populateSmallUserInfo(name, surname, filename) {
        let smallName = document.getElementById(this.smallNameId);
        if (smallName === null) return;
        smallName.innerText = name;

        let smallSurname = document.getElementById(this.smallSurnameId);
        if (smallSurname === null) return;
        smallSurname.innerText = surname;

        let smallUserImg = document.getElementById(this.smallUserImgId);
        if (smallUserImg === null || !filename) return;
        smallUserImg.setAttribute('src', this.imgPath + filename);
    }

    populateLargeUserInfo(name, surname, filename) {
        let largeName = document.getElementById(this.largeNameId);
        if (largeName === null) return;
        largeName.innerText = name;

        let largeSurname = document.getElementById(this.largeSurnameId);
        if (largeSurname === null) return;
        largeSurname.innerText = surname;

        let largeUserImg = document.getElementById(this.largeUserImgId);
        if (largeUserImg === null || !filename) return;
        largeUserImg.setAttribute('src', this.imgPath + filename);
    }

    errorCallbackUserInfo(response) {
        Notifier.showErrorMessage(response.error);
    }
}
export default User;