<?php require_once VIEW_COMMON."pages/user/profile/header/head.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
        top: 0;
        z-index: 2000;
        padding-left: 10px;
    }
</style>
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
                        <h2 class="main-content-title tx-24 mg-b-5">Calendar</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Apps</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calendar</li>
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
                    <div class="col-sm-12 col-md-12">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="row" id="wrap">
                                    <div class="col-xl-2" id="external-events">
                                        <h4>Draggable Events</h4>
                                        <div id="external-events-list">
                                            <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                                <div class="fc-event-main">My Event 1</div>
                                            </div>
                                            <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                                <div class="fc-event-main">My Event 2</div>
                                            </div>
                                            <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                                <div class="fc-event-main">My Event 3</div>
                                            </div>
                                            <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                                <div class="fc-event-main">My Event 4</div>
                                            </div>
                                            <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                                                <div class="fc-event-main">My Event 5</div>
                                            </div>
                                        </div>
                                        <p>
                                            <input type="checkbox" id="drop-remove" />
                                            <label for="drop-remove">remove after drop</label>
                                        </p>
                                    </div>
                                    <div class="col-xl-10" id="calendar-wrap">
                                        <div id="calendar"></div>
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

    <!-- Main Footer-->
<!--    --><?php //require_once VIEW_COMMON."pages/user/profile/footer/footer.php"?>
    <!--End Footer-->

    <!-- Modal -->
    <div aria-hidden="true" class="modal main-modal-calendar-schedule" id="modalSetSchedule" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create New Event</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="calendar.html" id="mainFormCalendar" method="post" name="mainFormCalendar">
                        <div class="form-group">
                            <input class="form-control" placeholder="Add title" type="text">
                        </div>
                        <div class="form-group d-flex align-items-center">
                            <label class="rdiobox mg-r-60"><input checked name="etype" type="radio" value="event"> <span>Event</span></label> <label class="rdiobox"><input name="etype" type="radio" value="reminder"> <span>Reminder</span></label>
                        </div>
                        <div class="form-group mg-t-30">
                            <label class="tx-13 mg-b-5 tx-gray-600">Start Date</label>
                            <div class="row row-xs">
                                <div class="col-7">
                                    <input class="form-control" id="mainEventStartDate" placeholder="Select date" type="text" value="">
                                </div><!-- col-7 -->
                                <div class="col-5">
                                    <select class="select2 form-control main-event-time" data-placeholder="Select time" id="mainEventStartTime">
                                        <option label="Select time">
                                            Select time
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="tx-13 mg-b-5 tx-gray-600">End Date</label>
                            <div class="row row-xs">
                                <div class="col-7">
                                    <input class="form-control" id="EventEndDate" placeholder="Select date" type="text" value="">
                                </div><!-- col-7 -->
                                <div class="col-5">
                                    <select class="select2 form-control main-event-time" data-placeholder="Select time" id="EventEndTime">
                                        <option label="Select time">
                                            Select time
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Write some description (optional)" rows="2"></textarea>
                        </div>
                        <div class="d-flex mg-t-15 mg-lg-t-30">
                            <button class="btn btn-main-primary pd-x-25 mg-r-5" type="submit">Save</button> <a class="btn btn-light" data-bs-dismiss="modal" href="">Discard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!-- Modal -->
    <div aria-hidden="true" class="modal main-modal-calendar-event" id="modalCalendarEvent" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <nav class="nav nav-modal-event">
                        <a class="nav-link" href="#"><i class="icon ion-md-open"></i></a>
                        <a class="nav-link" href="#"><i class="icon ion-md-trash"></i></a>
                        <a class="nav-link" data-bs-dismiss="modal" href="#">
                            <i class="icon ion-md-close"></i></a>
                    </nav>
                </div>
                <div class="modal-body">
                    <div class="row row-sm">
                        <div class="col-sm-6">
                            <label class="tx-13 tx-gray-600 mg-b-2">Start Date</label>
                            <p class="event-start-date"></p>
                        </div>
                        <div class="col-sm-6">
                            <label class="tx-13 mg-b-2">End Date</label>
                            <p class="event-end-date"></p>
                        </div>
                    </div>
                    <label class="tx-13 tx-gray-600 mg-b-2">Description</label>
                    <p class="event-desc tx-gray-900 mg-b-30"></p>
                    <a class="btn btn-secondary wd-80" data-bs-dismiss="modal" href="">Close</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->

    <!-- Sidebar -->

    <!-- End Sidebar -->

</div>
<!-- End Page -->

<!-- Back-to-top -->
<!--<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>-->

<!-- Jquery js-->
<script src="/public/assets/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap js-->
<script src="/public/assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="/public/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Moment js-->
<script src="/public/assets/plugins/moment/min/moment.min.js"></script>

<!-- Datepicker js-->
<script src="/public/assets/plugins/jquery-ui/ui/widgets/datepicker.js"></script>

<!-- Perfect-scrollbar js -->
<script src="/public/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<!-- Sidemenu js -->
<script src="/public/assets/plugins/sidemenu/sidemenu.js" id="leftmenu"></script>

<!-- Select2 js-->
<script src="/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="/public/assets/js/select2.js"></script>

<!-- Color Theme js -->
<script src="/public/assets/js/themeColors.js"></script>

<!-- Sticky js -->
<script src="/public/assets/js/sticky.js"></script>

<!-- Full-calendar js-->
<script src='/public/assets/plugins/fullcalendar/fullcalendar.min.js'></script>
<script src="/public/assets/js/calendar-events.js"></script>
<script src="/public/assets/js/calendar.js"></script>

<!-- Custom js -->
<script src="/public/assets/js/custom.js"></script>

<?php require_once VIEW_COMMON."pages/landing/footer/footer_scripts.php"?>

</body>
</html>