
$(function () {
    let userInfo = new Account(
        new Requestor, new AppointmentsTable, new SearchScheduleForm
    );
    userInfo.getUserInfo();
    userInfo.getComingAppointments();

    userInfo.getAllSelectInfo();
    userInfo.addListenerChangeServiceName();
    //userInfo.addListenerChangeWorkerName();

    userInfo.searchSchedule();
});