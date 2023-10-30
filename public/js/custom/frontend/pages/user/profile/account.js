
$(function () {
    let userInfo = new Account(
        new Requestor, new AppointmentsTable
    );
    userInfo.getUserInfo();
    userInfo.getComingAppointments();
});