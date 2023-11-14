
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
    static render(value, locale = 'en-US', timezone = 'Europe/Kiev') {
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
     *
     * @param dateString
     * @param timezone
     * @param locale
     * @returns {string}
     *
     * dateString = 'YYYY-MM-DD'
     */
    static getDayOfWeek(
        dateString,  locale = 'en-US', timezone = 'Europe/Kiev'
    ) {
        let date = new Date(dateString);

        let options = {
            timeZone: timezone,
            weekday: 'short',
            locale: locale
        };

        return DateRenderer.capitalizeFirstLetter(
            new Intl.DateTimeFormat(locale, options).format(date)
        );
    }
    static capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    static getUnixTimestamp(dateString, dateFormat = 'DD/MM/YYYY') {
        const trimmedDate = dateString.trim();
        const parsedDate = moment(trimmedDate, dateFormat);
        if (parsedDate.isValid()) {
            return parsedDate.unix();
        } else {
            console.log(`Invalid date: ${trimmedDate}`);
            return null; // Handle invalid dates as needed
        }
    }

    /**
     *
     * @param startDate YYYY-MM-DD
     * @param endDate YYYY-MM-DD
     * @returns {*[]}
     */
    static getDatesBetween(startDate, endDate) {
        const dateArray = [];
        let currentDate = new Date(startDate);
        let _endDate = new Date(endDate);
        //console.log('currentDate = ' + currentDate);

        // Iterate over the dates and push them to the array
        while (currentDate <= _endDate) {
            dateArray.push(new Date(currentDate).toISOString().split('T')[0]);
            currentDate.setDate(currentDate.getDate() + 1);
        }

        return dateArray;
    }

    /**
     * YYYY-MM-DD -> 2 Jun: Mn
     * @param dateString
     * @returns {string}
     */
    static shortRender(
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