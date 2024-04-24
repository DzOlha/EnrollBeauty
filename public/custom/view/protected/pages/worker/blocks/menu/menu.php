<ul class="menu-nav nav">

    <li>
        <?php require_once VIEW_PROTECTED_BLOCKS."/units/user_info.php"?>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['WORKER']['WEB']['PROFILE']['home']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-home sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">Home</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['WORKER']['WEB']['PROFILE']['settings']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-lock sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">Settings</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['WORKER']['WEB']['PROFILE']['schedule']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-receipt sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">Schedule</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?=API['WORKER']['WEB']['PROFILE']['services']?>">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-palette sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Services</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link with-sub" href="#">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-wallet sidemenu-icon menu-icon "></i>
            <span class="sidemenu-label">Pricing</span>
            <i class="angle fe fe-chevron-right"></i>
        </a>
        <ul class="nav-sub">

            <li class="nav-sub-item">
                <a class="nav-sub-link" href="<?=API['WORKER']['WEB']['PROFILE']['pricing']?>">
                    Services
                </a>
            </li>

        </ul>
    </li>

    <li class="nav-item">
        <a class="nav-link with-sub" href="#">
            <span class="shape1"></span>
            <span class="shape2"></span>
            <i class="ti-shopping-cart-full sidemenu-icon menu-icon"></i>
            <span class="sidemenu-label">Orders</span>
            <i class="angle fe fe-chevron-right"></i>
        </a>
        <ul class="nav-sub">

            <li class="nav-sub-item">
                <a class="nav-sub-link" href="<?=API['WORKER']['WEB']['PROFILE']['orders']?>">
                    Service
                </a>
            </li>

        </ul>
    </li>

</ul>