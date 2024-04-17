import Regex from "../Regex.js";

class PasswordRegex extends Regex
{
    constructor() {
        super(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/);
    }
}
export default PasswordRegex;