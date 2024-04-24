
<?php require_once VIEW_OPEN_BLOCKS . "/header/header.php"?>
<link href="/<?=CUSTOM_ASSETS?>/css/pages/open/common/page-form.css" rel="stylesheet"/>


<form id="ttm-quote-form registration-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center">Registration</h2>
    <div class="column">
        <div class="form-group">
            <p class="mg-b-0" id="name-label">
                <span>*</span> Name
            </p>
            <input name="name" type="text" placeholder="Name" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Name must be between 3-50 characters long and contain only letters"
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
                   data-title="Surname must be between 3-50 characters long and contain only letters"
                   required="required" class="form-control" id="surname-input"
                   aria-labelledby="surname-label" aria-label="Surname Input">
            <div class="error" id="surname-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="email-label">
                <span>*</span> Email
            </p>
            <input name="email" type="email" placeholder="Email" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Email address must be in the format myemail@mailservice.domain that not exceeds 100 characters"
                   required="required" class="form-control" id="email-input"
                   aria-labelledby="email-label" aria-label="Email Input">
            <div class="error" id="email-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="password-label">
                <span>*</span> Password
            </p>
            <input name="password" type="password" placeholder="Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="password-input"
                   aria-labelledby="password-label" aria-label="Password Input">
            <div class="error" id="password-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="confirm-password-label">
                <span>*</span> Confirm Password
            </p>
            <input name="confirm-password" type="password" placeholder="Confirm Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password confirmation must be equal to the provided password"
                   required="required" class="form-control" id="confirm-password-input"
                   aria-labelledby="confirm-password-label" aria-label="Confirm Password Input">
            <div class="error" id="confirm-password-input-error"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="button"
                    class="ttm-btn ttm-btn-color-skincolor ttm-btn-style-fill"
                    id="registration-form-submit"
                    >
                Register
            </button>
        </div>
    </div>
</form>

<!--footer start-->
<?php require_once VIEW_OPEN_BLOCKS."/footer/footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<?php require_once VIEW_OPEN_BLOCKS . '/units/totop.php'?>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>

<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/user/ui/auth/registration.js"></script>

</body>
</html>

