$(function () {
    let requester = new Requester();
    let user = new User(requester);


    /**
     * Get the user id for whom display the profile
     * @type {string}
     */
    let urlParams = new URLSearchParams(window.location.search);
    let userId = urlParams.get('user_id');
    if(userId !== null) {
        user.setApiUserInfoUrl(userId);
    }

    /**
     * Fill user info
     */
    user.getUserInfo();
});