
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
<!--            <ion-icon name="information-circle-outline"></ion-icon>-->
            <input name="password" type="password" placeholder="Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="password-input">
            <div class="error" id="password-input-error"></div>
        </div>
        <div class="form-group">
            <input name="confirm-password" type="password" placeholder="Confirm Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password confirmation must be equal to the provided password"
                   required="required" class="form-control" id="confirm-password-input">
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
<a id="totop" href="#top">
    <i class="fa fa-angle-up"></i>
</a>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>

<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/user/ui/auth/registration.js"></script>

</body>
</html>
