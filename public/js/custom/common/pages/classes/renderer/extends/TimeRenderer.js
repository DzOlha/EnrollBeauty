
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

    static _renderShortTime(timeString, locale = 'uk-UA', timezone = 'Europe/Kiev') {
        // Convert the time string to a Date object
        let timeDate = new Date("1970-01-01T" + timeString + "Z");

        let options = {
            hour: '2-digit',
            minute: '2-digit',
            locale: locale,
            timezone: timezone
        }
        // Format the time without the seconds
        return timeDate.toLocaleTimeString(locale, options);
    }
    static renderShortTime(timeString) {
        // Parse the input time string
        const parsedTime = new Date("1970-01-01T" + timeString + "Z");

        // Get hours and minutes in 24-hour format
        const hours = parsedTime.getUTCHours();
        const minutes = parsedTime.getUTCMinutes();

        // Format the result as HH:mm
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

        return formattedTime;
    }

}