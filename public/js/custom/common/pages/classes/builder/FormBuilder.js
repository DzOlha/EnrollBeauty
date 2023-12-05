
class FormBuilder {
    constructor() {
    }

    createModalForm(modalId) {
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
    createAddWorkerForm() {
        return `<div class="form-group">
                    <p class="mg-b-0"><span>*</span>Name</p>
                    <div class="input-group mb-3">
                        <input name="name" type="text" placeholder="Name" autocomplete="off"
                               data-toggle="tooltip" data-trigger="focus" data-placement="left"
                               data-title="Name must be at least 3 characters long and contain only letters"
                               required="required" class="form-control" id="name-input">
                        <div class="error text-danger" id="name-input-error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <p class="mg-b-0"><span>*</span>Surname</p>
                    <div class="input-group mb-3">
                        <input name="surname" type="text" placeholder="Surname" autocomplete="off"
                               data-toggle="tooltip" data-trigger="focus" data-placement="left"
                               data-title="Surname must be at least 3 characters long and contain only letters"
                               required="required" class="form-control" id="surname-input">
                        <div class="error text-danger" id="surname-input-error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <p class="mg-b-0"><span>*</span>Email</p>
                    <div class="input-group mb-3">
                        <input name="email" type="email" placeholder="Email" autocomplete="off"
                               data-toggle="tooltip" data-trigger="focus" data-placement="left"
                               data-title="Email address must be in the format myemail@mailservice.domain"
                               required="required" class="form-control" id="email-input">
                        <div class="error text-danger" id="email-input-error"></div>
                    </div>
                </div>
                 <div class="form-group position-selector-parent">
                    <p class="mg-b-0"><span>*</span>Position</p>
                    <select class="form-control select2-with-search"
                            id="position-select">
                        <option label="Choose one">
                        </option>
                    </select>
                     <div class="error text-danger" id="position-select-error"></div>
                </div>
                 <div class="form-group role-selector-parent">
                    <p class="mg-b-0"><span>*</span>Role</p>
                    <select class="form-control select2-with-search"
                            id="role-select">
                        <option label="Choose one">
                        </option>
                    </select>
                    <div class="error text-danger" id="role-select-error"></div>
               </div>
                 <div class="form-group gender-selector-parent">
                    <p class="mg-b-0">Gender</p>
                    <select class="form-control select2-with-search"
                            id="gender-select">
                        <option value="" disabled selected>Choose one</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="error text-danger" id="gender-select-error"></div>
               </div>
                <div class="form-group">
                    <p class="mg-b-0"><span>*</span>Age</p>
                    <div class="input-group mb-3">
                        <input aria-label="Age" placeholder="Age"
                               class="form-control" type="number"
                               id="age-input"
                        >
                        <div class="error text-danger" id="age-input-error"></div>
                    </div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0"><span>*</span>Years of experience</p>
                    <div class="input-group mb-3">
                        <input aria-label="Experience" placeholder="Years of experience"
                               class="form-control" type="number"
                               id="experience-input"
                        >
                        <div class="error text-danger" id="experience-input-error"></div>
                    </div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0">Salary</p>
                    <div class="input-group mb-3">
                        <div class="d-flex">
                            <input aria-label="Salary Amount" placeholder="Salary"
                                   class="form-control" type="number"
                                   id="salary-input"
                            >
                            <div class="input-group-prepend">
                                <span class="input-group-text bd-r">UAH</span>
                            </div>
                        </div>
                        <div class="error text-danger" id="salary-input-error"></div>
                    </div>
               </div>
            `
    }

    createAddPricingForm() {
        return `<div class="form-group service-selector-parent">
                    <p class="mg-b-0"><span>*</span>Service</p>
                    <select class="form-control select2-with-search"
                            id="service-select">
                        <option label="Choose one">
                        </option>
                    </select>
                    <div class="error text-danger" id="service-select-error"></div>
               </div>
               <div class="form-group">
                    <p class="mg-b-0"><span>*</span>Price</p>
                    <div class="input-group mb-3">
                        <div class="d-flex">
                            <input aria-label="Price Amount" placeholder="Price"
                                   class="form-control" type="number"
                                   id="price-input"
                            >
                            <div class="input-group-prepend">
                                <span class="input-group-text bd-r">UAH</span>
                            </div>
                        </div>
                        <div class="error text-danger" id="price-input-error"></div>
                    </div>
               </div>
            `
    }
}