import Regex from "../Regex.js";
class NameRegex extends Regex
{
    constructor(minLength = 3, maxLength = 50, allowWhitespace = false) {
        let regexString = allowWhitespace ? `^(?!-+$)(?=.*[^\s])[A-Za-zА-Яа-яіїІЇ\\s-]{${minLength},${maxLength}}$` : `^(?!-+$)[A-Za-zА-Яа-яіїІЇ-]{${minLength},${maxLength}}$`;
        super(new RegExp(regexString));
    }
}
export default NameRegex;