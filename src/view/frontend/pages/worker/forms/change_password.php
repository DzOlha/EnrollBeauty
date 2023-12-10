
<?php require_once VIEW_COMMON."pages/landing/header/header.php"?>
<link href="/public/css/custom/common/pages/page-form.css" rel="stylesheet"/>

<form id="ttm-quote-form registration-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center">Change password</h2>
    <div class="column">
        <div class="form-group">
            <!--            <ion-icon name="information-circle-outline"></ion-icon>-->
            <input name="password" type="password" placeholder="New Password" autocomplete="off"
                   data-toggle="tooltip" data-trigger="focus" data-placement="left"
                   data-title="Password must contain at least one uppercase letter, one lowercase letter,
                               one digit, one special character, and be between 8 to 30 characters long"
                   required="required" class="form-control" id="password-input">
            <div class="error" id="password-input-error"></div>
        </div>
        <div class="form-group">
            <input name="confirm-password" type="password" placeholder="Confirm New Password" autocomplete="off"
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
                    id="change-password-form-submit"
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

<script type="module" src="/public/js/custom/frontend/pages/worker/forms/change_password.js"></script>

</body>
</html>

