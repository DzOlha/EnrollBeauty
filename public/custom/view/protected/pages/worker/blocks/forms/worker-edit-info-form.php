<div>
    <div class="form-group">
        <p class="mg-b-0" id="photo-label">
            Main photo
        </p>
        <div class="input-group mb-3">
            <input type="file" class="dropify" id="main-photo-input"
                   accept=".svg, .jpg, .jpeg, .png" data-height="200"
                   name="main-photo" aria-label="Main photo input"
                   aria-labelledby="photo-label"/>
            <div class="error text-danger" id="main-photo-input-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="name-label">
            <span>*</span>Name
        </p>
        <div class="input-group mb-3">
            <input name="name" type="text" placeholder="Name" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Name must be at least 3 characters long and contain only letters"
                   required="required" class="form-control" id="name-input"
                   aria-label="Name input"
                   aria-labelledby="name-label">
            <div class="error text-danger" id="name-input-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="surname-label">
            <span>*</span>Surname
        </p>
        <div class="input-group mb-3">
            <input name="surname" type="text" placeholder="Surname" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Surname must be at least 3 characters long and contain only letters"
                   required="required" class="form-control" id="surname-input"
                   aria-label="Surname input"
                   aria-labelledby="surname-label">
            <div class="error text-danger" id="surname-input-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="description-label">
            Self Description
        </p>
        <div class="input-group mb-3">
            <textarea class="form-control"
                      type="text"
                      id="description-textarea"
                      name="text"
                      rows="3"
                      placeholder="Description"
                      aria-label="Description input"
                      aria-labelledby="description-label"></textarea>
            <div class="error text-danger" id="description-textarea-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="email-label">
            <span>*</span>Email
        </p>
        <div class="input-group mb-3">
            <input name="email" type="email" placeholder="Email" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Email address must be in the format myemail@mailservice.domain"
                   required="required" class="form-control" id="email-input"
                   aria-label="Email input"
                   aria-labelledby="email-label">
            <div class="error text-danger" id="email-input-error"></div>
        </div>
    </div>
    <div class="form-group position-selector-parent">
        <p class="mg-b-0" id="position-label">
            <span>*</span>Position
        </p>
        <select class="form-control select2-with-search"
                id="position-select" aria-label="Position input"
                aria-labelledby="position-label">
            <option label="Choose one">
            </option>
        </select>
        <div class="error text-danger" id="position-select-error"></div>
    </div>
    <div class="form-group role-selector-parent">
        <p class="mg-b-0" id="role-label">
            <span>*</span>Role
        </p>
        <select class="form-control select2-with-search"
                id="role-select" aria-label="Role input"
                aria-labelledby="role-label">
            <option label="Choose one">
            </option>
        </select>
        <div class="error text-danger" id="role-select-error"></div>
    </div>
    <div class="form-group gender-selector-parent">
        <p class="mg-b-0" id="gender-label">
            Gender
        </p>
        <select class="form-control select2-with-search"
                id="gender-select" aria-label="Gender input"
                aria-labelledby="gender-label">
            <option value="" disabled selected>Choose one</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <div class="error text-danger" id="gender-select-error"></div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="age-label">
            <span>*</span>Age
        </p>
        <div class="input-group mb-3">
            <input placeholder="Age"
                   class="form-control" type="number"
                   id="age-input" aria-label="Age input"
                   aria-labelledby="age-label"
            >
            <div class="error text-danger" id="age-input-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="experience-label">
            <span>*</span>Years of experience
        </p>
        <div class="input-group mb-3">
            <input placeholder="Years of experience"
                   class="form-control" type="number"
                   id="experience-input" aria-label="Experience input"
                   aria-labelledby="experience-label"
            >
            <div class="error text-danger" id="experience-input-error"></div>
        </div>
    </div>
    <div class="form-group">
        <p class="mg-b-0" id="salary-label">
            Salary
        </p>
        <div class="input-group mb-3">
            <div class="d-flex">
                <input placeholder="Salary"
                       class="form-control" type="number"
                       id="salary-input" aria-label="Salary input"
                       aria-labelledby="salary-label"
                >
                <div class="input-group-prepend">
                    <span class="input-group-text bd-r">UAH</span>
                </div>
            </div>
            <div class="error text-danger" id="salary-input-error"></div>
        </div>
    </div>
    <button aria-label="Submit"
            class="btn ripple pd-x-25"
            id="edit-worker-details-submit"
            data-bs-dismiss="modal" type="button">
        Update
    </button>
</div>