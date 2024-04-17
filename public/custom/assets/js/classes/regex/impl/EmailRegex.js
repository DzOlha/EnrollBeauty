import Regex from "../Regex.js";

class EmailRegex extends Regex
{
    constructor() {
        super(/^(?=.{1,100}$)[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/);
    }
}
export default EmailRegex;