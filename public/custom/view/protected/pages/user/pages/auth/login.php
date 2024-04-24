
<?php require_once VIEW_OPEN_BLOCKS . "/header/header.php"?>
<link href="/<?=CUSTOM_ASSETS?>/css/pages/open/common/page-form.css" rel="stylesheet"/>

<form id="ttm-quote-form login-form"
      class="ttm-quote-form clearfix login-form"
      method="post" action="#"
>
    <h2 class="text-center">Login</h2>
    <div class="column">
            <div class="form-group">
                <p class="mg-b-0" id="email-label">
                    <span>*</span> Email
                </p>
                <input name="email" type="email" placeholder="Email" autocomplete="off"
                       required="required" class="form-control" id="email-input"
                       aria-labelledby="email-label" aria-label="Email Input">
                <div class="error" id="email-input-error"></div>
            </div>
            <div class="form-group">
                <p class="mg-b-0" id="password-label">
                    <span>*</span> Password
                </p>
                <input name="password" type="password" placeholder="Password" autocomplete="off"
                       required="required" class="form-control" id="password-input"
                       aria-labelledby="password-label" aria-label="Password Input">
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
<?php require_once VIEW_OPEN_BLOCKS."/footer/footer.php"?>
<!--footer end-->

<!--back-to-top start-->
<?php require_once VIEW_OPEN_BLOCKS . '/units/totop.php'?>
<!--back-to-top end-->

</div><!-- page end -->

<?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>

<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/user/ui/auth/login.js"></script>

</body>
</html>

