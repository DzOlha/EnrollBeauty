<?php require_once VIEW_COMMON."pages/user/profile/header/head.php"?>
<link href="/public/css/custom/common/pages/page-profile.css" rel="stylesheet"/>
<!-- InternalFileupload css-->
<!--<link href="/public/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css"/>-->

<!-- InternalFancy uploader css-->
<!--<link href="/public/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />-->


<link href="/public/css/custom/common/pages/user/profile.css" rel="stylesheet" />
<link href="/public/css/custom/common/pages/admin/workers.css" rel="stylesheet" />
<link href="/public/css/custom/common/pages/admin/modal-form.css" rel="stylesheet" />

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
                <div class="row row-sm table-wrapper">
                    <!--                    <h3 class="pl-30 title">Table</h3>-->
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-3 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <?php require_once VIEW_COMMON."pages/user/profile/blocks/select_pagination_count.php"?>
                                </div>
                                <div class="button-wrapper mg-l-10">
                                    <button aria-label="Add Worker"
                                            class="btn ripple pd-x-25"
                                            id="add-pricing-trigger"
                                            data-bs-dismiss="modal" type="button">
                                        Add Pricing Item
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-9 d-lg-flex justify-content-end">
                                <div class="d-flex mt-4 mt-lg-0 filter-wrapper">
                                    <div class="mass-action-wrapper">
                                        <!--                                        --><?php //require_once "tmp/admin/panel/common/html/type_filter.html";?>
                                        <!---->
                                        <!--                                        --><?php //require_once "tmp/admin/panel/common/html/dropdown_status_filter.html";?>
                                        <!---->
                                        <!--                                        --><?php //require_once "tmp/admin/panel/common/html/dropdown_select_mass_by.html";?>
                                        <!---->
                                        <!--                                        --><?php //require_once "tmp/admin/panel/common/html/dropdown_select_mass_action.html";?>
                                        <!---->
                                        <!--                                        --><?php //require_once "tmp/admin/panel/common/html/confirm_mass_action_button.html";?>
                                    </div>

                                    <?php require_once "src/view/common/pages/admin/profile/blocks/search_by.php";?>


                                </div>
                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <?php require_once VIEW_COMMON."pages/user/profile/blocks/data_loader_gif.php"?>
                            </div>
                            <?php require_once VIEW_COMMON."pages/worker/profile/tables/pricing_table.php"?>
                        </div>
                        <ul class="pagination mt-4 mb-0 float-end">
                        </ul>
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

<?php //require_once VIEW_COMMON."pages/admin/profile/modals/modal-add-worker.php" ?>
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

<!--<script nomodule src="/public/js/custom/common/libs/jquery.powertip.min.js"></script>-->
<!--Mine scripts-->
<script src="/public/js/custom/common/pages/classes/builder/OptionBuilder.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/Renderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/TimeRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/DateRenderer.js"></script>
<!--<script src="/public/js/custom/common/pages/classes/renderer/extends/ScheduleRenderer.js"></script>-->

<script src="/public/js/custom/common/pages/classes/modal/FormModal.js"></script>

<script src="/public/js/custom/common/pages/classes/builder/FormBuilder.js"></script>

<script src="/public/js/custom/common/pages/classes/cookie/Cookie.js"></script>

<script src="/public/js/custom/common/pages/classes/table/Table.js"></script>
<script src="/public/js/custom/common/pages/classes/table/extends/PricingTable.js"></script>

<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/worker/forms/AddPricingForm.js"></script>

<script src="/public/js/custom/common/pages/classes/notifier/Notifier.js"></script>
<script src="/public/js/custom/common/pages/classes/requester/Requester.js"></script>
<script src="/public/js/custom/common/pages/user/profile/User.js"></script>
<script src="/public/js/custom/common/pages/worker/profile/Worker.js"></script>

<script src="/public/js/custom/frontend/pages/worker/profile/pricing.js"></script>

</body>
</html>