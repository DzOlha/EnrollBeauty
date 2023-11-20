$(function () {
    let userInfo = new Account(
        new Requestor, new AppointmentsTable,
        new SearchScheduleForm(
            new ScheduleRenderer()
        )
    );
    userInfo.getUserInfo();
    userInfo.getComingAppointments();

    userInfo.getAllSelectInfo();
    userInfo.addListenerChangeServiceName();
    //userInfo.addListenerChangeWorkerName();

    userInfo.searchSchedule();
    userInfo.searchScheduleForm.handleFormSubmission();
});