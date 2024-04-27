import CONST from "../../config/contants/constants.js";


class WorkerPublicProfileBuilder
{
    static createProfileTitleBlock(worker)
    {
        let workerFullName = `${worker.name} ${worker.surname}`;
        let workerNameUppercase = workerFullName.toUpperCase();

        return `<div class="title-box text-center">
                            <div class="page-title-heading">
                                <h1>${workerNameUppercase}</h1>
                            </div><!-- /.page-title-captions -->
                            <div class="breadcrumb-wrapper">
                                <span><a title="Homepage" href="/" aria-label="Home">
                                    home
                                </a></span>
                                <span class="ttm-bread-sep">&nbsp; / &nbsp;</span>
                                <span>${workerFullName}</span>
                            </div>  
                        </div>`
    }

    static createProfileDetailBlock(worker)
    {
        let workerImg = !worker.filename
                                ? CONST.noPhoto
                                : `${CONST.workerImgFolder}${worker.id}/${worker.filename}`;

        let workerFullName = `${worker.name} ${worker.surname}`;
        let workerNameUppercase = workerFullName.toUpperCase();

        let socials = WorkerPublicProfileBuilder.populateSocialMedia(worker);

        return `<div class="container">
                    <div class="bg-img9 tm-team-member-single-content-wrapper">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="featured-thumbnail pl-50 res-767-pl-0"><!-- featured-thumbnail -->
                                    <img class="img-fluid mb_50 res-767-mb-15" 
                                    src="${workerImg}" alt="image">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="ttm-team-member-single-list res-767-pl-15">
                                    <h2 class="ttm-team-member-single-title">${workerNameUppercase}</h2>
                                    <h5 class="ttm-team-member-single-position ttm-textcolor-skincolor">${worker.position}</h5>
                                    <p><b>Email :</b> &nbsp;${worker.email}</p>
                                    <p><b>Age :</b>&nbsp;${worker.age} Years</p>
                                    <p><b>Experience :</b> &nbsp;${worker.years_of_experience} years</p>
                                    <div class="team-media-block">
                                        <ul class="social-icons list-inline pt-20 res-767-p-0">
                                            ${socials.instagram}
                                            ${socials.tiktok}
                                            ${socials.linkedin}
                                            ${socials.facebook}
                                            ${socials.telegram}
                                            ${socials.youtube}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>                 
                </div>`
    }

    static populateSocialMedia(worker, tooltipPlace = 'tooltip-top', dataTooltip = 'data-tooltip')
    {
        let instagram = worker.Instagram !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Instagram}${worker.Instagram}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="Instagram"
                                        aria-label="Instagram">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                   </li>`
                                : '';

        let tiktok = worker.TikTok !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.TikTok}${worker.TikTok}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="TikTok"
                                        aria-label="TikTok">
                                           <svg xmlns="http://www.w3.org/2000/svg" class="tiktok-icon" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>      
                                        </a>
                                   </li>`
                                : '';

        let linkedin = worker.LinkedIn !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.LinkedIn}${worker.LinkedIn}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="LinkedIn"
                                        aria-label="LinkedIn">
                                            <i class="fa fa-linkedin"></i>
                                        </a>
                                   </li>`
                                : '';

        let facebook = worker.Facebook !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Facebook}${worker.Facebook}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="Facebook"
                                        aria-label="Facebook">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                   </li>`
                                : '';

        let telegram = worker.Telegram !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.Telegram}${worker.Telegram}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="Telegram"
                                        aria-label="Telegram">
                                            <i class="fa fa-telegram"></i>
                                        </a>
                                   </li>`
                                : '';

        let youtube = worker.YouTube !== null
                                ? `<li>
                                        <a href="${CONST.WORKER_SOCIAL_NETWORKS_ROOT_URLS.YouTube}${worker.YouTube}" 
                                        target="_blank" class="${tooltipPlace}" ${dataTooltip}="YouTube"
                                        aria-label="YouTube">
                                            <i class="fa fa-youtube"></i>
                                        </a>
                                   </li>`
                                : '';

        return {
            instagram: instagram,
            tiktok: tiktok,
            linkedin: linkedin,
            facebook: facebook,
            telegram: telegram,
            youtube: youtube
        }
    }

    static createDescriptionBlock(worker, blockTitle = 'ABOUT ME')
    {
        return `<div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="ttm-team-member-single-title pt-10 pb-10 res-575-p-0">
                                ${blockTitle}
                            </h3>
                            <p>${worker.description}</p>
                          </div>
                    </div>
                </div>`
    }
}
export default WorkerPublicProfileBuilder;