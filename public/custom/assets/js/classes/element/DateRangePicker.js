import DateRenderer from "../renderer/extends/DateRenderer.js";

class DateRangePicker
{
    constructor(pickerId, format = 'DD/MM/YYYY') {
        this.pickerId = pickerId;
        this.format = format;
        this.errorClass = 'border-danger';
        this.errorTextClass = 'text-danger';
    }

    _init(
        minToday = null, countDays =  1
    ) {
        const currentDate = new Date();

        const end = new Date();
        end.setDate(currentDate.getDate()-countDays);

        $(`#${this.pickerId}`).daterangepicker({
            locale: {
                format: this.format, // Date format
            },
            opens: 'right',
            showDropdowns: false,
            startDate: end,
            endDate: currentDate,
            minDate: minToday ?? currentDate,  // Minimum selectable date (current date)
        });
    }

    validate() {
        let dateRangeInput = $(`#${this.pickerId}`);
        let dateRange = dateRangeInput.val()
            .split('-')
            .map(
                (item) => DateRenderer.getUnixTimestamp(item, this.format)
            );

        let validDateRange = true;
        let error = $(`#${this.pickerId}-error`);

        if (dateRange[0] > dateRange[1]) {
            validDateRange = false;
            dateRangeInput.addClass(this.errorClass);

            error.html(
                'The start date should be less or equal to the end date'
            );
            error.addClass(this.errorTextClass)
        }

        if (validDateRange) {
            if (dateRangeInput.hasClass(this.errorClass))
                dateRangeInput.removeClass(this.errorClass);

            error.html('');
            error.removeClass(this.errorTextClass);

            return dateRange;
        }
        return false;
    }
}
export default DateRangePicker;