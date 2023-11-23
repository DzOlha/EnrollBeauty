
$(function () {
    let admin = new AdminAccount(
        new Requestor, new WorkersTable(), null
    );
    admin.getUserInfo();
    admin.getWorkers();
});