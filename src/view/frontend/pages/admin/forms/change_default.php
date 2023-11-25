
<?php require_once VIEW_COMMON."pages/landing/header/header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
    .ttm-quote-form .form-group {
        margin-bottom: 15px;
    }
    body {
        background: var(--grey-around-form);
    }
    .message-success {
        font-size: 25px;
    }
</style>

<form id="ttm-quote-form change-default-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center message-success">
        You successfully created a default admin account! Please change its info to yours!
    </h2>
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
            <input name="email" type="email" placeholder="New Email" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Email address must be in the format myemail@mailservice.domain"
                   required="required" class="form-control" id="email-input">
            <div class="error" id="email-input-error"></div>
        </div>
        <div class="form-group">
            <input name="old-password" type="password" placeholder="Old Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Old password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="old-password-input">
            <div class="error" id="old-password-input-error"></div>
        </div>
        <div class="form-group">
            <input name="new-password" type="password" placeholder="New Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="New password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="new-password-input">
            <div class="error" id="new-password-input-error"></div>
        </div>
        <div class="form-group">
            <input name="confirm-new-password" type="password" placeholder="Confirm New Password" autocomplete="off"
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
                    id="change-default-form-submit"
            >
                Change
            </button>
        </div>
    </div>
</form>

<!--footer start-->
<?php require_once VIEW_COMMON."pages/landing/footer/footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<a id="totop" href="#top">
    <i class="fa fa-angle-up"></i>
</a>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script nomodule src="/public/js/custom/common/libs/jquery.powertip.min.js"></script>

<script src="/public/js/custom/common/pages/classes/notifier/Notifier.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/admin/forms/ChangeDefaultForm.js"></script>
<script src="/public/js/custom/common/pages/classes/requester/Requester.js"></script>

<script src="/public/js/custom/frontend/pages/admin/forms/change_default.js"></script>

</body>
</html>

