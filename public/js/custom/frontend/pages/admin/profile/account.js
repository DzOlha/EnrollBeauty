
$(function () {
    let admin = new AdminAccount(
        new Requestor, new AppointmentsTable,
        new SearchScheduleForm(
            new ScheduleRenderer()
        )
    );
    admin.getUserInfo();
});