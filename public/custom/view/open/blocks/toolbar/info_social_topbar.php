<div class="ttm-topbar-wrapper ttm-textcolor-white clearfix">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="ttm-topbar-content">
                    <ul class="top-contact text-left">
                        <li><i class="fa fa-map-marker ttm-textcolor-skincolor"></i>
                            <?=COMPANY['address']?>
                        </li>
                        <li><i class="fa fa-envelope-o ttm-textcolor-skincolor"></i>
                            <a href="mailto:<?=COMPANY['email']?>">
                                <?=COMPANY['email']?>
                            </a>
                        </li>
                    </ul>
                    <div class="topbar-right text-right">
                        <div class="ttm-social-links-wrapper list-inline">
                            <ul class="social-icons">
                                <?php if(isset(COMPANY['socials']['Instagram'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['Instagram']?>" target="_blank"
                                           class="tooltip-bottom" data-tooltip="Instagram"
                                           aria-label="Instagram">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['Facebook'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['Facebook']?>" target="_blank"
                                           class="tooltip-bottom" data-tooltip="Facebook"
                                           aria-label="Facebook">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['TikTok'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['TikTok']?>" target="_blank"
                                           class="tooltip-bottom" data-tooltip="TikTok"
                                           aria-label="TikTok">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="tiktok-icon-header" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['LinkedIn'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['LinkedIn']?>" target="_blank"
                                           class="tooltip-bottom" data-tooltip="LinkedIn"
                                           aria-label="LinkedIn">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['YouTube'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['YouTube']?>" target="_blank"
                                           class="tooltip-bottom" data-tooltip="YouTube"
                                           aria-label="YouTube">
                                            <i class="fa fa-youtube"></i>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['Twitter'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['Twitter']?>" class=" tooltip-bottom"
                                           data-tooltip="Twitter"  aria-label="Twitter">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                <?php }?>
                                <?php if(isset(COMPANY['socials']['Telegram'])) {?>
                                    <li>
                                        <a href="<?=COMPANY['socials']['Telegram']?>" class=" tooltip-bottom"
                                           data-tooltip="Telegram"  aria-label="Telegram">
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
    </div>
</div>