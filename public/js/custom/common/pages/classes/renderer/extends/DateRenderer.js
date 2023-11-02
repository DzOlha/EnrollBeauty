
class DateRenderer extends Renderer {
    constructor() {
        super();
    }
    static render(value) {
        // console.log(value)
        let sqlDateTime = new Date(value);

        let options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        let dateFormatter = new Intl.DateTimeFormat('en-US', options);

        return dateFormatter.format(sqlDateTime);
    }
}