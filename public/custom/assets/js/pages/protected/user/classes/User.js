import API from "../../../../config/api/api.js";
import CONST from "../../../../config/contants/constants.js";
import Notifier from "../../../../classes/notifier/Notifier.js";


class User {
    constructor(requester, getProfileApi = API.USER.API.PROFILE.get) {
        this.requestor = requester;

        this.apiUserInfoUrl = getProfileApi;

        this.smallNameId = 'name-small';
        this.smallSurnameId = 'surname-small';

        this.largeNameId = 'name-large';
        this.largeSurnameId = 'surname-large';

        this.smallUserImgId = 'user-img-small';
        this.largeUserImgId = 'user-img-large';

        this.imgPath = CONST.userImgFolder;

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
            response.data.name, response.data.surname + "\n" + this.roleName,
            response.data?.filename, response.data.id
        );
        this.populateLargeUserInfo(
            this.roleName + " " + response.data.name, response.data.surname,
            response.data?.filename, response.data.id
        );
    }

    populateSmallUserInfo(name, surname, filename, id) {
        let smallName = document.getElementById(this.smallNameId);
        if (smallName === null) return;
        smallName.innerText = name;

        let smallSurname = document.getElementById(this.smallSurnameId);
        if (smallSurname === null) return;
        smallSurname.innerText = surname;

        let smallUserImg = document.getElementById(this.smallUserImgId);
        if (smallUserImg === null || !filename) return;
        smallUserImg.setAttribute('src', this.imgPath + `${id}/` + filename);
    }

    populateLargeUserInfo(name, surname, filename, id) {
        let largeName = document.getElementById(this.largeNameId);
        if (largeName === null) return;
        largeName.innerText = name;

        let largeSurname = document.getElementById(this.largeSurnameId);
        if (largeSurname === null) return;
        largeSurname.innerText = surname;

        let largeUserImg = document.getElementById(this.largeUserImgId);
        if (largeUserImg === null || !filename) return;
        largeUserImg.setAttribute('src', this.imgPath + `${id}/` + filename);
    }

    errorCallbackUserInfo(response) {
        Notifier.showErrorMessage(response.error);
    }
}
export default User;