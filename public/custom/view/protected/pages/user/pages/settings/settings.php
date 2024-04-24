<?php require_once VIEW_PROTECTED_BLOCKS."/header/full_header.php"?>

<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css"/>
<link href="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/fancyuploder/fancy_fileupload.css" rel="stylesheet" />

<link href="/<?=CUSTOM_ASSETS?>/css/pages/protected/pages/worker/profile-settings.css" rel="stylesheet"/>


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

                <div class="row settings-wrapper pb-30">
                    <div class="col-lg-9 content-area order-lg-2">
                        <div class="tabs-style-4">
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="personalInformation">
                                        <h2 class="text-center"><b>Personal Information</b></h2>

                                        <div class="tab-content modal-form">
                                            <?php require_once VIEW_PROTECTED_PAGES . '/user/blocks/forms/user-edit-info-form.php'?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="socialNetworks">
                                        <h2 class="text-center"><b>Social Networks</b></h2>

                                        <div class="tab-content modal-form">
                                           <?php require_once VIEW_PROTECTED_PAGES . '/user/blocks/forms/user-edit-socials-form.php'?>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="publicProfile">
                                        <h2 class="text-center"><b>Public Profile</b></h2>

                                        <div class="tab-content modal-form">
                                            <button aria-label="Submit" class="btn ripple pd-x-25"
                                                    id="show-public-profile-button" type="button">
                                                View My Public Profile
                                            </button>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="galleryOfWorks">
                                        <h2 class="text-center"><b>Gallery Of Works</b></h2>

                                        <div class="tab-content">

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="documents">
                                        <h2 class="text-center"><b>Documents</b></h2>

                                        <div class="tab-content">

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="reviews">
                                        <h2 class="text-center"><b>Reviews</b></h2>

                                        <div class="tab-content">

                                        </div>
                                    </div>
                                    <div class="tab-pane" id="security">
                                        <h2 class="text-center"><b>Security</b></h2>

                                        <div class="tab-content">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 widget-area">
                        <aside class="widget widget-nav-menu box-shadow ttm-bgcolor-grey">
                            <ul class="widget-menu nav" id="settings-menu">
                                <li><a href="#personalInformation" id="personalInformation-trigger" class="active" data-bs-toggle="tab">
                                        Personal Information
                                    </a></li>
                                <li><a href="#socialNetworks" id="socialNetworks-trigger" data-bs-toggle="tab">
                                        Social Networks
                                    </a></li>
<!--                                <li><a href="#publicProfile" id="publicProfile-trigger" data-bs-toggle="tab">-->
<!--                                        Public Profile-->
<!--                                    </a></li>-->
                                <!--                                <li><a href="#galleryOfWorks" id="galleryOfWorks-trigger" data-bs-toggle="tab">-->
                                <!--                                        Gallery Of Works-->
                                <!--                                    </a></li>-->
                                <!--                                <li><a href="#documents" id="documents-trigger" data-bs-toggle="tab">-->
                                <!--                                        Documents-->
                                <!--                                    </a></li>-->
                                <!--                                <li><a href="#reviews" id="reviews-trigger" data-bs-toggle="tab">-->
                                <!--                                        Reviews-->
                                <!--                                    </a></li>-->
                                <!--                                <li><a href="#security" id="security-trigger" data-bs-toggle="tab">-->
                                <!--                                        Security-->
                                <!--                                    </a></li>-->
                            </ul>
                        </aside>
                    </div>
                </div>

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

</div>
<!-- End Page -->



<!--</div>-->
<?php require_once VIEW_PROTECTED_BLOCKS . '/footer/full_footer.php'?>


<!-- Internal Fileuploads js-->
<script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/fileuploads/js/fileupload.js"></script>
<script src="/<?=MOCKUP_PROTECTED_FOLDER?>/assets/plugins/fileuploads/js/file-upload.js"></script>


<!--Mine scripts-->
<script type="module" src="/<?=CUSTOM_ASSETS?>/js/pages/protected/user/ui/settings/settings.js"></script>

</body>
</html>