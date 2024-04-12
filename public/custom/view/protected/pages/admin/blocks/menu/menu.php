<ul class="menu-nav nav">

    <?php require_once VIEW_PROTECTED_BLOCKS."/units/user_info.php"?>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['ADMIN']['WEB']['PROFILE']['home']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-home sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">Home</span>
        </a>
    </li>


<!--    <li class="nav-item">-->
<!--        <a class="nav-link" href="/web/admin/profile/settings">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-lock sidemenu-icon menu-icon"></i>-->
<!--            <span class="sidemenu-label">Settings</span>-->
<!--        </a>-->
<!--    </li>-->

    <li class="nav-item">
        <a class="nav-link with-sub" href="javascript:void(0)">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-user sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">User Management</span>
            <i class="angle fe fe-chevron-right"></i>
        </a>
        <ul class="nav-sub">
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/users">-->
<!--                    Users-->
<!--                </a>-->
<!--            </li>-->
            <li class="nav-sub-item">
                <a class="nav-sub-link" href="<?=API['ADMIN']['WEB']['PROFILE']['workers']?>">
                    Workers
                </a>
            </li>
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/administrators">-->
<!--                    Administrators-->
<!--                </a>-->
<!--            </li>-->
        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['ADMIN']['WEB']['PROFILE']['services']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-palette sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Services</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['ADMIN']['WEB']['PROFILE']['departments']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-menu sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Departments</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['ADMIN']['WEB']['PROFILE']['positions']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-receipt sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Positions</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['ADMIN']['WEB']['PROFILE']['affiliates']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-package sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Affiliates</span>
        </a>
    </li>

<!--    <li class="nav-item">-->
<!--        <a class="nav-link" href="/web/admin/profile/services">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-palette sidemenu-icon menu-icon"></i>-->
<!--            <span class="sidemenu-label">Services</span>-->
<!--        </a>-->
<!--    </li>-->
<!---->
<!--    <li class="nav-item">-->
<!--        <a class="nav-link" href="/web/admin/profile/products">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-shopping-cart-full sidemenu-icon menu-icon "></i>-->
<!--            <span class="sidemenu-label">Products</span>-->
<!--        </a>-->
<!--    </li>-->
<!---->
<!--    <li class="nav-item">-->
<!--        <a class="nav-link with-sub" href="javascript:void(0)">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-wallet sidemenu-icon menu-icon "></i>-->
<!--            <span class="sidemenu-label">Orders</span>-->
<!--            <i class="angle fe fe-chevron-right"></i>-->
<!--        </a>-->
<!--        <ul class="nav-sub">-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/orders_product">-->
<!--                    Products-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/orders_service">-->
<!--                    Services-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/orders_other">-->
<!--                    Other-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </li>-->
<!---->
<!--    <li class="nav-item">-->
<!--        <a class="nav-link with-sub" href="javascript:void(0)">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-receipt sidemenu-icon menu-icon "></i>-->
<!--            <span class="sidemenu-label">History</span>-->
<!--            <i class="angle fe fe-chevron-right"></i>-->
<!--        </a>-->
<!--        <ul class="nav-sub">-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/history">-->
<!--                    Tab 1-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/history">-->
<!--                    Tab 2-->
<!--                </a>-->
<!--            </li>-->
<!--            <li class="nav-sub-item">-->
<!--                <a class="nav-sub-link" href="/web/admin/profile/history">-->
<!--                    Tab 3-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </li>-->
<!---->
<!--    <li class="nav-item">-->
<!--        <a class="nav-link" href="/web/admin/profile/statistics">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-bar-chart-alt sidemenu-icon menu-icon "></i>-->
<!--            <span class="sidemenu-label">Statistics</span>-->
<!--        </a>-->
<!--    </li>-->
<!---->
<!--    <li class="nav-item">-->
<!--        <a class="nav-link" href="/web/admin/profile/notification">-->
<!--            <span class="shape1"></span>-->
<!--            <span class="shape2"></span>-->
<!--            <i class="ti-menu sidemenu-icon menu-icon "></i>-->
<!--            <span class="sidemenu-label">Notification</span>-->
<!--        </a>-->
<!--    </li>-->
</ul>