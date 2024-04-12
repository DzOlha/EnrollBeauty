import CONST from "../../../../../../config/contants/constants.js";
import WorkerPublicProfileBuilder from "../../../../../../classes/builder/WorkerPublicProfileBuilder.js";
import UrlRenderer from "../../../../../../classes/renderer/extends/UrlRenderer.js";

class WorkerCardBuilder
{
    constructor() {
        this.cardsTwoWrapperId = 'workers-two-wrapper';
        this.cardsOneWrapperId = 'workers-one-wrapper';
    }

    getParentForTwoLeftCards() {
        return document.getElementById(this.cardsTwoWrapperId);
    }

    getParentForOneRightCard() {
        return document.getElementById(this.cardsOneWrapperId);
    }

    createWorkerCard(worker, bottomCard = false) {
        let workerImg = !worker.filename
            ? CONST.noPhoto
            : `${CONST.workerImgFolder}${worker.id}/${worker.filename}`;

        let workerFullName = `${worker.name} ${worker.surname}`;

        // no tooltip
        let socials = WorkerPublicProfileBuilder.populateSocialMedia(
            worker,
            '', ''  // no tooltip
        );

        let classes = bottomCard === true ? 'mt-30 res-575-mt-15' : '';

        let profileLink = UrlRenderer.renderWorkerPublicProfileUrl(
            worker.name, worker.surname, worker.id
        );

        return `<div class="featured-imagebox featured-imagebox-team style1 ${classes}">
                    <div class="featured-thumbnail">
                        <img class="img-fluid" src="${workerImg}" alt="image">
                        <div class="media-block">
                            <ul class="social-icons list-inline">
                                ${socials.instagram}
                                ${socials.tiktok}
                                ${socials.linkedin}
                                ${socials.facebook}
                                ${socials.github}
                                ${socials.telegram}
                                ${socials.youtube}
                            </ul>
                        </div>
                    </div>
                    <div class="featured-content box-shadow">
                        <div class="featured-title">
                            <h5><a href="${profileLink}" target="_blank">
                                    ${workerFullName}
                                </a>
                            </h5>
                        </div>
                        <p class="category">${worker.position}</p>
                    </div>
                </div>`;
    }
}
export default WorkerCardBuilder;