
<?php require_once VIEW_COMMON."main_header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
    .ttm-quote-form .form-group {
        margin-bottom: 15px;
    }
</style>

<form id="ttm-quote-form registration-form"
      class="ttm-quote-form clearfix registration-form"
      method="post"
      action="#"
>
    <h2 class="text-center">Registration</h2>
    <div class="column">
        <div class="form-group">
            <input name="name" type="text" placeholder="Name"
                   required="required" class="form-control" id="name-input">
            <div class="error" id="name-input-error"></div>
        </div>
        <div class="form-group">
            <input name="surname" type="text" placeholder="Surname"
                   required="required" class="form-control" id="surname-input">
            <div class="error" id="surname-input-error"></div>
        </div>
        <div class="form-group">
            <input name="email" type="email" placeholder="Email"
                   required="required" class="form-control" id="email-input">
            <div class="error" id="email-input-error"></div>
        </div>
        <div class="form-group">
            <input name="password" type="password" placeholder="Password"
                   required="required" class="form-control" id="password-input">
            <div class="error" id="password-input-error"></div>
        </div>
        <div class="form-group">
            <input name="confirm-password" type="password" placeholder="Confirm Password"
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
<?php require_once VIEW_COMMON."footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<a id="totop" href="#top">
    <i class="fa fa-angle-up"></i>
</a>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_COMMON."footer_scripts.php"?>

<script src="/public/js/custom/common/classes/forms/Form.js"></script>
<script src="/public/js/custom/common/classes/forms/RegistrationForm.js"></script>
<script src="/public/js/custom/common/classes/Requestor.js"></script>


<script src="/public/js/custom/frontend/forms/registration.js"></script>

</body>
</html>

