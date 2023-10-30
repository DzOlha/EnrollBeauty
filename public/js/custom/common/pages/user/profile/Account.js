class Account {
    constructor(requestorObject, appointmentsTable) {
        this.requestor = requestorObject;
        this.appointmentsTable = appointmentsTable;
        this.apiUserInfoUrl = '/api/user/getUserInfo';

        this.smallNameId = 'name-small';
        this.smallSurnameId = 'surname-small';

        this.largeNameId = 'name-large';
        this.largeSurnameId = 'surname-large';

        this.smallUserImgId = 'user-img-small';
        this.largeUserImgId = 'user-img-large';

        this.imgPath = '/public/images/custom/pages/user/';
    }

    getUserInfo() {
        this.requestor.get(
            this.apiUserInfoUrl,
            this.successCallbackUserInfo.bind(this),
            this.errorCallbackUserInfo.bind(this),
            this.errorCallbackUserInfo.bind(this)
        )
    }

    getComingAppointments() {
        this.appointmentsTable.manageAll();
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
            response.data.name, response.data.surname, response.data.filename
        );
        this.populateLargeUserInfo(
            response.data.name, response.data.surname, response.data.filename
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

    errorCallbackUserInfo(message) {
        Notifier.showErrorMessage(message);
    }
}