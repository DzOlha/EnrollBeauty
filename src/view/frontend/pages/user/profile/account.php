<?php require_once VIEW_COMMON."pages/user/profile/header/head.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
        top: 0;
        z-index: 2000;
        padding-left: 10px;
    }
</style>
<!-- InternalFileupload css-->
<!--<link href="/public/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css"/>-->

<!-- InternalFancy uploader css-->
<!--<link href="/public/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />-->


<link href="/public/css/custom/common/pages/user/profile.css" rel="stylesheet" />

<!-- Select2 css -->
<link href="/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">

<!-- Internal Daterangepicker css-->
<link href="/public/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">


<body class="ltr main-body leftmenu">

<!-- Loader -->
<div id="global-loader">
    <img src="/public/assets/img/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- End Loader -->

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
                    <h3 class="pl-30 title">Coming Appointments</h3>
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-1 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <select class="form-control select2 wd-50">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>15</option>
                                        <option>20</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <div class="card custom-card" id="dataTableLoader">
                                    <div class="card-body">
                                        <div>
                                            <h6 class="main-content-label mb-1">Please wait</h6>
                                            <p class="text-muted card-sub-title">information is loading...</p>
                                        </div>
                                        <div class="text-center">
                                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="data-table"
                                   class="table card-table table-striped table-vcenter text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="wd-lg-8p">
                                        <span class="arrow_column active">
                                            ID
                                            <img src="/public/images/custom/system/icons/arrows_down.svg"
                                                 id="id_arrow"
                                                 class="sort_arrow"
                                                 data-column="id" data-order="asc">
                                        </span>
                                    </th>
                                    <th class="wd-lg-8p">
                                        <span class="arrow_column">
                                            Service
                                            <img src="" id="img_arrow" class="sort_arrow"
                                                 data-column="name" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-20p">
                                        <span class="arrow_column">
                                            Worker
                                            <img src="" id="login_arrow" class="sort_arrow"
                                                 data-column="login" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-20p">
                                        <span class="arrow_column">
                                            Affiliate Address
                                            <img src="" id="email_arrow" class="sort_arrow"
                                                 data-column="email" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-10p">
                                        <span class="arrow_column">
                                            Date
                                            <img src="" id="email_arrow" class="sort_arrow"
                                                 data-column="email" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-2p">
                                        <span class="arrow_column">
                                            Start
                                            <img src="" id="email_arrow" class="sort_arrow"
                                                 data-column="email" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-2p">
                                        <span class="arrow_column">
                                            End
                                            <img src="" id="email_arrow" class="sort_arrow"
                                                 data-column="email" data-order="">
                                        </span>
                                    </th>
                                    <th class="wd-lg-10p">
                                        <span class="arrow_column">
                                            Price
                                            <img src="" id="created_arrow" class="sort_arrow"
                                                 data-column="time_reg" data-order="">
                                        </span>
                                    </th>

                                    <th class="wd-lg-5p">
                                        <span class="arrow_column">
                                            Action
                                        </span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="table-body">
                                <tr>
                                    <td>1</td>
                                    <td>Brown Coloring</td>
                                    <td>Irena Karpa</td>
                                    <td>Kyiv, str. Independent 89</td>
                                    <td>2 November</td>
                                    <td>12:00</td>
                                    <td>13:30</td>
                                    <td>30 USD</td>
                                    <td>
                                        <a class="btn ripple btn-manage"
                                           href="">
                                            <i class="fe fe-eye me-2"></i>
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Brown Coloring</td>
                                    <td>Irena Karpa</td>
                                    <td>Kyiv, str. Freedom 33</td>
                                    <td>7 November</td>
                                    <td>12:00</td>
                                    <td>13:30</td>
                                    <td>22 USD</td>
                                    <td>
                                        <a class="btn ripple btn-manage"
                                           href="">
                                            <i class="fe fe-eye me-2"></i>
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Brown Coloring</td>
                                    <td>Irena Karpa</td>
                                    <td>Kyiv, str. Brave 13</td>
                                    <td>15 November</td>
                                    <td>12:00</td>
                                    <td>13:30</td>
                                    <td>29 USD</td>
                                    <td>
                                        <a class="btn ripple btn-manage"
                                           href="">
                                            <i class="fe fe-eye me-2"></i>
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <ul class="pagination mt-4 mb-0 float-end">
                        </ul>
                    </div>
                </div>

                <div class="row row-sm available-schedules mt-40 mb-40">
                    <h3 class="pl-15">Available Schedule for Appointments</h3>
                    <div class="card-body">

                        <div class="row row-sm">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <p class="mg-b-0">Service Name</p>
                                    <select class="form-control select2-with-search">
                                        <option label="Choose one">
                                        </option>
                                        <option value="Firefox">
                                            Firefox
                                        </option>
                                        <option value="Chrome">
                                            Chrome
                                        </option>
                                        <option value="Safari">
                                            Safari
                                        </option>
                                        <option value="Opera">
                                            Opera
                                        </option>
                                        <option value="Internet Explorer">
                                            Internet Explorer
                                        </option>
                                    </select>
                                    </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <p class="mg-b-0">Worker Name</p>
                                    <select class="form-control select2-with-search">
                                        <option label="Choose one">
                                        </option>
                                        <option value="Firefox">
                                            Firefox
                                        </option>
                                        <option value="Chrome">
                                            Chrome
                                        </option>
                                        <option value="Safari">
                                            Safari
                                        </option>
                                        <option value="Opera">
                                            Opera
                                        </option>
                                        <option value="Internet Explorer">
                                            Internet Explorer
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <p class="mg-b-0">Affiliate</p>
                                    <select class="form-control select2-with-search">
                                        <option label="Choose one">
                                        </option>
                                        <option value="Firefox">
                                            Firefox
                                        </option>
                                        <option value="Chrome">
                                            Chrome
                                        </option>
                                        <option value="Safari">
                                            Safari
                                        </option>
                                        <option value="Opera">
                                            Opera
                                        </option>
                                        <option value="Internet Explorer">
                                            Internet Explorer
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
                                           class="form-control pull-right"
                                           id="reservation"
                                           required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row row-sm time-range">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <p class="mg-b-0">Start time</p>
                                            <select class="form-control select2">
                                                <option label="Choose one">
                                                </option>
                                                <option value="9">
                                                    09:00
                                                </option>
                                                <option value="10">
                                                    10:00
                                                </option>
                                                <option value="11">
                                                    11:00
                                                </option>
                                                <option value="12">
                                                    12:00
                                                </option>
                                                <option value="13">
                                                    13:00
                                                </option>
                                                <option value="14">
                                                    14:00
                                                </option>
                                                <option value="15">
                                                    15:00
                                                </option>
                                                <option value="16">
                                                    16:00
                                                </option>
                                                <option value="17">
                                                    17:00
                                                </option>
                                                <option value="18">
                                                    18:00
                                                </option>
                                        </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <p class="mg-b-0">End time</p>
                                            <select class="form-control select2 col-lg-2">
                                                <option label="Choose one">
                                                </option>
                                                <option value="9">
                                                    09:00
                                                </option>
                                                <option value="10">
                                                    10:00
                                                </option>
                                                <option value="11">
                                                    11:00
                                                </option>
                                                <option value="12">
                                                    12:00
                                                </option>
                                                <option value="13">
                                                    13:00
                                                </option>
                                                <option value="14">
                                                    14:00
                                                </option>
                                                <option value="15">
                                                    15:00
                                                </option>
                                                <option value="16">
                                                    16:00
                                                </option>
                                                <option value="17">
                                                    17:00
                                                </option>
                                                <option value="18">
                                                    18:00
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <p class="mg-b-0">Price from</p>
                                    <div class="input-group mb-3">
                                        <input aria-label="Amount (to the nearest dollar)"
                                               class="form-control" type="number">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bd-r">грн</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <p class="mg-b-0">Price to</p>
                                    <div class="input-group mb-3">
                                        <input aria-label="Amount (to the nearest dollar)"
                                               class="form-control" type="number">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bd-r">грн</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <p class="mg-b-25"></p>
                                <button class="btn btn-block"
                                        id="submit-search-button">
                                    Search
                                </button>
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

</div>
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


<!--Mine scripts-->
<script src="/public/js/custom/common/pages/classes/renderer/Renderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/TimeRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/DateRenderer.js"></script>

<script src="/public/js/custom/common/pages/Cookie.js"></script>

<script src="/public/js/custom/common/pages/Table.js"></script>
<script src="/public/js/custom/common/pages/user/profile/AppointmentsTable.js"></script>

<script src="/public/js/custom/common/pages/Notifier.js"></script>
<script src="/public/js/custom/common/pages/Requestor.js"></script>
<script src="/public/js/custom/common/pages/user/profile/Account.js"></script>

<script src="/public/js/custom/frontend/pages/user/profile/account.js"></script>

</body>
</html>