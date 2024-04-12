
class DatePicker {
    constructor(inputId) {
        this.inputId = inputId;
    }
    addListenerOnChange(callback) {
        let input = document.getElementById(
            this.inputId
        );
        input.addEventListener('pDatePickerChange', (e) => {
            callback(e);
        })
    }

    /**
     * @param date in yyyy-mm-dd format
     */
    set(date) {
        const datePickerInstance = $(`#${this.inputId}`).data("plugin_pDatePicker");
        const selectedDate = new Date(date);
        datePickerInstance.setSelectedDate(selectedDate, true);
    }
}
export default DatePicker;