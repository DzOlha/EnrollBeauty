<?php require_once VIEW_PROTECTED_BLOCKS."/header/head.php"?>

<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/common/page-profile.css" rel="stylesheet"/>
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/pages/user/profile.css" rel="stylesheet" />

<body class="ltr main-body leftmenu">

<!-- Page -->
<div class="page">

    <!-- preloader start -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- preloader end -->

    <!-- Main Header-->
    <?php require_once VIEW_PROTECTED_BLOCKS."/header/header.php"?>
    <!-- End Main Header-->

    <!-- Sidemenu -->
    <div class="sticky">
        <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
            <div class="main-sidebar-header main-container-1 active">
                <div class="main-sidebar-body main-body-1">
                    <div class="slide-left disabled" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                    <?php require_once VIEW_PROTECTED_PAGES."/user/blocks/menu/menu.php"?>
                    <div class="slide-right" id="slide-right"><i class="fe fe-chevron-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content-->
    <div class="main-content side-content pt-0">
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

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5"><?=$data['page_name']?></h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><?=$data['title']?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?=$data['page_name']?></li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">
                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Row -->
                <div class="row row-sm">
                    <div class="col-sm-12 col-md-8 profile-homepage-user-info">
                        <div class="col-sm-12 col-md-12 user-info-wrapper">
                            <div class="user-img-wrapper">
                                <img id="user-img-large" class="user-img"
                                     src="<?=NO_PHOTO?>" alt="user-photo"/>
                            </div>
<!--                            <input type="file" class="dropify upload-img" id="user-photo-input"-->
<!--                                   accept=".jpg"-->
<!--                                   name="user-photo" data-height="150"-->
<!--                                   data-default-file="<?=NO_PHOTO?>"-->
<!--                                   disabled="disabled"-->
<!--                            />-->
                            <div class="user-name-surname-wrapper">
                                <span id="name-large">Name</span>
                                <span id="surname-large">Surname</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-xs mt-10 gx-20 cards-wrapper">
                    <div class="col-lg-4">
                        <a href="<?=API['USER']['WEB']['PROFILE']['settings']?>">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto tx-white">
                                    <i class="si si-settings" data-bs-toggle="tooltip" title=""
                                       data-bs-original-title="si-settings" aria-label="si-settings"></i>
                                    Settings
                                </div>
                                <div class="card-body">
                                    <p class="mg-b-0"></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4">
                        <a href="<?=API['USER']['WEB']['PROFILE']['orders']?>">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto tx-white">
                                    <i class="ti-shopping-cart-full sidemenu-icon menu-icon"></i>
                                    Orders History
                                </div>
                                <div class="card-body">
                                    <p class="mg-b-0"></p>
                                </div>
                            </div>
                        </a>
                    </div>

<!--                    <div class="col-lg-3">-->
<!--                        <a href="/web/user/profile/statistics">-->
<!--                            <div class="card custom-card">-->
<!--                                <div class="card-header p-3 tx-medium my-auto tx-white">-->
<!--                                    <i class="ti-bar-chart-alt sidemenu-icon menu-icon "></i>-->
<!--                                    Statistics-->
<!--                                </div>-->
<!--                                <div class="card-body">-->
<!--                                    <p class="mg-b-0"></p>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a>-->
<!--                    </div>-->
                    </div>

                <div class="row row-sm appointments-wrapper mt-40">
                    <h3 class="pl-30 title">Upcoming Appointments</h3>
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-1 mg-l-15 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <?php require_once VIEW_PROTECTED_BLOCKS."/units/select_pagination_count.php"?>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <?php require_once VIEW_PROTECTED_BLOCKS."/units/data_loader_gif.php";?>
                            </div>
                            <?php require_once VIEW_PROTECTED_PAGES."/user/blocks/tables/appointments_table.php";?>
                        </div>
                        <ul class="pagination mt-4 mb-0 float-end">
                        </ul>
                    </div>
                </div>

                <?php require_once VIEW_PROTECTED_BLOCKS . "/forms/schedule-search-form.php";?>

                <!-- End Row -->
            </div>
            </div>
                <!--footer start-->
                <?php require_once VIEW_OPEN_BLOCKS."/footer/footer_widget.php"?>
                <!--footer end-->

                <!--back-to-top start-->
                <?php require_once VIEW_OPEN_BLOCKS . '/units/totop.php'?>
                <!--back-to-top end-->
        </div><!-- page end -->
    </div>
    <!-- End Main Content-->

<?php require_once VIEW_PROTECTED_BLOCKS . "/modals/modal-confirmation.php" ?>
<!--</div>-->
<!-- End Page -->

<?php require_once VIEW_PROTECTED_BLOCKS . '/footer/full_footer.php'?>

<!-- Internal Daternagepicker js-->
<script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!--Mine scripts-->
<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/user/ui/home.js"></script>

</body>
</html>