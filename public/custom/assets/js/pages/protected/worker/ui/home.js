import Requester from "../../../../classes/requester/Requester.js";
import Worker from "../classes/Worker.js";


$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    /**
     * Fill the admin info
     */
    worker.getUserInfo();
});