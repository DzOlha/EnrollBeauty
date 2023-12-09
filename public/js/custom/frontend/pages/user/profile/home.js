$(function () {
    let requester = new Requester();
    let user = new User(requester);

    let dateRenderer = new DateRenderer();
    let timeRenderer = new TimeRenderer();

    let confirmationModal = new ConfirmationModal();
    let appointmentsTable = new AppointmentsTable(
        requester, confirmationModal,
        dateRenderer, timeRenderer
    );

    let scheduleRenderer = new ScheduleRenderer(
            requester, appointmentsTable, confirmationModal,
            new ScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );
    let searchScheduleForm = new SearchScheduleForm(
        requester, scheduleRenderer,
        new OptionBuilder(), dateRenderer
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
    searchScheduleForm.getServices();
    searchScheduleForm.getWorkers();
    searchScheduleForm.getAffiliates();

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