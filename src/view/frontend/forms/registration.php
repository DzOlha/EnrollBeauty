
<?php require_once VIEW_COMMON."main_header.php"?>
<style>
    .ttm-stickable-header-w {
        position: relative !important;
    }
    .ttm-quote-form .form-group {
        margin-bottom: 15px;
    }
</style>

<form id="ttm-quote-form" class="ttm-quote-form clearfix registration-form" method="post" action="#">
    <h2 class="text-center">Registration</h2>
    <div class="column">
        <div class="form-group">
            <input name="name" type="text" placeholder="Name"
                   required="required" class="form-control" >
        </div>
        <div class="form-group">
            <input name="surname" type="text" placeholder="Surname"
                   required="required" class="form-control" >
        </div>
        <div class="form-group">
            <input name="email" type="email" placeholder="Email"
                   required="required" class="form-control">
        </div>
        <div class="form-group">
            <input name="password" type="password" placeholder="Password"
                   required="required" class="form-control" >
        </div>
        <div class="form-group">
            <input name="confirm_password" type="password" placeholder="Confirm Password"
                   required="required" class="form-control" >
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit"
                    class="ttm-btn ttm-btn-color-skincolor ttm-btn-style-fill">
                Register
            </button>
        </div>
    </div>
</form>

<?php require_once VIEW_COMMON."main_footer.php"?>

