
<?php require_once VIEW_OPEN_BLOCKS . "/header/header.php"?>
<link href="/<?=CUSTOM_ASSETS?>/css/pages/open/common/page-form.css" rel="stylesheet"/>

<form id="ttm-quote-form change-default-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center message-success">
        You successfully created a default admin account! Please change the info to yours!
    </h2>
    <div class="column">
        <div class="form-group">
            <p class="mg-b-0" id="name-label">
                <span>*</span> Name
            </p>
            <input name="name" type="text" placeholder="Name" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Name must be between 3-50 characters long and contain only letters with dashes"
                   required="required" class="form-control" id="name-input"
                   aria-labelledby="name-label" aria-label="Name Input">
            <div class="error" id="name-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="surname-label">
                <span>*</span> Surname
            </p>
            <input name="surname" type="text" placeholder="Surname" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Surname must be between 3-50 characters long and contain only letters with dashes"
                   required="required" class="form-control" id="surname-input"
                   aria-labelledby="surname-label" aria-label="Surname Input">
            <div class="error" id="surname-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="email-label">
                <span>*</span> New Email
            </p>
            <input name="email" type="email" placeholder="New Email" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Email address must be in the format myemail@mailservice.domain that not exceeds 100 characters"
                   required="required" class="form-control" id="email-input"
                   aria-labelledby="email-label" aria-label="Email Input">
            <div class="error" id="email-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="old-password-label">
                <span>*</span> Old Password
            </p>
            <input name="old-password" type="password" placeholder="Old Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Old password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="old-password-input"
                   aria-labelledby="old-password-label" aria-label="Old Password Input">
            <div class="error" id="old-password-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="new-password-label">
                <span>*</span> New Password
            </p>
            <input name="new-password" type="password" placeholder="New Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="New password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="new-password-input"
                   aria-labelledby="new-password-label" aria-label="New Password Input">
            <div class="error" id="new-password-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="confirm-password-label">
                <span>*</span> Confirm New Password
            </p>
            <input name="confirm-new-password" type="password" placeholder="Confirm New Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password confirmation must be equal to the provided password"
                   required="required" class="form-control" id="confirm-password-input"
                   aria-labelledby="confirm-password-label" aria-label="Confirm New Password Input">
            <div class="error" id="confirm-password-input-error"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="button"
                    class="ttm-btn ttm-btn-color-skincolor ttm-btn-style-fill"
                    id="change-default-form-submit"
            >
                Change
            </button>
        </div>
    </div>
</form>

<!--footer start-->
<?php require_once VIEW_OPEN_BLOCKS."/footer/footer.php"?>
<!--footer end-->

<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/admin/ui/auth/change_default.js"></script>

</body>
</html>

