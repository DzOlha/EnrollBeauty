<div class="card-body">
    <div class="row row-sm justify-content-start">
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
        <div class="col-lg-4">
            <div class="form-group">
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
        <div class="col-lg-4">
            <p class="mg-b-0" id="dates-label">
                Dates
            </p>
            <div class="input-group">
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
        <div class="col-lg-6">
            <div class="row row-sm time-range">
                <div class="col-lg-6">
                    <div class="form-group">
                        <p class="mg-b-0" id="start-time-label">
                            Start time
                        </p>
                        <select class="form-control select2 col-lg-2"
                                id="start-time" aria-label="Start time select"
                                aria-labelledby="start-time-label"
                        >
                            <option label="Choose one">
                            </option>
                            <option value="9:00">
                                09:00
                            </option>
                            <option value="10:00">
                                10:00
                            </option>
                            <option value="11:00">
                                11:00
                            </option>
                            <option value="12:00">
                                12:00
                            </option>
                            <option value="13:00">
                                13:00
                            </option>
                            <option value="14:00">
                                14:00
                            </option>
                            <option value="15:00">
                                15:00
                            </option>
                            <option value="16:00">
                                16:00
                            </option>
                            <option value="17:00">
                                17:00
                            </option>
                            <option value="18:00">
                                18:00
                            </option>
                            <option value="19:00">
                                19:00
                            </option>
                            <option value="20:00">
                                20:00
                            </option>
                            <option value="21:00">
                                21:00
                            </option>
                        </select>
                        <div class="error" id="start-time-select-error"></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <p class="mg-b-0" id="end-time-label">
                            End time
                        </p>
                        <select class="form-control select2 col-lg-2"
                                id="end-time"  aria-label="End time select"
                                aria-labelledby="end-time-label"
                        >
                            <option label="Choose one">
                            </option>
                            <option value="9:00">
                                09:00
                            </option>
                            <option value="10:00">
                                10:00
                            </option>
                            <option value="11:00">
                                11:00
                            </option>
                            <option value="12:00">
                                12:00
                            </option>
                            <option value="13:00">
                                13:00
                            </option>
                            <option value="14:00">
                                14:00
                            </option>
                            <option value="15:00">
                                15:00
                            </option>
                            <option value="16:00">
                                16:00
                            </option>
                            <option value="17:00">
                                17:00
                            </option>
                            <option value="18:00">
                                18:00
                            </option>
                            <option value="19:00">
                                19:00
                            </option>
                            <option value="20:00">
                                20:00
                            </option>
                            <option value="21:00">
                                21:00
                            </option>
                        </select>
                        <div class="error" id="end-time-select-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 d-flex">
            <div class="form-group">
                <label class="pd-t-30 custom-control custom-checkbox custom-control-md">
                    <input type="checkbox"
                           class="custom-control-input"
                           name="make-lang-active"
                           value="" checked
                           id="only-ordered-checkbox"
                           aria-label="Ordered Schedule Items"
                           aria-labelledby="ordered-label"
                    >
                    <span class="custom-control-label custom-control-label-md  tx-16"
                            id="ordered-label">
                             Show ordered schedules
                    </span>
                </label>
            </div>
        </div>
        <div class="col-lg-3 d-flex">
            <div class="form-group">
                <label class="pd-t-30 custom-control custom-checkbox custom-control-md">
                    <input type="checkbox"
                           class="custom-control-input"
                           name="make-lang-active"
                           value="" checked
                           id="only-free-checkbox"
                           aria-label="Free Schedule Items"
                           aria-labelledby="free-label"
                    >
                    <span class="custom-control-label custom-control-label-md  tx-16"
                            id="free-label">
                            Show free schedules
                    </span>
                </label>
            </div>
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
                    <div class="error" id="price-from-input-error"></div>
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
                    <div class="error" id="price-to-input-error"></div>
                </div>
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
    <div class="button-wrapper light mg-t-35">
        <button aria-label="Add Worker"
                class="btn ripple pd-x-25"
                id="add-schedule-trigger"
                data-bs-dismiss="modal" type="button">
            Add Schedule Item
        </button>
    </div>

    <div class="row row-sm">
        <div class="col-lg-12 panel panel-primary tabs-style-2 mg-t-50"
             id="main-schedule-wrapper">
            <!--                                JS generate schedule-->
        </div>
    </div>
</div>