
class AddWorkerForm extends Form {
    constructor(requester, modalForm, optionBuilder, workersTable) {
        super(
            '',
            '',
            '/api/admin/registerWorker',
            requester
        );
        console.log(modalForm.modalSubmitId);
        //this.requester = requester;
        this.modalForm = modalForm;
        this.optionBuilder = optionBuilder;
        this.workersTable = workersTable;
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

    /**
     * ---------------------------------Form initialization------------------------------
     */

    _initSelect2() {
        let modalBody = $(`#${this.modalForm.modalId} .${this.modalBodyClass}`);
        this._initGenderSelect2(modalBody);
        this._initPositionSelect2(modalBody);
        this._initRoleSelect2(modalBody);
    }
    _initGenderSelect2(modalBody) {
        $(`#${this.genderSelectId}`).select2({
            dropdownParent:  modalBody,
            placeholder: "Choose one",
            allowClear: true,
        });
    }

    _initPositionSelect2(modalBody) {
        $(`#${this.positionSelectId}`).select2({
            dropdownParent: modalBody,
            placeholder: "Choose one",
            allowClear: false,
        });
    }

    _initRoleSelect2(modalBody) {
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
        this.submitButtonId = this.modalForm.modalSubmitId;
        this.modalForm.show(
            'Create New Worker',
            this.modalForm.formBuilder.createAddWorkerForm(),
            'Create Worker'
        );
        // Wait for the modal to fully show before initializing Select2
        // this.modalForm.showCompleteCallback = () => {
        //     this._initSelect2();
        //     this.getPositionsAndRoles();
        // };
        this._initSelect2();
        this.getPositionsAndRoles();
        this.modalForm.close();
        this.addListenerSubmitForm();
    }

    _populateSelectOptions(parent, data) {
        parent.html('');
        parent.append(this.optionBuilder.createOptionLabel());

        data.forEach((item) => {
            parent.append(this.optionBuilder.createOption(
                item.id, item.name
            ));
        })
        parent.select2('destroy').select2();
        if(parent.attr('id') === this.roleSelectId) {
            parent.val('2').trigger('change');
            parent.prop('disabled', true);
        }
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


    /**
     * ----------------------------Form validation and submission----------------------------
     */
    listenerSubmitForm = () => {
        /**
         * @type {{[p: string]: *}|boolean}
         *
         * {
         *     name:
         *     surname:
         *     email:
         *     position_id:
         *     role_id:
         *     gender:
         *     age:
         *     experience:
         *     salary:
         * }
         */
        let data = this.validateInputs();
        console.log(data);

        if(data) {
            this.requester.post(
                this.submitActionUrl,
                data,
                this.successCallbackSubmit.bind(this),
                (response) => {
                    Notifier.showErrorMessage(response.error);
                }
            )
        }
    }
    successCallbackSubmit(response) {
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);

        /**
         * Close modal window with form
         */
        $(`#${this.modalForm.modalCloseId}`).click();

        /**
         * Regenerate the table of workers to show the newly added worker there
         */
        this.workersTable.sendApiRequest(
            this.workersTable.getItemsPerPage(),
            Cookie.get('currentPage')
        );
    }
    validateInputs() {
        let nameSurnameEmail = this.validateNameSurnameEmail();
        let positionRoleGender = this.validatePositionRoleGender();
        let ageExperienceSalary = this.validateAgeExperienceSalary();

        if(nameSurnameEmail && positionRoleGender && ageExperienceSalary) {
            return {
                ...nameSurnameEmail, ...positionRoleGender, ...ageExperienceSalary
            }
        } else {
            return false;
        }
    }
    _getNameSurnameEmailRules() {
        const formRules = {};

        formRules[this.nameInputId] = {
            required: true,
            pattern: /^[A-Za-zА-Яа-яіїІЇ]{3,}$/
        };
        formRules[this.surnameInputId] = {
            required: true,
            pattern: /^[A-Za-zА-Яа-яіїІЇ]{3,}$/
        };
        formRules[this.emailInputId] = {
            required: true,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        };
        return formRules;
    }

    _getNameSurnameEmailMessages() {
        const formMessages = {};

        formMessages[this.nameInputId] = {
            required: 'Please enter your name',
            pattern: 'Name must be at least 3 characters long and contain only letters'
        };
        formMessages[this.surnameInputId] = {
            required: 'Please enter your surname',
            pattern: 'Surname must be at least 3 characters long and contain only letters'
        };
        formMessages[this.emailInputId] = {
            required: 'Please enter your email address',
            pattern: 'Please enter an email address in the format myemail@mailservice.domain'
        };
        return formMessages;
    }

    /**
     *
     * @param idAssoc
     * @returns {{}|{surname: any, name: any, email: any}}
     * @private
     */
    _collectNameSurnameEmail(idAssoc = false) {
        let name = document.getElementById(this.nameInputId);
        if (name === null) return;
        name = name.value.trim();

        let surname = document.getElementById(this.surnameInputId);
        if (surname === null) return;
        surname = surname.value.trim();

        let email = document.getElementById(this.emailInputId);
        if (email === null) return;
        email = email.value.trim();

        if (idAssoc === true) {
            let result = {};
            result[this.nameInputId] = name;
            result[this.surnameInputId] = surname;
            result[this.emailInputId] = email;

            return result;
        }
        return {
            'name': name,
            'surname': surname,
            'email': email
        };
    }

    /**
     *
     * @returns {boolean|{}|{surname: HTMLElement, name: HTMLElement, email: HTMLElement}}
     */
    validateNameSurnameEmail() {
        let formRules = this._getNameSurnameEmailRules();
        let formMessages = this._getNameSurnameEmailMessages();
        let nameSurnameEmail = this._collectNameSurnameEmail(true);

        // Call the validation function and get the errors
        const validationErrors = this.validateFormData(nameSurnameEmail, formRules, formMessages);

        this.displayErrors(validationErrors);

        // Check if there are no div.error elements with text content
        const noErrorWithText = $("div.error").get().every(function (element) {
            return $(element).text().trim() === '';
        });

        // Return data if there are no errors
        if (noErrorWithText) {
            return nameSurnameEmail;
        } else {
            return false;
        }
    }

    _checkSelectAndSetErrorBorder(value, wrapperClass, errorId, errorMessage) {
        let wrapper = $(`.${wrapperClass} .${this.select2ContainerClass}`);
        let hasGender = wrapper.find(`#select2-${this.genderSelectId}-container`).length > 0;
        let errorBlock = $(`#${errorId}`);
        if(!value && !hasGender) {
            wrapper.addClass('border-error');
            errorBlock.html(errorMessage);
            return false;
        } else {
            wrapper.removeClass('border-error');
            errorBlock.html('');
        }
        return true;
    }

    /**
     *
     * @returns {{gender: (*|string), role_id: *, position_id: *}|boolean}
     */
    validatePositionRoleGender() {
        let positionSelect = document.getElementById(this.positionSelectId);
        let roleSelect = document.getElementById(this.roleSelectId);
        let genderSelect = document.getElementById(this.genderSelectId);

        /**
         * Validate position id
         */
        let position_id = positionSelect.value.trim();
        let validPosition = this._checkSelectAndSetErrorBorder(
            position_id, this.positionParentClass,
            `${this.positionSelectId}-error`, "Position is required field!"
        );

        /**
         * Validate role id
         */
        let role_id = roleSelect.value.trim();
        let validRole = this._checkSelectAndSetErrorBorder(
            role_id, this.roleParentClass,
            `${this.roleSelectId}-error`, "Role is required field!"
        );

        /**
         * Just get gender in string format because it is not required field
         */
        let gender_string = genderSelect.value.trim();

        if(validPosition && validRole) {
            return {
                'position_id': position_id,
                'role_id': role_id,
                'gender': gender_string ?? ''
            }
        } else {
            return false;
        }
    }


    /**
     *
     * @returns {{experience: *, salary: *, age: *}|boolean}
     */
    validateAgeExperienceSalary() {
        let ageInput = document.getElementById(this.ageInputId);
        let experienceInput = document.getElementById(this.experienceInputId);
        let salaryInput = document.getElementById(this.salaryInputId);

        let ageNumber = ageInput.value.trim();
        let validAge = true;
        let ageError = $(`#${this.ageInputId}-error`);

        /**
         * Validate age number
         */
        if(!ageNumber && validAge) {
            validAge = false;
            ageInput.classList.add('border-danger');
            $(`#${this.ageInputId}-error`).html('Age is required field!');
        }
        if(isNaN(ageNumber)) {
            validAge = false;
            ageInput.classList.add('border-danger');
            $(`#${this.ageInputId}-error`).html('Incorrect number is provided!');
        }
        else {
            if(validAge && (ageNumber < 14 || ageNumber > 80)) {
                validAge = false;
                ageInput.classList.add('border-danger');
                ageError.html(
                    "The worker's age should be from 14 to 80 years!"
                );
            }
        }
        if(validAge) {
            if(ageInput.classList.contains('border-danger')) {
                ageInput.classList.remove('border-danger');
            }
            ageError.html('');
        }

        /**
         * Validate years of experience
         */
        let experienceNumber = experienceInput.value.trim();
        let validExperience = true;
        let exError = $(`#${this.experienceInputId}-error`);

        if(!experienceNumber) {
            validExperience = false;
            experienceInput.classList.add('border-danger');
            $(`#${this.experienceInputId}-error`).html('Years of experience is required field!!');
        }
        if(isNaN(experienceNumber) && validExperience) {
            validExperience = false;
            experienceInput.classList.add('border-danger');
            $(`#${this.experienceInputId}-error`).html('Incorrect number is provided!');
        }
        else {
            if(validExperience && (experienceNumber < 0 || experienceNumber > 66)) {
                validExperience = false;
                experienceInput.classList.add('border-danger');
                exError.html(
                    "The years of the worker's experience should be from 0 to 66 years!"
                );
            }
        }
        if(validExperience) {
            if(experienceInput.classList.contains('border-danger')) {
                experienceInput.classList.remove('border-danger');
            }
            exError.html('');
        }

        /**
         * Validate salary number
         */
        let salaryNumber = salaryInput.value.trim();
        let validSalary = true;
        let salaryError = $(`#${this.salaryInputId}-error`);

        if(salaryNumber) {
            if(isNaN(salaryNumber)) {
                validSalary = false;
                salaryInput.classList.add('border-danger');
                salaryError.html('Incorrect salary number is provided!');
            }
            if(salaryNumber < 0 && validSalary) {
                validSalary = false;
                salaryInput.classList.add('border-danger');
                salaryError.html('Salary can not be negative number!');
            }
        }
        if(validSalary) {
            if(salaryInput.classList.contains('border-danger')) {
                salaryInput.classList.remove('border-danger');
            }
            salaryError.html('');
        }

        /**
         * Result
         */
        if(validAge && validSalary && validExperience) {
            return {
                'age': ageNumber,
                'experience': experienceNumber,
                'salary': salaryNumber
            }
        } else {
            return false;
        }
    }
}