<?php require_once VIEW_PROTECTED_BLOCKS."/header/full_header.php"?>

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
                    <?php require_once VIEW_PROTECTED_PAGES."/admin/blocks/menu/menu.php"?>
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
                <div class="row row-sm table-wrapper">
<!--                    <h3 class="pl-30 title">Table</h3>-->
                    <div class="card-body">
                        <div class="row table-filter">
                            <div class="col-lg-3 d-lg-flex justify-content-start">
                                <div class="show-entries">
                                    <?php require_once VIEW_PROTECTED_BLOCKS."/units/select_pagination_count.php"?>
                                </div>
                                <div class="button-wrapper mg-l-10">
                                    <button aria-label="Add Worker"
                                            class="btn ripple pd-x-25"
                                            id="add-worker-trigger"
                                            data-bs-dismiss="modal" type="button">
                                        Add New Worker
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-9 d-lg-flex justify-content-end">
                                <div class="d-flex mt-4 mt-lg-0 filter-wrapper">
                                    <div class="mass-action-wrapper">
                                    </div>

                                    <?php //require_once "src/view/common/pages/admin/profile/blocks/search_by.php";?>

                                  </div>
                            </div>
                        </div>
                        <div class="table-responsive my-data-table">
                            <div class="col-lg-12 col-sm-12">
                                <?php require_once VIEW_PROTECTED_BLOCKS."/units/data_loader_gif.php"?>
                            </div>
                            <?php require_once VIEW_PROTECTED_PAGES."/admin/blocks/tables/workers_table.php"?>
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
        <?php require_once VIEW_OPEN_BLOCKS."/footer/footer_widget.php"?>
        <!--footer end-->

        <!--back-to-top start-->
        <a id="totop" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
        <!--back-to-top end-->
    </div><!-- page end -->
</div>
<!-- End Main Content-->

<!--</div>-->
<!-- End Page -->

<?php require_once VIEW_PROTECTED_BLOCKS . '/footer/full_footer.php'?>

<!--Mine scripts-->
<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/admin/ui/worker/workers.js"></script>

</body>
</html>