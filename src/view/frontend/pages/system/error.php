
<?php require_once VIEW_COMMON."pages/landing/header/header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
    body {
        background: #f4f4f2;
    }
</style>

<form id="ttm-quote-form login-form" class="ttm-quote-form clearfix login-form" method="post" action="#">
    <h2 class="text-center"><?=$data['message']?></h2>
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


<script src="/public/js/custom/common/pages/Notifier.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/user/forms/LoginForm.js"></script>
<script src="/public/js/custom/common/pages/Requestor.js"></script>

<script src="/public/js/custom/frontend/pages/user/forms/login.js"></script>

</body>
</html>

