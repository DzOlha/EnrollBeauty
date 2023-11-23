
class AddWorkerForm extends Form {
    constructor() {
        super();

        this.addWorkerTriggerId = 'add-worker-trigger';
    }
    addListenerAddWorker() {
        let trigger = document.getElementById(this.addWorkerTriggerId);
        trigger.addEventListener('click', this.handleAddWorker);
    }
    handleAddWorker = () => {
        let modal = new FormModal('modalAddWorker');
        modal.show(
            'Create New Worker',
            FormBuilder.createAddWorkerForm(),
            'Create Worker'
        )
    }
}