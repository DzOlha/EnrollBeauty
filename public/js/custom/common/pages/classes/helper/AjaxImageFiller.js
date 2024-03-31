
class AjaxImageFiller
{
    constructor() {
    }
    static _populateFormDataObject(data, exceptImageName = 'photo') {
        /**
         * Populate form data object to send to the server
         * @type {FormData}
         */
        let formData = new FormData();
        for (let key in data) {
            if (typeof data[key] === 'object' && data[key] !== null && key !== exceptImageName) {
                formData.append(key, JSON.stringify(data[key]));
            } else {
                formData.append(key, data[key]);
            }
        }

        return formData;
    }
}
export default AjaxImageFiller;