
$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    /**
     * Fill the admin info
     */
    admin.getUserInfo();
});