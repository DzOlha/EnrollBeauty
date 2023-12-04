
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    /**
     * Fill the admin info
     */
    worker.getUserInfo();
});