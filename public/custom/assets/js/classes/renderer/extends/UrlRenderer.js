import API from "../../../config/api/api.js";

class UrlRenderer
{
    static englishRegex = /^[a-zA-Z]+$/;
    constructor() {
    }

    static renderWorkerPublicProfileUrl(name, surname, id)
    {
        if(!UrlRenderer.englishRegex.test(name)) {
            name = 'Name';
        }
        if(!UrlRenderer.englishRegex.test(surname)) {
            surname = 'Surname';
        }
        return `${API.OPEN.WEB.WORKER.profile}/${name.toLowerCase()}-${surname.toLowerCase()}-${id}`;
    }
}
export default UrlRenderer;