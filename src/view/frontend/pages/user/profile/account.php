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
                                <div class="tab-menu-heading main-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line"
                                            id="departments-menu-wrapper">
                                            <li>
                                                <a href="#makeup"
                                                   class="nav-link active mt-1"
                                                   data-bs-toggle="tab"
                                                   id="userContactsTrigger">
                                                    Makeup
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#hair"
                                                   class="nav-link mt-1"
                                                   data-bs-toggle="tab"
                                                   id="userBalancesTrigger">
                                                    Hair Style
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#manicure"
                                                   class="nav-link mt-1"
                                                   data-bs-toggle="tab"
                                                   id="withdrawDetailsTrigger">
                                                    Manicure & Pedicure
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="makeup">
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="panel panel-primary tabs-style-3">
                                                        <div class="tab-menu-heading weekday-menu-heading">
                                                            <div class="tabs-menu">
                                                                <!-- Tabs -->
                                                                <ul class="nav panel-tabs me-3">
                                                                    <li class="">
                                                                        <a href="#tab21"
                                                                           class="active"
                                                                           data-bs-toggle="tab">
                                                                            Monday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab22"
                                                                           data-bs-toggle="tab"
                                                                           class="">
                                                                            Tuesday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab23"
                                                                           data-bs-toggle="tab"
                                                                           class="">
                                                                            Wednesday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab24"
                                                                           data-bs-toggle="tab">
                                                                            Thursday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab24"
                                                                           data-bs-toggle="tab">
                                                                            Friday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab24"
                                                                           data-bs-toggle="tab">
                                                                            Saturday
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#tab24"
                                                                           data-bs-toggle="tab">
                                                                            Sunday
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tabs-style-3">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tab21">
                                                            <div class="row row-sm time-separation-wrapper">
                                                                <div class="row row-sm">
                                                                    <div class="col-lg-3 time-interval-value"
                                                                         data-start-interval="9"
                                                                         data-end-interval="12"
                                                                    >
                                                                        9:00
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval-value"
                                                                         data-start-interval="12"
                                                                         data-end-interval="15"
                                                                    >
                                                                        12:00
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval-value"
                                                                         data-start-interval="15"
                                                                         data-end-interval="18"
                                                                    >
                                                                        15:00
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval-value"
                                                                         data-start-interval="18"
                                                                         data-end-interval="21"
                                                                    >
                                                                        18:00
                                                                    </div>
                                                                </div>
                                                                <div class="row row-sm time-interval-wrapper">
                                                                    <div class="col-lg-3 time-interval 9-12"
                                                                         data-start-interval="9"
                                                                         data-end-interval="12"
                                                                    >
                                                                        <div class="card">
                                                                            <div class="card-header custom-card-header border-bottom-0 ">
                                                                                <h5 class="main-content-label my-auto tx-medium mb-0">
                                                                                    Manicure
                                                                                </h5>
                                                                                <div class="card-options">
                                                                                    <i class="far fa-heart me-1"></i>
                                                                                    <i class="fe fe-shopping-cart"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex align-items-center pt-3 mt-auto
                                                                                       card-worker-wrapper">
                                                                                <!--                                                                                <div class="main-img-user avatar-sm me-3">-->
                                                                                <!--                                                                                    <img src="" class="w-10 rounded-circle" alt="avatar-img">-->
                                                                                <!--                                                                                </div>-->
                                                                                <div>
                                                                                    <span class="d-block text-muted">
                                                                                        <span>Price: </span>
                                                                                        <span class="price">500 UAH</span>
                                                                                    </span>
                                                                                    <div>

                                                                                        <span>Master: </span>
                                                                                        <span>
                                                                                            <a href="" class="text-default">Alica Nestle</a>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-schedule-content">
                                                                                <div class="time-value">
<!--                                                                                    <span>Date: </span>-->
                                                                                    <div class="date">
                                                                                        <i class="fe fe-calendar"></i>
<!--                                                                                        <span>Date: </span>-->
                                                                                        <span>5 November</span>
                                                                                    </div>
                                                                                    <div class="time">
                                                                                        <i class="fe fe-clock"></i>

<!--                                                                                        <span>Time: </span>-->
                                                                                        <span>
                                                                                            <span class="start-time">9:30</span>
                                                                                            <span>-</span>
                                                                                            <span class="end-time">11:00</span>
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="affiliate-address">
                                                                                        <i class="fe fe-map-pin"></i>
<!--                                                                                         <span>Address: </span>-->
                                                                                         <span class="address">
                                                                                             c. Kyiv, str. Freedom, 79
                                                                                         </span>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card">
                                                                            <div class="card-header custom-card-header border-bottom-0 ">
                                                                                <h5 class="main-content-label my-auto tx-medium mb-0">
                                                                                    Manicure
                                                                                </h5>
                                                                                <div class="card-options">
                                                                                    <i class="far fa-heart me-1"></i>
                                                                                    <i class="fe fe-shopping-cart"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex align-items-center pt-3 mt-auto
                                                                                       card-worker-wrapper">
                                                                                <!--                                                                                <div class="main-img-user avatar-sm me-3">-->
                                                                                <!--                                                                                    <img src="" class="w-10 rounded-circle" alt="avatar-img">-->
                                                                                <!--                                                                                </div>-->
                                                                                <div>
                                                                                    <span class="d-block text-muted">
                                                                                        <span>Price: </span>
                                                                                        <span class="price">500 UAH</span>
                                                                                    </span>
                                                                                    <div>

                                                                                        <span>Master: </span>
                                                                                        <span>
                                                                                            <a href="" class="text-default">Alica Nestle</a>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-schedule-content">
                                                                                <div class="time-value">
