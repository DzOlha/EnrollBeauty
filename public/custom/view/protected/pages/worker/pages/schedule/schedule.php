<?php require_once VIEW_PROTECTED_BLOCKS."/header/full_header.php"?>

<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/pages/worker/schedule.css" rel="stylesheet" />
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<link rel="stylesheet" href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/date-month-year-picker/css/date-picker.css">

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
                    <?php require_once VIEW_PROTECTED_PAGES."/worker/blocks/menu/menu.php"?>
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
                <h3 class="">My Schedule</h3>
                <div class="card-body">
                    <div class="row row-sm justify-content-start">
                        <div class="col-lg-4">
                            <div class="form-group service-wrapper">
                                <p class="mg-b-0">Service Name</p>
                                <select class="form-control select2-with-search"
                                        id="service-name"
                                >
                                    <option label="Choose one">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <p class="mg-b-0">Affiliate</p>
                                <select class="form-control select2-with-search"
                                        id="affiliate-name-address"
                                >
                                    <option label="Choose one">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <p class="mg-b-0">Dates</p>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fe fe-calendar"></i>
                                    </div>
                                </div>
                                <input type="text"
                                       class="form-control pull-right date-range"
                                       id="date-range-input"
                                       required>
                            </div>
                            <div class="error" id="date-range-input-error"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row row-sm time-range">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p class="mg-b-0">Start time</p>
                                        <select class="form-control select2 col-lg-2"
                                                id="start-time"
                                        >
                                            <option label="Choose one">
                                            </option>
                                            <option value="9:00">
                                                09:00
                                            </option>
                                            <option value="10:00">
                                                10:00
                                            </option>
                                            <option value="11:00">
                                                11:00
                                            </option>
                                            <option value="12:00">
                                                12:00
                                            </option>
                                            <option value="13:00">
                                                13:00
                                            </option>
                                            <option value="14:00">
                                                14:00
                                            </option>
                                            <option value="15:00">
                                                15:00
                                            </option>
                                            <option value="16:00">
                                                16:00
                                            </option>
                                            <option value="17:00">
                                                17:00
                                            </option>
                                            <option value="18:00">
                                                18:00
                                            </option>
                                            <option value="19:00">
                                                19:00
                                            </option>
                                            <option value="20:00">
                                                20:00
                                            </option>
                                            <option value="21:00">
                                                21:00
                                            </option>
                                        </select>
                                        <div class="error" id="start-time-select-error"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <p class="mg-b-0">End time</p>
                                        <select class="form-control select2 col-lg-2"
                                                id="end-time"
                                        >
                                            <option label="Choose one">
                                            </option>
                                            <option value="9:00">
                                                09:00
                                            </option>
                                            <option value="10:00">
                                                10:00
                                            </option>
                                            <option value="11:00">
                                                11:00
                                            </option>
                                            <option value="12:00">
                                                12:00
                                            </option>
                                            <option value="13:00">
                                                13:00
                                            </option>
                                            <option value="14:00">
                                                14:00
                                            </option>
                                            <option value="15:00">
                                                15:00
                                            </option>
                                            <option value="16:00">
                                                16:00
                                            </option>
                                            <option value="17:00">
                                                17:00
                                            </option>
                                            <option value="18:00">
                                                18:00
                                            </option>
                                            <option value="19:00">
                                                19:00
                                            </option>
                                            <option value="20:00">
                                                20:00
                                            </option>
                                            <option value="21:00">
                                                21:00
                                            </option>
                                        </select>
                                        <div class="error" id="end-time-select-error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex">
                            <div class="form-group">
                                <label class="pd-t-30 custom-control custom-checkbox custom-control-md">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           name="make-lang-active"
                                           value=""
                                           checked
                                           id="only-ordered-checkbox">
                                    <span class="custom-control-label custom-control-label-md  tx-16">
                                                 Show ordered schedules
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-3 d-flex">
                            <div class="form-group">
                                <label class="pd-t-30 custom-control custom-checkbox custom-control-md">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           name="make-lang-active"
                                           value=""
                                           checked
                                           id="only-free-checkbox">
                                    <span class="custom-control-label custom-control-label-md  tx-16">
                                                Show free schedules
                                        </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <p class="mg-b-0">Price from</p>
                                <div class="input-group mb-3">
                                    <input aria-label="Amount (to the nearest dollar)"
                                           class="form-control" type="number"
                                           id="price-from"
                                    >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bd-r">грн</span>
                                    </div>
                                    <div class="error" id="price-from-input-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <p class="mg-b-0">Price to</p>
                                <div class="input-group mb-3">
                                    <input aria-label="Amount (to the nearest dollar)"
                                           class="form-control" type="number"
                                           id="price-to"
                                    >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bd-r">грн</span>
                                    </div>
                                    <div class="error" id="price-to-input-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <p class="mg-b-25"></p>
                            <button class="btn btn-block"
                                    type="button"
                                    id="submit-search-button">
                                Search
                            </button>
                        </div>
                    </div>
                    <div class="button-wrapper light mg-t-35">
                        <button aria-label="Add Worker"
                                class="btn ripple pd-x-25"
                                id="add-schedule-trigger"
                                data-bs-dismiss="modal" type="button">
                            Add Schedule Item
                        </button>
                    </div>

                    <div class="row row-sm">
                        <div class="col-lg-12 panel panel-primary tabs-style-2 mg-t-50"
                             id="main-schedule-wrapper">
                            <!--                                JS generate schedule-->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-sm available-schedules mt-40 mb-40">
                <h3 class="pl-15"></h3>
                <div class="card-body">

                </div>
            </div>

            <!-- End Row -->
        </div>
    </div>
    </div><!--ttm-header-wrap end -->
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
<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/worker/ui/schedule/schedule.js"></script>

</body>
</html>