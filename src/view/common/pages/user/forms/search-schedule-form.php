
<div class="row row-sm available-schedules mt-40 mb-40" id="search-appointment-form">
            <h3 class="pl-15 search-schedule-title">Search Available Schedules for Appointments</h3>
            <div class="card-body">

                <div class="row row-sm">
                    <div class="col-lg-3">
                        <div class="form-group service-wrapper">
                            <p class="mg-b-0">Service Name</p>
                            <select class="form-control select2-with-search"
                                    id="service-name"
                            >
                                <option label="Choose one">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group worker-wrapper">
                            <p class="mg-b-0">Worker Name</p>
                            <select class="form-control select2-with-search"
                                    id="worker-name"
                            >
                                <option label="Choose one">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <p class="mg-b-0">Affiliate</p>
                            <select class="form-control select2-with-search"
                                    id="affiliate-name-address"
                            >
                                <option label="Choose one">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <p class="mg-b-0">Dates</p>
                        <div class="input-group dates">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fe fe-calendar"></i>
                                </div>
                            </div>
                            <input type="text"
                                   class="form-control pull-right date-range"
                                   id="date-range-input"
                                   required>
                        </div>
                        <div class="error" id="date-range-input-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row row-sm time-range">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <p class="mg-b-0">Start time</p>
                                    <select class="form-control select2 col-lg-2"
                                            id="start-time"
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
                                    <p class="mg-b-0">End time</p>
                                    <select class="form-control select2 col-lg-2"
                                            id="end-time"
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

                    <div class="col-lg-3">
                        <div class="form-group">
                            <p class="mg-b-0">Price from</p>
                            <div class="input-group mb-3">
                                <div class="price-d-flex">
                                    <input aria-label="Amount (to the nearest dollar)"
                                           class="form-control" type="number"
                                           id="price-from"
                                    >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bd-r">грн</span>
                                    </div>
                                </div>
                                <div class="error" id="price-from-input-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <p class="mg-b-0">Price to</p>
                            <div class="input-group mb-3">
                                <div class="price-d-flex">
                                    <input aria-label="Amount (to the nearest dollar)"
                                           class="form-control" type="number"
                                           id="price-to"
                                    >
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bd-r">грн</span>
                                    </div>
                                </div>
                                <div class="error" id="price-to-input-error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 search-btn-schedule">
                        <p class="mg-b-20"></p>
                        <button class="btn btn-block"
                                type="button"
                                id="submit-search-button">
                            Search
                        </button>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="col-lg-12 panel panel-primary tabs-style-2 mg-t-50"
                         id="main-schedule-wrapper">
                        <!--                                JS generate schedule-->
                    </div>
                </div>
            </div>
        </div>