<!--                                                                                    <span>Date: </span>-->
                                                                                    <div class="date">
                                                                                        <i class="fe fe-calendar"></i>
<!--                                                                                        <span>Date: </span>-->
                                                                                        <span>5 November</span>
                                                                                    </div>
                                                                                    <div class="time">
                                                                                        <i class="fe fe-clock"></i>

<!--                                                                                        <span>Time: </span>-->
                                                                                        <span>
                                                                                            <span class="start-time">9:30</span>
                                                                                            <span>-</span>
                                                                                            <span class="end-time">11:00</span>
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="affiliate-address">
                                                                                        <i class="fe fe-map-pin"></i>
<!--                                                                                         <span>Address: </span>-->
                                                                                         <span class="address">
                                                                                             c. Kyiv, str. Freedom, 79
                                                                                         </span>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card">
                                                                            <div class="card-header custom-card-header border-bottom-0 ">
                                                                                <h5 class="main-content-label my-auto tx-medium mb-0">
                                                                                    Manicure
                                                                                </h5>
                                                                                <div class="card-options">
                                                                                    <i class="far fa-heart me-1"></i>
                                                                                    <i class="fe fe-shopping-cart"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex align-items-center pt-3 mt-auto
                                                                                       card-worker-wrapper">
                                                                                <!--                                                                                <div class="main-img-user avatar-sm me-3">-->
                                                                                <!--                                                                                    <img src="" class="w-10 rounded-circle" alt="avatar-img">-->
                                                                                <!--                                                                                </div>-->
                                                                                <div>
                                                                                    <span class="d-block text-muted">
                                                                                        <span>Price: </span>
                                                                                        <span class="price">500 UAH</span>
                                                                                    </span>
                                                                                    <div>

                                                                                        <span>Master: </span>
                                                                                        <span>
                                                                                            <a href="" class="text-default">Alica Nestle</a>
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-schedule-content">
                                                                                <div class="time-value">
<!--                                                                                    <span>Date: </span>-->
                                                                                    <div class="date">
                                                                                        <i class="fe fe-calendar"></i>
<!--                                                                                        <span>Date: </span>-->
                                                                                        <span>5 November</span>
                                                                                    </div>
                                                                                    <div class="time">
                                                                                        <i class="fe fe-clock"></i>

<!--                                                                                        <span>Time: </span>-->
                                                                                        <span>
                                                                                            <span class="start-time">9:30</span>
                                                                                            <span>-</span>
                                                                                            <span class="end-time">11:00</span>
                                                                                        </span>
                                                                                    </div>

                                                                                    <div class="affiliate-address">
                                                                                        <i class="fe fe-map-pin"></i>
<!--                                                                                         <span>Address: </span>-->
                                                                                         <span class="address">
                                                                                             c. Kyiv, str. Freedom, 79
                                                                                             c. Kyiv, str. Freedom, 79 ahhajakj sjsjs
                                                                                         </span>
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval 12-15"
                                                                         data-start-interval="12"
                                                                         data-end-interval="15"
                                                                    >
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval 15-18"
                                                                         data-start-interval="15"
                                                                         data-end-interval="18"
                                                                    >
                                                                    </div>
                                                                    <div class="col-lg-3 time-interval 18-21"
                                                                         data-start-interval="18"
                                                                         data-end-interval="21"
                                                                    >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="tab22">
                                                            <p> Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. </p>
                                                            <p> Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. </p>
                                                            <p class="mb-0">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                                                        </div>
                                                        <div class="tab-pane" id="tab23">
                                                            <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
                                                            <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
                                                            <p class="mb-0">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. </p>
                                                        </div>
                                                        <div class="tab-pane" id="tab24">
                                                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
                                                            <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so blinded by desire quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
                                                            <p class="mb-0">Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="hair">
                                            <div class="card-body">

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="manicure">
                                            <div class="card-body">

                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<!--Moment-->
<script src="/public/assets/plugins/moment/moment.js"></script>


<!--Mine scripts-->
<script src="/public/js/custom/common/pages/classes/builder/OptionBuilder.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/Renderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/TimeRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/DateRenderer.js"></script>
<script src="/public/js/custom/common/pages/classes/renderer/extends/ScheduleRenderer.js"></script>

<script src="/public/js/custom/common/pages/classes/builder/ScheduleHtmlBuilder.js"></script>

<script src="/public/js/custom/common/pages/Cookie.js"></script>

<script src="/public/js/custom/common/pages/Table.js"></script>
<script src="/public/js/custom/common/pages/user/profile/AppointmentsTable.js"></script>
<script src="/public/js/custom/common/pages/user/forms/Form.js"></script>
<script src="/public/js/custom/common/pages/user/forms/SearchScheduleForm.js"></script>

<script src="/public/js/custom/common/pages/Notifier.js"></script>
<script src="/public/js/custom/common/pages/Requestor.js"></script>
<script src="/public/js/custom/common/pages/user/profile/Account.js"></script>

<script src="/public/js/custom/frontend/pages/user/profile/account.js"></script>

</body>
</html>