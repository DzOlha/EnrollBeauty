
class DateRenderer extends Renderer {
    constructor() {
        super();
    }
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
}