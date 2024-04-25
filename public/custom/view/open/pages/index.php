<?php require_once VIEW_OPEN_BLOCKS."/header/head.php"?>
<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/pages/user/profile.css" rel="stylesheet" />
<!-- Internal Daterangepicker css-->
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/web-fonts/plugin.css" rel="stylesheet"/>

<link href="/<?=CUSTOM_ASSETS?>/css/pages/open/pages/home/home.css" rel="stylesheet">

<!-- Style css-->
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/css/style.css" rel="stylesheet">

<div>

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

        <!-- START homebanner -->
        <?php require_once VIEW_OPEN_BLOCKS."/slider/homepage_slider.php"?>
        <!-- END homebanner -->

        <!--site-main start-->
        <div class="site-main">

            <!-- about us-section -->
            <?php require_once VIEW_OPEN_BLOCKS."/sections/about_us_section.php"?>
            <!-- about us-section end -->

            <!-- service-section title-->
            <?php require_once VIEW_OPEN_BLOCKS."/sections/service_section_title.php"?>
            <!-- service-section title end-->

            <!-- service-section content-->
            <?php require_once VIEW_OPEN_BLOCKS."/sections/service_section_content.php"?>
            <!-- service-section content end -->

            <!-- pricelist-section-section -->
            <?php require_once VIEW_OPEN_BLOCKS."/sections/pricelist_section.php"?>

            <div class="main-content pt-0">
                <div class="main-container container-fluid schedule">
                    <div class="inner-body">
                        <?php require_once VIEW_PROTECTED_BLOCKS . "/forms/schedule-search-form.php";?>
                    </div>
                </div>
            </div>
            <!-- pricelist-section end -->

            <!-- stylish Team section -->
            <?php require_once VIEW_OPEN_BLOCKS."/sections/team_members_section.php"?>
            <!-- stylish Team section end-->

        </div>

        <!--footer start-->
        <?php require_once VIEW_OPEN_BLOCKS."/footer/footer_widget.php"?>
        <!--footer end-->

        <!--back-to-top start-->
        <?php require_once VIEW_OPEN_BLOCKS . '/units/totop.php'?>
        <!--back-to-top end-->
    </div>
    </div>

        <!--site-main end-->

        <!--footer start-->
        <!--</div>-->
        <!-- End Page -->

        <?php require_once VIEW_PROTECTED_BLOCKS."/modals/modal-confirmation.php" ?>

        <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap/js/bootstrap.min.js" async></script>

        <!-- Color Theme js -->
<!--        <script src="/--><?php //=MOCKUP_PROTECTED_FOLDER?><!--/assets/js/themeColors.js" defer></script>-->


        <?php require_once VIEW_OPEN_BLOCKS."/footer/footer_scripts.php"?>
        <!-- Select2 js-->
        <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/select2/js/select2.min.js"></script>
       <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/js/select2.js"></script>

        <!-- Internal Daternagepicker js-->
        <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
        <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!--Mine scripts-->

        <script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/open/home/ui/home.js" async></script>
</body>
</html>


