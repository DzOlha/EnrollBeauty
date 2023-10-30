
class TimeRenderer extends Renderer {
    constructor() {
        super();
    }
    static render(value) {
        let sqlDateTime = new Date(value);

        let options = {
            hour: '2-digit',
            minute: '2-digit'
        };

        let timeFormatter = new Intl.DateTimeFormat('en-US', options);

        return timeFormatter.format(sqlDateTime);
    }
}