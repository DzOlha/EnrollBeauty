import Regex from "../Regex.js";

class AddressRegex extends Regex
{
    constructor() {
        super(/^(?!-+$)(str\.\s[A-Za-z\s\d-]+,\s\d+|str\.\s[A-Za-z\s\d-]+,\s\d+-[A-Za-z])$/);
    }
}
export default AddressRegex;