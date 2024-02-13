import Admin from "../../../../common/pages/admin/profile/Admin.js";
import Requester from "../../../../common/pages/classes/requester/Requester.js";
import AffiliatesTable from "../../../../common/pages/classes/table/extends/AffiliatesTable.js";
import API from "../../../../common/pages/api.js";
import DateRenderer from "../../../../common/pages/classes/renderer/extends/DateRenderer.js";

$(function () {
    let requester = new Requester();

    let admin = new Admin(requester);
    admin.getUserInfo();

    let dateRenderer = new DateRenderer();
    let table = new AffiliatesTable(
        requester, API.ADMIN.API.AFFILIATE.get["all-limited"], dateRenderer
    );

    table.POPULATE();
})