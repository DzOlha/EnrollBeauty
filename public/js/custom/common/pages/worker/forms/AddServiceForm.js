
class AddServiceForm extends Form {
    constructor(requester, modalForm, optionBuilder, servicesTable) {
        super(
            '',
            '',
            '/api/worker/service/add',
            requester
        );
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.servicesTable = servicesTable;
        this.addServiceTriggerId = 'add-service-trigger';

        this.serviceInputId = 'service-input';
        this.priceInputId = 'price-input';
        this.departmentSelectId = 'department-select';

        this.modalBodyClass = 'modal-body';

        this.apiGetServices = '/api/worker/department/get/all';
    }
}