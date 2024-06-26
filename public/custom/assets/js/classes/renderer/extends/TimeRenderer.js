import Renderer from "../Renderer.js";
class TimeRenderer extends Renderer {
    constructor() {
        super();
    }

    /**
     * From datetime -> to time 12:00
     * @param value
     * @returns {string}
     */
    render(value) {
        let sqlDateTime = new Date(value);

        let options = {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        };

        let timeFormatter = new Intl.DateTimeFormat('en-GB', options);

        return timeFormatter.format(sqlDateTime);
    }

    _renderShortTime(timeString, locale = 'uk-UA', timezone = 'Europe/Kiev') {
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

    renderShortTime(timeString) {
        // Parse the input time string
        const parsedTime = new Date("1970-01-01T" + timeString + "Z");

        // Get hours and minutes in 24-hour format
        const hours = parsedTime.getUTCHours();
        const minutes = parsedTime.getUTCMinutes();

        // Format the result as HH:mm
        const formattedTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

        return formattedTime;
    }

    /**
     *
     * @param timeString in hh:mm:ss
     * @returns {*}
     */
    static timeToDecimal(timeString) {
        // Split the time string into hours, minutes, and seconds
        const [hours, minutes, seconds] = timeString.split(':').map(Number);

        // Calculate the decimal representation
        const decimalTime = hours + minutes / 60 + seconds / 3600;

        return decimalTime;
    }

    /**
     * hh:mm:ss -> hh:mm
     * @param timeString in hh:mm:ss format
     */
    static hmsToHm(timeString)
    {
        let arr = timeString.split(':');
        arr.pop();

        return arr.join(':');
    }
}
export default TimeRenderer;