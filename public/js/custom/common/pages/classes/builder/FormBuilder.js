
class FormBuilder {
    constructor() {
    }

    static createModalForm(modalId) {
        return `<div class="modal modal-form" id="${modalId}">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content tx-size-sm" style="z-index: 20">
                            <div class="modal-body tx-center pd-y-20 pd-x-20">
                                <button class="close-modal float-end" id="${modalId}-close"
                                        type="button">
                                    <img src="/public/images/custom/system/icons/close.svg"/>
                                </button>
                
                                <h4 class="tx-semibold mg-b-20"
                                    id="${modalId}-headline">
                
                                </h4>
                                <div class="column" id="${modalId}-content">
                                   
                                </div>
                                <button aria-label="Submit"
                                        class="btn ripple pd-x-25"
                                        id="${modalId}-submit"
                                        data-bs-dismiss="modal" type="button">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `
    }
    static createAddWorkerForm() {
        return `<div class="form-group">
                    <input name="name" type="text" placeholder="Name" autocomplete="off"
                           data-toggle="tooltip" data-trigger="focus" data-placement="left"
                           data-title="Name must be at least 3 characters long and contain only letters"
                           required="required" class="form-control" id="name-input">
                    <div class="error" id="name-input-error"></div>
                </div>
                <div class="form-group">
                    <input name="surname" type="text" placeholder="Surname" autocomplete="off"
                           data-toggle="tooltip" data-trigger="focus" data-placement="left"
                           data-title="Surname must be at least 3 characters long and contain only letters"
                           required="required" class="form-control" id="surname-input">
                    <div class="error" id="surname-input-error"></div>
                </div>
                <div class="form-group">
                    <input name="email" type="email" placeholder="Email" autocomplete="off"
                           data-toggle="tooltip" data-trigger="focus" data-placement="left"
                           data-title="Email address must be in the format myemail@mailservice.domain"
                           required="required" class="form-control" id="email-input">
                    <div class="error" id="email-input-error"></div>
                </div>
                <div class="form-group">
                    <p class="mg-b-0">Age</p>
                    <div class="input-group mb-3">
                        <input aria-label="Age"
                               class="form-control" type="number"
                               id="age-input"
                        >
                        <div class="error" id="age-input-error"></div>
                    </div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0">Years of experience</p>
                    <div class="input-group mb-3">
                        <input aria-label="Experience"
                               class="form-control" type="number"
                               id="experience-input"
                        >
                        <div class="error" id="experience-input-error"></div>
                    </div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0">Position</p>
                    <select class="form-control select2-with-search"
                            id="position-select">
                        <option label="Choose one">
                        </option>
                    </select>
               </div>
               <div class="form-group">
                    <p class="mg-b-0">Salary</p>
                    <div class="input-group mb-3">
                        <input aria-label="Salary Amount"
                               class="form-control" type="number"
                               id="salary-input"
                        >
                        <div class="input-group-prepend">
                            <span class="input-group-text bd-r">грн</span>
                        </div>
                        <div class="error" id="salary-input-error"></div>
                    </div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0">Role</p>
                    <select class="form-control select2-with-search"
                            id="position-select">
                        <option label="Choose one">
                        </option>
                    </select>
                </div>
            `
    }
}