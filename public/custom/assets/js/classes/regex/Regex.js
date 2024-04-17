
class Regex
{
    /**
     *
     * @param RegExp
     */
    constructor(RegExp) {
        this.pattern = RegExp;
    }
    get() {
        return this.pattern;
    }
    test(value)
    {
        return this.pattern.test(value);
    }
}
export default Regex;