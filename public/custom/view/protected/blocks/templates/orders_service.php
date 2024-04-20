<?php require_once VIEW_PROTECTED_BLOCKS."/header/full_header.php"?>

<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/pages/worker/schedule.css" rel="stylesheet" />
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/date-month-year-picker/css/date-picker.css">

<link rel="stylesheet" href="/<?=CUSTOM_ASSETS?>/css/pages/protected/common/checkbox-row.css">


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
                    <?php require_once VIEW_PROTECTED_PAGES."/$role/blocks/menu/menu.php"?>
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
            </div><!-- ttm-stickable-header-w end-->

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

                <div class="row row-sm available-schedules mb-40">
                    <h3 class="">Search The History Of Services' Orders</h3>
                    <?php require_once VIEW_PROTECTED_BLOCKS . '/forms/orders-search-form.php';?>
                </div>

                <div class="row row-sm appointments-wrapper mt-40">
                    <h3 class="pl-30 title">
                        Total Sum:
                        <span><b id="total-orders-sum">0 UAH</b></span>
                    </h3>
                    <h3 class="pl-30 title">
                        Total Count:
                        <span><b id="total-orders-count">0</b></span>
                    </h3>
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-1 mg-l-15 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <?php require_once VIEW_PROTECTED_BLOCKS."/units/select_pagination_count.php"?>
                                </div>
                            </div>
                            <div class="buttons-wrapper d-flex col-lg-6" id="action-buttons-wrapper">

                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <?php require_once VIEW_PROTECTED_BLOCKS."/units/data_loader_gif.php";?>
                            </div>
                            <?php require_once VIEW_PROTECTED_BLOCKS."/tables/orders_table.php";?>
                        </div>
                        <ul class="pagination mt-4 mb-0 float-end">
                        </ul>
                    </div>
                </div>

                <!-- End Row -->
            </div>
        </div>
        <!--footer start-->
        <?php require_once VIEW_OPEN_BLOCKS."/footer/footer_widget.php"?>
        <!--footer end-->

        <!--back-to-top start-->
        <a id="totop" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
        <!--back-to-top end-->
    </div>
    <!-- End Main Content-->

    <?php require_once VIEW_PROTECTED_BLOCKS . "/modals/modal-confirmation.php" ?>
    <!-- End Page -->

    <?php require_once VIEW_PROTECTED_BLOCKS . '/footer/full_footer.php'?>

    <!-- Bootstrap js-->
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap/js/popper.min.js"></script>

    <!-- Internal Daternagepicker js-->
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!--Moment-->
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/moment/moment.js"></script>
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/moment/min/moment.min.js"></script>

    <!--Datepicker plugin-->
    <script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/date-month-year-picker/js/date-picker.js"></script>

    <!--Mine scripts-->
    <script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/<?=$role?>/ui/order/service/orders.js"></script>

</body>
</html>