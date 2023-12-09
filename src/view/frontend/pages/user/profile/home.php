<?php require_once VIEW_COMMON."pages/user/profile/header/head.php"?>
<link href="/public/css/custom/common/pages/page-profile.css" rel="stylesheet"/>
<!-- InternalFileupload css-->
<!--<link href="/public/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css"/>-->

<!-- InternalFancy uploader css-->
<!--<link href="/public/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />-->


<link href="/public/css/custom/common/pages/user/profile.css" rel="stylesheet" />
<!--<link href="/public/css/custom/common/pages/user/modal-confirmation.css" rel="stylesheet" />-->

<!-- Select2 css -->
<link href="/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<!-- Internal Daterangepicker css-->
<link href="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

<body class="ltr main-body leftmenu">

<!-- Page -->
<div class="page">

    <!-- preloader start -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- preloader end -->


    <!-- Main Header-->
    <?php require_once VIEW_COMMON."pages/user/profile/header/header.php"?>
    <!-- End Main Header-->

    <!-- Sidemenu -->
    <div class="sticky">
        <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
            <div class="main-sidebar-header main-container-1 active">
                <div class="main-sidebar-body main-body-1">
                    <div class="slide-left disabled" id="slide-left"><i class="fe fe-chevron-left"></i></div>
                    <?php require_once VIEW_COMMON."pages/user/profile/menu/menu.php"?>
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
                    <?php require_once VIEW_COMMON."pages/landing/toolbar/info_social_topbar.php"?>
                    <!-- ttm-topbar-wrapper end -->

                    <?php require_once VIEW_COMMON."pages/landing/menu/menu.php"?>
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
                                     src="/public/images/custom/system/nophoto.jpg" alt="user-photo"/>
                            </div>
<!--                            <input type="file" class="dropify upload-img" id="user-photo-input"-->
<!--                                   accept=".jpg"-->
<!--                                   name="user-photo" data-height="150"-->
<!--                                   data-default-file="/public/images/custom/system/nophoto.jpg"-->
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
                    <div class="col-lg-3">
                        <a href="/web/user/profile/settings">
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

                    <div class="col-lg-3">
                        <a href="/web/user/profile/history">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto tx-white">
                                    <i class="fa fa-history" data-bs-toggle="tooltip" title=""
                                       data-bs-original-title="fa fa-history"
                                       aria-label="fa fa-history"></i>
                                    History
                                </div>
                                <div class="card-body">
                                    <p class="mg-b-0"></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3">
                        <a href="/web/user/profile/statistics">
                            <div class="card custom-card">
                                <div class="card-header p-3 tx-medium my-auto tx-white">
                                    <i class="ti-bar-chart-alt sidemenu-icon menu-icon "></i>
                                    Statistics
                                </div>
                                <div class="card-body">
                                    <p class="mg-b-0"></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    </div>

                <div class="row row-sm appointments-wrapper mt-40">
                    <h3 class="pl-30 title">Upcoming Appointments</h3>
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-1 mg-l-15 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <?php require_once VIEW_COMMON."pages/user/profile/blocks/select_pagination_count.php"?>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <?php require_once VIEW_COMMON."pages/user/profile/blocks/data_loader_gif.php"?>
                            </div>
                            <?php require_once VIEW_COMMON."pages/user/profile/tables/appointments_table.php"?>
                        </div>
                        <ul class="pagination mt-4 mb-0 float-end">
                        </ul>
                    </div>
                </div>

                <div class="row row-sm available-schedules mt-40 mb-40">
                    <h3 class="pl-15">Search Available Schedules for Appointments</h3>
                    <div class="card-body">

                        <div class="row row-sm">
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
                                <div class="form-group worker-wrapper">
                                    <p class="mg-b-0">Worker Name</p>
                                    <select class="form-control select2-with-search"
                                            id="worker-name"
                                    >
                                        <option label="Choose one">
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
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
                            <div class="col-lg-4">
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
                                            </select>
                                            <div class="error" id="end-time-select-error"></div>
                                        </div>
                                    </div>
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

                        <div class="row row-sm">
                            <div class="col-lg-12 panel panel-primary tabs-style-2 mg-t-50"
                                  id="main-schedule-wrapper">
<!--                                JS generate schedule-->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Row -->
            </div>
            </div>
                <!--footer start-->
                <?php require_once VIEW_COMMON."pages/landing/footer/footer_widget.php"?>
                <!--footer end-->

                <!--back-to-top start-->
                <a id="totop" href="#top">
                    <i class="fa fa-angle-up"></i>
                </a>
                <!--back-to-top end-->
            </div><!-- page end -->
    </div>
    <!-- End Main Content-->

<?php require_once VIEW_COMMON."pages/user/profile/modals/modal-confirmation.php" ?>
<!--</div>-->
<!-- End Page -->

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

<!-- Perfect-scrollbar js -->
<script src="/public/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- Sidemenu js -->
<script src="/public/assets/plugins/sidemenu/sidemenu.js" id="leftmenu"></script>

<!-- Color Theme js -->
<script src="/public/assets/js/themeColors.js"></script>

<!-- Custom js -->
<script src="/public/assets/js/custom.js"></script>

<?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>
<!-- Select2 js-->
<script src="/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="/public/assets/js/select2.js"></script>

<!-- Internal Form-elements js-->
<script src="/public/assets/js/advanced-form-elements.js"></script>

<!-- Sticky js -->
<script src="/public/assets/js/sticky.js"></script>

<!-- Internal Fileuploads js-->
<script src="/public/assets/plugins/fileuploads/js/fileupload.js"></script>
<script src="/public/assets/plugins/fileuploads/js/file-upload.js"></script>

<!-- Internal Daternagepicker js-->
<script src="/public/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script src="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!--Moment-->
<script src="/public/assets/plugins/moment/moment.js"></script>


<!--Mine scripts-->
<script src="/public/js/custom/common/pages/classes/builder/OptionBuilder.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/Renderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/TimeRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/DateRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/ScheduleRenderer.js"></script>

<script src="/public/js/custom/common/pages/classes/modal/ConfirmationModal.js"></script>

<script src="/public/js/custom/common/pages/classes/builder/ScheduleHtmlBuilder.js"></script>

<script src="/public/js/custom/common/pages/classes/cookie/Cookie.js"></script>

<script src="/public/js/custom/common/pages/classes/loader/GifLoader.js"></script>
<script src="/public/js/custom/common/pages/classes/loader/TableLoader.js"></script>

<script src="/public/js/custom/common/pages/classes/table/Table.js"></script>
<script src="/public/js/custom/common/pages/classes/table/extends/AppointmentsTable.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/user/forms/SearchScheduleForm.js"></script>

<script src="/public/js/custom/common/pages/classes/notifier/Notifier.js"></script>
<script src="/public/js/custom/common/pages/classes/requester/Requester.js"></script>
<script src="/public/js/custom/common/pages/user/profile/User.js"></script>

<script src="/public/js/custom/frontend/pages/user/profile/home.js"></script>

</body>
</html>