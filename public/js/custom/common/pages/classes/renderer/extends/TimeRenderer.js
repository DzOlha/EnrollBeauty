
class TimeRenderer extends Renderer {
    constructor() {
        super();
    }

    /**
     * From datetime -> to time 12:00
     * @param value
     * @returns {string}
     */
    static render(value) {
        let sqlDateTime = new Date(value);

        let options = {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        };

        let timeFormatter = new Intl.DateTimeFormat('en-GB', options);

        return timeFormatter.format(sqlDateTime);
    }

    static renderShortTime(timeString) {
        // Convert the time string to a Date object
        let timeDate = new Date("1970-01-01T" + timeString + "Z");

        let options = {
            hour: '2-digit',
            minute: '2-digit',
        }
        // Format the time without the seconds
        return timeDate.toLocaleTimeString([], options);
    }
}