import Renderer from "../Renderer.js";
class DateRenderer extends Renderer {
    constructor() {
        super();
    }

    /**
     * Converts datetime into '2 Month' format
     * @param value
     * @param locale
     * @param timezone
     * @returns {string}
     */
    render(value, locale = 'en-US', timezone = 'Europe/Kiev') {
        // console.log(value)
        let sqlDateTime = new Date(value);

        let options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timezone: timezone,
            locale: locale
        };

        let dateFormatter = new Intl.DateTimeFormat(locale, options);

        return dateFormatter.format(sqlDateTime)
    }

    /**
     * @param dateString in yyyy-mm-dd hh:mm:ss format
     * @param locale
     * @param timezone
     * return - 2 Month, 12:20
     */
    renderDatetime(dateString, locale = 'en-US', timezone = 'Europe/Kiev') {
        let sqlDateTime = new Date(dateString);

        let dateOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            timezone: timezone,
            locale: locale
        };

        let timeOptions = {
            hour: 'numeric',
            minute: 'numeric',
            hour12: false, // set 24-hours format
            // timeZoneName: 'short',
            timezone: timezone,
            locale: locale
        };

        let dateFormatter = new Intl.DateTimeFormat(locale, dateOptions);
        let timeFormatter = new Intl.DateTimeFormat(locale, timeOptions);

        let formattedDate = dateFormatter.format(sqlDateTime);
        let formattedTime = timeFormatter.format(sqlDateTime);

        return `${formattedDate}, ${formattedTime}`;
    }


    /**
     *
     * @param dateString
     * @param timezone
     * @param locale
     * @returns {string}
     *
     * dateString = 'YYYY-MM-DD'
     */
    getDayOfWeek(
        dateString, locale = 'en-US', timezone = 'Europe/Kiev'
    ) {
        let date = new Date(dateString);

        let options = {
            timeZone: timezone,
            weekday: 'short',
            locale: locale
        };

        return this.capitalizeFirstLetter(
            new Intl.DateTimeFormat(locale, options).format(date)
        );
    }
    capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    getUnixTimestamp(dateString, dateFormat = 'DD/MM/YYYY') {
        const trimmedDate = dateString.trim();
        const parsedDate = moment(trimmedDate, dateFormat);
        if (parsedDate.isValid()) {
            return parsedDate.unix();
        } else {
            //console.log(`Invalid date: ${trimmedDate}`);
            return null; // Handle invalid dates as needed
        }
    }

    _formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    /**
     *
     * @param startDate YYYY-MM-DD
     * @param endDate YYYY-MM-DD
     * @returns {*[]}
     */
    getDatesBetween(startDate, endDate) {
        const dateArray = [];
        let currentDate = new Date(startDate);
        let _endDate = new Date(endDate);

        // Iterate over the dates and push them to the array
        while (currentDate.getTime() <= _endDate.getTime()) {
            dateArray.push(this._formatDate(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }

        return dateArray;
    }

    /**
     * YYYY-MM-DD -> 2 Jun: Mn
     * @param dateString
     * @param locale
     * @param timezone
     * @returns {string}
     */
    shortRender(
        dateString, locale = 'en-US', timezone = 'Europe/Kiev'
    ) {
        const options = {
            month: 'short',
            day: 'numeric',
            weekday: 'short',
            locale: locale,
            timezone: timezone
        };
        const date = new Date(dateString);
        const formattedDate = date.toLocaleDateString(locale, options);

        // Extracting the day and month abbreviation
        const [, monthAbbreviation, day] = formattedDate.split(' ');

        return `${day} ${monthAbbreviation}`;
    }
}
export default DateRenderer;