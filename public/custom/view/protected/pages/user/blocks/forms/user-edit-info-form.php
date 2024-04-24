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

    <button aria-label="Submit"
            class="btn ripple pd-x-25"
            id="edit-user-details-submit"
            data-bs-dismiss="modal" type="button">
        Update
    </button>
</div>