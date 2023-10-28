<?php require_once VIEW_COMMON."pages/landing/header/head.php"?>
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
            <?php require_once VIEW_COMMON."pages/landing/sections/about_beautysalon_section.php"?>
            <!-- about-beautysalloon section end -->
            <!-- pricelist-section-section -->
            <?php require_once VIEW_COMMON."pages/landing/sections/pricelist_section.php"?>
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
            <?php require_once VIEW_COMMON."pages/landing/sections/contact_form_section.php"?>
             <!-- contact-section  -->
            <!-- news-section  -->
            <?php require_once VIEW_COMMON."pages/landing/sections/news_section.php"?>
            <!-- news-section end -->

        </div>
        <!--site-main end-->

        <!--footer start-->
        <?php require_once VIEW_COMMON."pages/landing/footer/footer.php"?>
        <!--footer end-->

        <!--back-to-top start-->
        <a id="totop" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
        <!--back-to-top end-->

    </div>
    <!-- page end -->

    <?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>

</body>
</html>