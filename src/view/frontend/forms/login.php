
<?php require_once VIEW_COMMON."main_header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
</style>

<form id="ttm-quote-form login-form" class="ttm-quote-form clearfix login-form" method="post" action="#">
    <h2 class="text-center">Login</h2>
    <div class="column">
            <div class="form-group">
                <input name="email" type="email" placeholder="Email"
                       required="required" class="form-control">
            </div>
            <div class="form-group">
                <input name="password" type="password" placeholder="Password"
                       required="required" class="form-control" >
            </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-10">
            <button type="submit"
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


</body>
</html>

