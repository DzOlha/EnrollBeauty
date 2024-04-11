import CONST from "../../../../../../../constants.js";

class DepartmentCardBuilder
{
    constructor() {
        this.cardsParentId = 'department-cards-wrapper';
    }

    getParent() {
        return document.getElementById(this.cardsParentId);
    }

    createDepartmentCard(department, num = 4)
    {
        let photo = `${CONST.adminImgFolder}/departments/department_${department.id}/${department.photo_filename}`;
        return ` <div class="col-md-${num} department-card" data-department-id="${department?.id}" 
                      id="department-card-${department?.id}">
                    <div class="featured-imagebox featured-imagebox-post style1 res-767-mb-15">
                        <div class="ttm-post-thumbnail featured-thumbnail">
                            <img class="img-fluid" src="${photo}" alt="image">
                        </div>
                        <div class="featured-content box-shadow">
                            <div class="featured-title"><!-- featured-title -->
                                <h5><a href="#">${department?.name}</a></h5>
                            </div>
                            <div class="featured-desc"><!-- featured-title -->
                                <p>${department?.description}</p>
                            </div>
                            <div class="ttm-border-seperator"></div>
    <!--                        <a class="ttm-btn ttm-btn-size-sm ttm-btn-color-skincolor btn-inline ttm-icon-btn-right mt-10" href="services-details.html">Read More <i class="ti ti-angle-double-right"></i></a>-->
                        </div>
                    </div>
                </div>`;
    }
}
export default DepartmentCardBuilder;