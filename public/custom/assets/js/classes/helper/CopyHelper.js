
class CopyHelper
{
    static handleCopy = (text) =>
    {
        let temp = $("<input>");
        $("body").append(temp);
        temp.val(text).select();
        document.execCommand("copy");
        temp.remove();

        return true;
    }
}
export default CopyHelper;