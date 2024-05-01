<?php require_once VIEW_OPEN_BLOCKS."/header/head.php"?>
<link rel="stylesheet" href="/<?=CUSTOM_ASSETS?>/css/pages/open/pages/worker_profile/worker_profile.css">
<body>

<!--page start-->
<div class="page">

    <!-- preloader start -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- preloader end -->

    <!--header start-->
    <header id="masthead" class="header ttm-header-style-01">

        <!-- ttm-header-wrap -->
        <div class="ttm-header-wrap">
            <!-- ttm-stickable-header-w -->
            <div id="ttm-stickable-header-w" class="ttm-stickable-header-w clearfix">
                <!-- ttm-topbar-wrapper -->
                <?php require_once VIEW_OPEN_BLOCKS."/toolbar/info_social_topbar.php"?>
                <!-- ttm-topbar-wrapper end -->

                <?php require_once VIEW_OPEN_BLOCKS."/menu/menu.php"?>
            </div><!-- ttm-stickable-header-w end-->
        </div><!--ttm-header-wrap end -->

    </header><!--header end-->

    <!-- page title -->
    <div class="ttm-page-title-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12" id="profile-title-block">
                    <!--           JS generated -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- page title -->

    <!--site-main start-->
    <div class="site-main">
        <!-- team detail-section -->
        <section class="ttm-row team-detail-section clearfix" id="profile-detail-block">
            <!--           JS generated -->
        </section>
        <!-- team detail-section end-->
        <!-- history-section -->
        <section class="ttm-row history-section pt-0 res-767-pt-0" id="profile-description-block">
            <!--           JS generated -->
        </section>
        <!-- history-section end -->
    </div>
    <!--site-main end-->

<!--site-main end-->

<!--footer start-->
    <?php require_once VIEW_OPEN_BLOCKS . '/footer/footer.php'?>
<!--footer end-->

</div>
<!-- page end -->

<?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>

<!--Mine scripts-->
<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/worker/ui/profile/public/public_profile.js"></script>

</body>
</html>