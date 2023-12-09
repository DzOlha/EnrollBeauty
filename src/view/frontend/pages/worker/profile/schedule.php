
<?php require_once VIEW_COMMON."pages/user/profile/header/head.php"?>
<link href="/public/css/custom/common/pages/page-profile.css" rel="stylesheet"/>

<link href="/public/css/custom/common/pages/user/profile.css" rel="stylesheet" />
<link href="/public/css/custom/common/pages/admin/table-button-search.css" rel="stylesheet" />
<link href="/public/css/custom/common/pages/admin/modal-form.css" rel="stylesheet" />
<!--<link href="/public/css/custom/common/pages/worker/timepicker.css" rel="stylesheet" />-->
<link href="/public/css/custom/common/pages/worker/schedule.css" rel="stylesheet" />

<!-- Select2 css -->
<link href="/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<!-- Internal Daterangepicker css-->
<link href="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

<!--Bootstrap-datepicker css-->
<!--<link rel="stylesheet" href="/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css">-->

<!--Range timepicker-->
<!--<link rel="stylesheet" href="/public/assets/plugins/range-picker-scale/jquery.timescale.css">-->

<!--Datepicker plugin-->
<link rel="stylesheet" href="/public/assets/plugins/date-month-year-picker/css/date-picker.css">

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
                    <?php require_once VIEW_COMMON."pages/worker/profile/menu/menu.php"?>
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

                <div class="row row-sm available-schedules mb-40">
                    <h3 class="">My Ordered Schedule</h3>
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
<!-- End Page -->

<!-- Jquery js-->
<script src="/public/assets/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap js-->
<script src="/public/assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="/public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Moment js-->
<script src="/public/assets/plugins/moment/min/moment.min.js"></script>

<!-- Jquery-Ui js-->
<!--<script src="/public/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>-->

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

<!-- Sticky js -->
<script src="/public/assets/js/sticky.js"></script>

<!-- Internal Daternagepicker js-->
<script src="/public/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script src="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>

<!--Bootstrap-datepicker js-->
<!--<script src="/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>-->

<!--Moment-->
<script src="/public/assets/plugins/moment/moment.js"></script>

<!--Timepicker-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.14.1/jquery.timepicker.min.js"></script>-->

<!-- Internal Form-elements js-->
<script src="/public/assets/js/advanced-form-elements.js"></script>

<!--Range timepicker-->
<!--<script src="/public/assets/plugins/range-picker-scale/jquery.timescale.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.10/dayjs.min.js" integrity="sha512-FwNWaxyfy2XlEINoSnZh1JQ5TRRtGow0D6XcmAWmYCRgvqOUTnzCxPc9uF35u5ZEpirk1uhlPVA19tflhvnW1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script src="/public/assets/plugins/easy-time-picker-bootstrap/src/timepicker-bs4.js"></script>-->


<!--Bootstrap timepicker-->
<!--<script src="/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>-->


<!--Datepicker plugin-->
<script src="/public/assets/plugins/date-month-year-picker/js/date-picker.js"></script>




<!--Mine scripts-->
<script src="/public/js/custom/common/pages/classes/builder/OptionBuilder.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/Renderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/TimeRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/DateRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/ScheduleRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/WorkerScheduleRenderer.js"></script>

<script src="/public/js/custom/common/pages/classes/modal/ConfirmationModal.js"></script>

<script src="/public/js/custom/common/pages/classes/builder/ScheduleHtmlBuilder.js"></script>
<script src="/public/js/custom/common/pages/classes/builder/WorkerScheduleHtmlBuilder.js"></script>

<script src="/public/js/custom/common/pages/classes/cookie/Cookie.js"></script>

<script src="/public/js/custom/common/pages/classes/table/Table.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/user/forms/SearchScheduleForm.js"></script>
<script src="/public/js/custom/common/pages/worker/forms/WorkerSearchScheduleForm.js"></script>

<script src="/public/js/custom/common/pages/classes/loader/GifLoader.js"></script>
<script src="/public/js/custom/common/pages/classes/loader/TableLoader.js"></script>

<script src="/public/js/custom/common/pages/classes/notifier/Notifier.js"></script>
<script src="/public/js/custom/common/pages/classes/requester/Requester.js"></script>
<script src="/public/js/custom/common/pages/user/profile/User.js"></script>
<script src="/public/js/custom/common/pages/worker/profile/Worker.js"></script>

<!--Modal Add Schedule-->
<script src="/public/js/custom/common/pages/classes/modal/FormModal.js"></script>
<script src="/public/js/custom/common/pages/classes/builder/FormBuilder.js"></script>
<script src="/public/js/custom/common/pages/worker/forms/AddScheduleForm.js"></script>

<script src="/public/js/custom/frontend/pages/worker/profile/schedule.js"></script>

</body>
</html>