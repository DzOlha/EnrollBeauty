
<?php require_once VIEW_COMMON."pages/landing/header/header.php"?>
<link href="/public/css/custom/common/pages/page-form.css" rel="stylesheet"/>

<form id="ttm-quote-form login-form" class="ttm-quote-form clearfix login-form" method="post" action="#">
    <h2 class="text-center">Login to Worker account</h2>
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
<?php require_once VIEW_COMMON."pages/landing/footer/footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<a id="totop" href="#top">
    <i class="fa fa-angle-up"></i>
</a>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>


<script src="/public/js/custom/common/pages/classes/notifier/Notifier.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/user/forms/LoginForm.js"></script>
<script src="/public/js/custom/common/pages/worker/forms/WorkerLoginForm.js"></script>
<script src="/public/js/custom/common/pages/classes/requester/Requester.js"></script>

<script src="/public/js/custom/frontend/pages/worker/forms/login.js"></script>

</body>
</html>

