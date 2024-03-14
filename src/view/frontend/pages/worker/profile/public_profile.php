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

    <!-- page title -->
    <div class="ttm-page-title-row">
        <div class="container">
            <div class="row">
                <div class="col-md-12" id="profile-title-block">
                    <!--                        <div class="title-box text-center">-->
                    <!--                            <div class="page-title-heading">-->
                    <!--                                <h1>DANIEL TAYLER</h1>-->
                    <!--                            </div>&lt;!&ndash; /.page-title-captions &ndash;&gt;-->
                    <!--                            <div class="breadcrumb-wrapper">-->
                    <!--                                <span><a title="Homepage" href="index.html">home</a></span>-->
                    <!--                                <span class="ttm-bread-sep">&nbsp; / &nbsp;</span>-->
                    <!--                                <span>Daniel Tayler</span>-->
                    <!--                            </div>  -->
                    <!--                        </div>-->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- page title -->

    <!--site-main start-->
    <div class="site-main">
        <!-- team detail-section -->
        <section class="ttm-row team-detail-section clearfix" id="profile-detail-block">
            <!--                <div class="container">-->
            <!--                    <div class="bg-img9 tm-team-member-single-content-wrapper">-->
            <!--                        <div class="row">-->
            <!--                            <div class="col-md-6">-->
            <!--                                <div class="featured-thumbnail pl-50 res-767-pl-0">&lt;!&ndash; featured-thumbnail &ndash;&gt;-->
            <!--                                    <img class="img-fluid mb_50 res-767-mb-15" src="images/team-member/team-img03.jpg" alt="image">-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                            <div class="col-md-6 d-flex align-items-center">-->
            <!--                                <div class="ttm-team-member-single-list res-767-pl-15">-->
            <!--                                    <h2 class="ttm-team-member-single-title">TOMMY ATKINS</h2>-->
            <!--                                    <h5 class="ttm-team-member-single-position ttm-textcolor-skincolor">Barber Stylist</h5>-->
            <!--                                    <p><b>Phone :</b> &nbsp;(+01) 123 456 7890</p>-->
            <!--                                    <p><b>Email :</b> &nbsp;infoyourname@gmail.com</p>-->
            <!--                                    <p><b>Age :</b>&nbsp;29 Years</p>-->
            <!--                                    <p><b>Experience :</b> &nbsp;6+ years</p>-->
            <!--                                    <div class="team-media-block">-->
            <!--                                        <ul class="social-icons list-inline pt-20 res-767-p-0">-->
            <!--                                            <li><a href="#" class=" tooltip-top" data-tooltip="Facebook"><i class="fa fa-facebook"></i></a>-->
            <!--                                            </li>-->
            <!--                                            <li><a href="#" class=" tooltip-top" data-tooltip="Google+"><i class="fa fa-google-plus"></i></a>-->
            <!--                                            </li>-->
            <!--                                            <li><a href="#" class=" tooltip-top" data-tooltip="Instagram"><i class="fa fa-instagram"></i></a>-->
            <!--                                            </li>-->
            <!--                                            <li><a href="#" class=" tooltip-top" data-tooltip="Youtube"><i class="fa fa-youtube-play"></i></a>-->
            <!--                                            </li>-->
            <!--                                        </ul>-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>   -->
            <!--                    </div>                 -->
            <!--                </div>-->
        </section>
        <!-- team detail-section end-->
        <!-- history-section -->
        <section class="ttm-row history-section pt-0 res-767-pt-0" id="profile-description-block">
            <!--                <div class="container">-->
            <!--                    <div class="row">-->
            <!--                        <div class="col-12">-->
            <!--                            <h3 class="ttm-team-member-single-title pt-10 pb-10 res-575-p-0">MY HISTORY</h3>-->
            <!--                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>-->
            <!--                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised</p>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
        </section>
        <!-- history-section end -->
    </div>
    <!--site-main end-->

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

<!--Mine scripts-->

<script type="module" src="/public/js/custom/frontend/pages/worker/profile/public_profile.js"></script>

</body>
</html>