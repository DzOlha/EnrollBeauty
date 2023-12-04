
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    /**
     * Fill the admin info
     */
    worker.getUserInfo();

    let dateRenderer = new DateRenderer();
    let timeRenderer = new TimeRenderer();

    let confirmationModal = new ConfirmationModal();
    // let appointmentsTable = new AppointmentsTable(
    //     requester, confirmationModal,
    //     dateRenderer, timeRenderer
    // );

    let scheduleRenderer = new WorkerScheduleRenderer(
        requester, null, confirmationModal,
        new WorkerScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );
    let searchScheduleForm = new WorkerSearchScheduleForm(
        requester, scheduleRenderer,
        new OptionBuilder(), dateRenderer,
        '/api/worker/searchSchedule'
    );

    /**
     * Get information for select elements of the form of searching
     * available schedules for appointments
     */
    searchScheduleForm.getServicesAffiliatesForTheWorker();

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