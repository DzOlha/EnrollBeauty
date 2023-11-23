$(function () {
    let requester = new Requester();
    let user = new User(requester);

    let confirmationModal = new ConfirmationModal();
    let appointmentsTable = new AppointmentsTable(
        requester,
        confirmationModal
    );

    let searchScheduleForm = new SearchScheduleForm(
        new ScheduleRenderer(
            requester,
            new ScheduleHtmlBuilder(),
            appointmentsTable,
            confirmationModal
        ),
        requester
    );

    /**
     * Fill user info
     */
    user.getUserInfo();

    /**
     * Populate upcoming appointments table
     */
    appointmentsTable.POPULATE();

    /**
     * Get information for select elements of the form of searching
     * available schedules for appointments
     */
    searchScheduleForm.getServicesWorkersAffiliates();

    /**
     * Add listener to offer only valid workers for the selected service
     */
    searchScheduleForm.addListenerChangeServiceName();
    //userInfo.addListenerChangeWorkerName();

    /**
     * Add the listener to handle submission of the form of schedule searching
     */
    searchScheduleForm.addListenerSubmitForm(searchScheduleForm);

    /**
     * Make initial submission of the form to show the available schedules
     * in all services/departments for the current date
     */
    searchScheduleForm.handleFormSubmission();
});