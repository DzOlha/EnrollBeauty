<div id="site-header-menu" class="site-header-menu">
    <div class="site-header-menu-inner ttm-stickable-header">
        <div class="container">
            <div class="row">
                <div class="col">
                    <!--site-navigation -->
                    <div id="site-navigation" class="site-navigation d-flex flex-row">
                        <div class="site-branding mr-auto">
                            <!-- site-branding -->
                            <a class="home-link" href="<?=ENROLL_BEAUTY_URL_HTTPS_ROOT?>" title="Sylin Beauty" rel="home">
                                <img id="logo-img" class="img-center lazyload" src="/public/images/mockup/logo.png" alt="logo">
                            </a>
                            <!-- site-branding end -->
                        </div>
                        <div class="ttm-menu-toggle">
                            <input type="checkbox" id="menu-toggle-form" />
                            <label for="menu-toggle-form" class="ttm-menu-toggle-block">
                                <span class="toggle-block toggle-blocks-1"></span>
                                <span class="toggle-block toggle-blocks-2"></span>
                                <span class="toggle-block toggle-blocks-3"></span>
                            </label>
                        </div>
                        <nav id="menu" class="menu">
                            <ul class="dropdown">
                                <li class="active"><a href="#">Home</a>
                                    <ul>
                                        <li class="active"><a href="<?=ENROLL_BEAUTY_URL_HTTPS_ROOT?>">Homepage 1</a></li>
                                        <li><a href="home-2.html">Homepage 2</a></li>
                                        <li><a href="http://themetechmount.net/html/sylin/barber">Demo Barber</a></li>
                                        <li><a href="http://themetechmount.net/html/sylin/spa">Demo Spa</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">pages</a>
                                    <ul>
                                        <li><a href="aboutus.html">about us</a></li>
                                        <li><a href="services.html">services</a></li>
                                        <li><a href="skin-care-treatment.html">service details</a></li>
                                        <li><a href="faq.html">F.A.Q.</a></li>
                                        <li><a href="our-team.html">our team</a></li>
                                        <li><a href="team-details.html">team details</a></li>
                                        <li><a href="error.html">Error page</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">services</a>
                                    <ul>
                                        <li><a href="bleaching-services.html">Bleaching Service</a></li>
                                        <li><a href="hair-smoothing.html">Hair Smoothening</a></li>
                                        <li><a href="skin-care-treatment.html">Skin Care Treatment</a></li>
                                        <li><a href="face-treatment.html">Anti-Ageing Face  Treatments</a></li>
                                        <li><a href="hair-color-styling.html">Hair Color and Styling</a></li>
                                        <li><a href="facial-makeup.html">Facial and Makeup</a></li>
                                    </ul>
                                </li>
                                <li><a href="gallery.html">gallery</a></li>
                                <li><a href="#">NEWS</a>
                                    <ul>
                                        <li><a href="blog.html">news classic view</a></li>
                                        <li><a href="blog-grid.html">news Grid View</a></li>
                                        <li><a href="blog-single.html">news details</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact-us.html">CONTACT US</a></li>

                                <?php if(!isset($_SESSION['user_id'])) {?>
                                <li class="register-button">
                                    <a href="/web/user/registration">
                                        Register
                                    </a>
                                </li>
                                <li class="login-button">
                                    <a href="/web/user/login">
                                        Log In
                                    </a>
                                </li>
                                <?php } else {?>
                                <li class="register-button">
                                    <a href="/web/user/logout">
                                        Log Out
                                    </a>
                                </li>
                                <li class="login-button">
                                    <a href="/web/user/account">
                                        Profile
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>

                        </nav>
                    </div><!-- site-navigation end-->
                </div>
            </div>

        </div>
    </div>
</div>