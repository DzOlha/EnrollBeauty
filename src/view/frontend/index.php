<?php require_once VIEW_COMMON."pages/landing/header/head.php"?>
<link href="/public/css/custom/common/pages/user/profile.css" rel="stylesheet" />
<!-- Internal Daterangepicker css-->
<link href="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

<link href="/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<!-- Icons css-->
<!--<link href="/public/assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">-->
<link href="/public/assets/plugins/web-fonts/plugin.css" rel="stylesheet"/>

<link href="/public/css/custom/common/pages/landing/home.css" rel="stylesheet">

<!-- Style css-->
<link href="/public/assets/css/style.css" rel="stylesheet">

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
                    <?php require_once VIEW_COMMON."pages/landing/toolbar/info_social_topbar.php"?>
                   <!-- ttm-topbar-wrapper end -->

                    <?php require_once VIEW_COMMON."pages/landing/menu/menu.php"?>
                </div><!-- ttm-stickable-header-w end-->
            </div><!--ttm-header-wrap end -->

        </header><!--header end-->

        <!-- START homebanner -->
        <?php require_once VIEW_COMMON."pages/landing/slider/homepage_slider.php"?>
        <!-- END homebanner -->

        <!--site-main start-->
        <div class="site-main">

            <!-- about us-section -->
            <?php require_once VIEW_COMMON."pages/landing/sections/about_us_section.php"?>
            <!-- about us-section end -->

            <!-- service-section title-->
            <?php require_once VIEW_COMMON."pages/landing/sections/service_section_title.php"?>
            <!-- service-section title end-->
            <!-- service-section content-->
            <?php require_once VIEW_COMMON."pages/landing/sections/service_section_content.php"?>
            <!-- service-section content end -->
			<!-- about-beautysalloon-section -->
<!--            --><?php //require_once VIEW_COMMON."pages/landing/sections/about_beautysalon_section.php"?>
            <!-- about-beautysalloon section end -->
            <!-- pricelist-section-section -->
            <?php require_once VIEW_COMMON."pages/landing/sections/pricelist_section.php"?>

            <div class="main-content pt-0">
                <div class="main-container container-fluid">
                    <div class="inner-body">
                        <?php require_once VIEW_COMMON . 'pages/user/forms/search-schedule-form.php';?>
                    </div>
                </div>
            </div>

            <!-- pricelist-section end -->
            <!-- stylish Team section -->
            <?php require_once VIEW_COMMON."pages/landing/sections/team_members_section.php"?>
            <!-- stylish Team section end-->
            <!-- styleservices section -->
            <?php require_once VIEW_COMMON."pages/landing/sections/styleservices_section.php"?>
            <!-- styleservices section end-->
             <!-- testimonials -->
            <?php require_once VIEW_COMMON."pages/landing/sections/testimonials_section.php"?>
             <!-- testimonials end -->
             <!-- contact-section  -->
<!--            --><?php //require_once VIEW_COMMON."pages/landing/sections/contact_form_section.php"?>
             <!-- contact-section  -->
            <!-- news-section  -->
<!--            --><?php //require_once VIEW_COMMON."pages/landing/sections/news_section.php"?>
            <!-- news-section end -->

        </div>

        <!--footer start-->
        <?php require_once VIEW_COMMON."pages/landing/footer/footer_widget.php"?>
        <!--footer end-->

        <!--back-to-top start-->
        <a id="totop" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
        <!--back-to-top end-->
    </div>
    </div>

        <!--site-main end-->

        <!--footer start-->
        <!--</div>-->
        <!-- End Page -->

        <?php require_once VIEW_COMMON."pages/user/profile/modals/modal-confirmation.php" ?>

        <!-- Jquery js-->
        <script src="/public/assets/plugins/jquery/jquery.min.js"></script>

        <!-- Bootstrap js-->
        <script src="/public/assets/plugins/bootstrap/js/popper.min.js"></script>
        <script src="/public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

        <!-- Moment js-->
        <script src="/public/assets/plugins/moment/min/moment.min.js"></script>

        <!-- Datepicker js-->
        <script src="/public/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>

        <!-- Jquery-Ui js-->
        <script src="/public/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>

        <!-- Color Theme js -->
        <script src="/public/assets/js/themeColors.js"></script>

        <!-- Custom js -->
<!--        <script src="/public/assets/js/custom.js"></script>-->

        <?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>
        <!-- Select2 js-->
        <script src="/public/assets/plugins/select2/js/select2.min.js"></script>
        <script src="/public/assets/plugins/select2/js/select2.full.min.js"></script>
        <script src="/public/assets/js/select2.js"></script>

        <!-- Internal Daternagepicker js-->
        <script src="/public/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
        <script src="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!--Moment-->
        <script src="/public/assets/plugins/moment/moment.js"></script>


        <!--Mine scripts-->

        <script type="module" src="/<?=PUBLIC_JS_FOLDER?>/custom/frontend/pages/landing/home.js"></script>

</body>
</html>


