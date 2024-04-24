<footer class="footer widget-footer clearfix">
    <div class="first-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7 col-sm-9 m-auto text-center">
                    <div class="footer-logo">
                        <img id="footer-logo-img" class="lazyload img-center"
                             data-src="/<?=MOCKUP_OPEN_FOLDER?>/assets/img/logo/footer-logo.png" alt="back-image">
                    </div>
                    <h4 class="textwidget widget-text ttm-textcolor-white">
<!--                        Sign Up To Get Latest Updates-->
                    </h4>
<!--                    <form id="subscribe-form" class="newsletter-form" method="post" action="#" data-mailchimp="true">-->
<!--                        <div class="mailchimp-inputbox clearfix" id="subscribe-content">-->
<!--                            <p><input type="email" name="email" placeholder="Your Email Address..." required=""></p>-->
<!--                            <p><button class="submit ttm-btn ttm-btn-size-md ttm-btn-shape-rounded ttm-btn-bgcolor-skincolor ttm-textcolor-white" type="submit">Subscribe Now!</button></p>-->
<!--                        </div>-->
<!--                        <div id="subscribe-msg"></div>-->
<!--                    </form>-->
                </div>
            </div>
        </div>
    </div>
    <div class="second-footer ttm-textcolor-white">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 widget-area">
                    <div class="widget widget_text clearfix">
                        <h3 class="widget-title">About Us</h3>
                        <div class="textwidget widget-text">
                            <p class="pb-10 res-767-p-0">We consistently showed year on year growth and is now a chain of 118+ branches worldwide</p>
                            <p class="pb-10 res-767-p-0">The most innovative products tested & approvby the greatest names in beauty industry.</p>
                            <a class="ttm-color-skincolor" href="">- More About Salone</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 widget-area">
                    <div class="widget flicker_widget clearfix">
                        <h3 class="widget-title">Get In  Touch</h3>
                        <div class="textwidget widget-text">
                            <div class="featured-icon-box icon-align-before-content icon-ver_align-top style3">
                                <div class="featured-icon">
                                    <div class="ttm-icon ttm-icon_element-onlytxt ttm-icon_element-color-skincolor ttm-icon_element-size-sm">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                </div>
                                <div class="featured-content">
                                    <div class="featured-desc">
                                        <p>
                                            <?=COMPANY['address']?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="featured-icon-box icon-align-before-content icon-ver_align-top style3">
                                <div class="featured-icon">
                                    <div class="ttm-icon ttm-icon_element-onlytxt ttm-icon_element-color-skincolor ttm-icon_element-size-sm">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                </div>
                                <div class="featured-content">
                                    <div class="featured-desc">
                                        <p><a href="mailto:info@example.com.com" style="color: white !important;">
                                                <?=COMPANY['email']?>
                                            </a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="featured-icon-box icon-align-before-content icon-ver_align-top style3">
                                <div class="featured-icon">
                                    <div class="ttm-icon ttm-icon_element-onlytxt ttm-icon_element-color-skincolor ttm-icon_element-size-sm">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                </div>
                                <div class="featured-content">
                                    <div class="featured-desc">
                                        <p>
                                            <?=COMPANY['phone']?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-footer-text">
        <div class="container">
            <div class="row copyright">
                <div class="col-md-9">
                    <div class="ttm-textcolor-white">
                        <span>Copyright &copy; 2024&nbsp;<a class="ttm-textcolor-skincolor" href="#">
                                <?=COMPANY['name']?>
                            </a></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-row align-items-center justify-content-end social-icons">
                        <ul class="footer-socials social-icons list-inline">
                            <?php if(isset(COMPANY['socials']['Instagram'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['Instagram']?>" target="_blank"
                                       class="tooltip-top" data-tooltip="Instagram" aria-label="Instagram">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['Facebook'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['Facebook']?>" target="_blank"
                                       class="tooltip-top" data-tooltip="Facebook" aria-label="Facebook">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['TikTok'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['TikTok']?>" target="_blank"
                                       class="tooltip-top tiktok-icon-footer-a" data-tooltip="TikTok"
                                       aria-label="TikTok">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="tiktok-icon-footer" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['LinkedIn'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['LinkedIn']?>" target="_blank"
                                       class="tooltip-top" data-tooltip="LinkedIn"
                                       aria-label="LinkedIn">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['YouTube'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['YouTube']?>" target="_blank"
                                       class="tooltip-top" data-tooltip="YouTube"
                                       aria-label="YouTube">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['Twitter'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['Twitter']?>" class=" tooltip-top"
                                       data-tooltip="Twitter" aria-label="Twitter">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if(isset(COMPANY['socials']['Telegram'])) {?>
                                <li>
                                    <a href="<?=COMPANY['socials']['Telegram']?>" class=" tooltip-top"
                                       data-tooltip="Telegram" aria-label="Telegram">
                                        <i class="fa fa-telegram"></i>
                                    </a>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>