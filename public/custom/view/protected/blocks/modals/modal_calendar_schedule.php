<div aria-hidden="true" class="modal main-modal-calendar-schedule" id="modalSetSchedule" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create New Event</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="calendar.html" id="mainFormCalendar" method="post" name="mainFormCalendar">
                    <div class="form-group">
                        <input class="form-control" placeholder="Add title" type="text">
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="rdiobox mg-r-60"><input checked name="etype" type="radio" value="event"> <span>Event</span></label> <label class="rdiobox"><input name="etype" type="radio" value="reminder"> <span>Reminder</span></label>
                    </div>
                    <div class="form-group mg-t-30">
                        <label class="tx-13 mg-b-5 tx-gray-600">Start Date</label>
                        <div class="row row-xs">
                            <div class="col-7">
                                <input class="form-control" id="mainEventStartDate" placeholder="Select date" type="text" value="">
                            </div><!-- col-7 -->
                            <div class="col-5">
                                <select class="select2 form-control main-event-time" data-placeholder="Select time" id="mainEventStartTime">
                                    <option label="Select time">
                                        Select time
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="tx-13 mg-b-5 tx-gray-600">End Date</label>
                        <div class="row row-xs">
                            <div class="col-7">
                                <input class="form-control" id="EventEndDate" placeholder="Select date" type="text" value="">
                            </div><!-- col-7 -->
                            <div class="col-5">
                                <select class="select2 form-control main-event-time" data-placeholder="Select time" id="EventEndTime">
                                    <option label="Select time">
                                        Select time
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Write some description (optional)" rows="2"></textarea>
                    </div>
                    <div class="d-flex mg-t-15 mg-lg-t-30">
                        <button class="btn btn-main-primary pd-x-25 mg-r-5" type="submit">Save</button> <a class="btn btn-light" data-bs-dismiss="modal" href="">Discard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>