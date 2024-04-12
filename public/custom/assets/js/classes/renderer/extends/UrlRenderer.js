import API from "../../../config/api/api.js";


class UrlRenderer
{
    constructor() {
    }

    static renderWorkerPublicProfileUrl(name, surname, id)
    {
        return `${API.OPEN.WEB.WORKER.profile}/${name.toLowerCase()}-${surname.toLowerCase()}-${id}`;
    }
}
export default UrlRenderer;