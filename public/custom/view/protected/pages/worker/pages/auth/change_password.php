
<?php require_once VIEW_OPEN_BLOCKS . "/header/header.php"?>
<link href="/<?=CUSTOM_ASSETS?>/css/pages/open/common/page-form.css" rel="stylesheet"/>

<form id="ttm-quote-form registration-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center">Change password</h2>
    <div class="column">
        <div class="form-group">
            <p class="mg-b-0" id="new-password-label">
                <span>*</span> New Password
            </p>
            <input name="password" type="password" placeholder="New Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="password-input"
                   aria-labelledby="new-password-label" aria-label="New Password Input">
            <div class="error" id="password-input-error"></div>
        </div>
        <div class="form-group">
            <p class="mg-b-0" id="confirm-password-label">
                <span>*</span> Confirm New Password
            </p>
            <input name="confirm-password" type="password" placeholder="Confirm New Password" autocomplete="off"
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
                    id="change-password-form-submit"
            >
                Change
            </button>
        </div>
    </div>
</form>

<!--footer start-->
<!--footer start-->
<?php require_once VIEW_OPEN_BLOCKS."/footer/footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<?php require_once VIEW_OPEN_BLOCKS . '/units/totop.php'?>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>

<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/worker/ui/auth/change_password.js"></script>

</body>
</html>

