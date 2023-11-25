
class AddWorkerForm extends Form {
    constructor(requester, modalForm, optionBuilder) {
        super();
        this.requester = requester;
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.addWorkerTriggerId = 'add-worker-trigger';

        this.nameInputId = 'name-input';
        this.surnameInputId = 'surname-input';
        this.emailInputId = 'email-input';
        this.genderSelectId = 'gender-select';
        this.ageInputId = 'age-input';
        this.experienceInputId = 'experience-input';
        this.positionSelectId = 'position-select';
        this.salaryInputId = 'salary-input';
        this.roleSelectId = 'role-select';

        this.select2ContainerClass = 'select2-container';

        this.genderParentClass = 'gender-selector-parent';
        this.positionParentClass = 'position-selector-parent';
        this.roleParentClass = 'role-selector-parent';

        this.modalBodyClass = 'modal-body';

        this.apiGetPositionsRoles = '/api/admin/getAllPositionsRoles';
    }

    _initSelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        $(`#${this.genderSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one",
            allowClear: true,
        });
        $(`#${this.positionSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one",
            allowClear: false,
        });
        $(`#${this.roleSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one",
            allowClear: false,
        });
    }

    /**
     * Add listener to the 'Add New Worker' button
     */
    addListenerShowAddWorkerForm() {
        let trigger = document.getElementById(this.addWorkerTriggerId);
        trigger.addEventListener('click', this.handleShowAddWorkerForm);
    }

    /**
     * Handle the click on 'Add Ne Worker' button to
     * show the modal window with the appropriate form
     */
    handleShowAddWorkerForm = () => {
        this.modalForm.setSelectors('modalAddWorker');
        this.modalForm.show(
            'Create New Worker',
            this.modalForm.formBuilder.createAddWorkerForm(),
            'Create Worker'
        );
        this.modalForm.close();
        this.getPositionsAndRoles();
    }

    _populateSelectOptions(parent, data) {
        parent.html('');
        parent.append(this.optionBuilder.createOptionLabel());

        data.forEach((item) => {
            parent.append(this.optionBuilder.createOption(
                item.id, item.name
            ));
        })

        parent.select2();
    }

    getPositionsAndRoles() {
        this.requester.get(
            this.apiGetPositionsRoles,
            this.successCallbackGetPositionsAndRoles.bind(this),
            (message) => {
                Notifier.showErrorMessage(message);
            }
        )
    }

    successCallbackGetPositionsAndRoles(response) {
        console.log(response);

        let positionsSelect = $(`#${this.positionSelectId}`);
        this._populateSelectOptions(positionsSelect, response.data.positions);

        let rolesSelect = $(`#${this.roleSelectId}`);
        this._populateSelectOptions(rolesSelect, response.data.roles);

        this._initSelect2();
    }


}