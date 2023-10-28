
<?php require_once VIEW_COMMON."main_header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
    body {
        background: #f4f4f2;
    }
</style>

<form id="ttm-quote-form login-form" class="ttm-quote-form clearfix login-form" method="post" action="#">
    <h2 class="text-center">Login</h2>
    <div class="column">
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
    </div>

    <div class="row">
        <div class="col-md-12 mt-10">
            <button type="button"
                    class="ttm-btn ttm-btn-color-skincolor ttm-btn-style-fill"
                    id="login-form-submit"
                    >
                Log In
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
<script src="/public/js/custom/common/classes/forms/LoginForm.js"></script>
<script src="/public/js/custom/common/classes/Requestor.js"></script>

<script src="/public/js/custom/frontend/forms/login.js"></script>

</body>
</html>

