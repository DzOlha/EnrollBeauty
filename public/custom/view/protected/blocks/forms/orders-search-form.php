
<div class="card-body">
    <div class="row row-sm justify-content-start">
        <?php if(isset($role) && $role !== 'worker') {?>
            <div class="col-lg-4">
                <div class="form-group worker-wrapper">
                    <p class="mg-b-0" id="worker-name-label">Worker Name</p>
                    <select class="form-control select2-with-search"
                            id="worker-name" aria-label="Worker Select"
                            aria-labelledby="worker-name-label"
                    >
                        <option label="Choose one">
                        </option>
                    </select>
                </div>
            </div>
        <?php }?>
        <?php if(isset($role) && $role !== 'user') {?>
            <div class="col-lg-4">
                <div class="form-group user-email-wrapper">
                    <p class="mg-b-0" id="user-email-label">User Email</p>
                    <select class="form-control select2-with-search"
                            id="user-email" aria-label="User Select"
                            aria-labelledby="user-name-label"
                    >
                        <option label="Choose one">
                        </option>
                    </select>
                </div>
            </div>
        <?php }?>
        <?php if(isset($role) && $role === 'admin') {?>
            <div class="col-lg-4">
                <div class="form-group department-wrapper">
                    <p class="mg-b-0" id="department-name-label">
                        Department Name
                    </p>
                    <select class="form-control select2-with-search"
                            id="department-name" aria-label="Department Select"
                            aria-labelledby="department-name-label"
                    >
                        <option label="Choose one">
                        </option>
                    </select>
                </div>
            </div>
        <?php }?>
        <div class="col-lg-4">
            <div class="form-group service-wrapper">
                <p class="mg-b-0" id="service-name-label">
                    Service Name
                </p>
                <select class="form-control select2-with-search"
                        id="service-name" aria-label="Service Select"
                        aria-labelledby="service-name-label"
                >
                    <option label="Choose one">
                    </option>
                </select>
            </div>
        </div>
        <?php if(isset($role) && $role !== 'user') {?>
            <div class="col-lg-4">
                <div class="form-group affiliate-wrapper">
                    <p class="mg-b-0" id="affiliate-name-label">
                        Affiliate
                    </p>
                    <select class="form-control select2-with-search"
                            id="affiliate-name-address"
                            aria-label="Affiliate Select"
                            aria-labelledby="affiliate-name-label"
                    >
                        <option label="Choose one">
                        </option>
                    </select>
                </div>
            </div>
        <?php }?>
        <div class="col-lg-4">
            <p class="mg-b-0" id="dates-label">Dates</p>
            <div class="input-group" style="height: 37px;">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fe fe-calendar"></i>
                    </div>
                </div>
                <input type="text"
                       class="form-control pull-right date-range"
                       id="date-range-input" aria-label="Dates Select"
                       aria-labelledby="dates-label"
                       required>
            </div>
            <div class="error" id="date-range-input-error"></div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <p class="mg-b-0" id="price-from-label">
                    Price from
                </p>
                <div class="input-group mb-3">
                    <input class="form-control" type="number"
                           id="price-from" aria-label="Price from amount"
                           aria-labelledby="price-from-label"
                    >
                    <div class="input-group-prepend">
                        <span class="input-group-text bd-r">грн</span>
                    </div>
                    <div class="error" id="price-from-error"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <p class="mg-b-0" id="price-to-label">
                    Price to
                </p>
                <div class="input-group mb-3">
                    <input class="form-control" type="number"
                           id="price-to" aria-label="Price to amount"
                           aria-labelledby="price-to-label"
                    >
                    <div class="input-group-prepend">
                        <span class="input-group-text bd-r">грн</span>
                    </div>
                    <div class="error" id="price-to-error"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group status-wrapper">
                <p class="mg-b-0" id="status-label">
                    Status
                </p>
                <select class="form-control select2 col-lg-2"
                        id="status-select" aria-label="Status Select"
                        aria-labelledby="status-label"
                >
                    <option label="Choose one" value=" ">
                        Choose one
                    </option>
                    <option value="1">
                        Completed
                    </option>
                    <option value="0">
                        Upcoming
                    </option>
                    <option value="-1">
                        Canceled
                    </option>
                </select>
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
</div>