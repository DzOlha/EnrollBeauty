
class ScheduleRenderer {
    constructor() {
        this.builder = new ScheduleHtmlBuilder();
    }

    /**
     * response example
     *  {
     *      success: true,
     *      data: {
     *          schedule: {
     *              0: {
     *                schedule_id: ,
     *                service_id: ,
     *                service_name: ,
     *                worker_id: ,
     *                worker_name: ,
     *                worker_surname: ,
     *                affiliate_id: ,
     *                city: ,
     *                address: ,
     *                day: ,
     *                start_time: ,
     *                end_time: ,
     *                price: ,
     *                currency:
     *              },
     *              1: {
     *
     *              }
     *              .........
     *          },
     *          departments: {
     *              0: {
     *                  id:
     *                  name:
     *              }
     *              .........
     *          },
     *          active_department: {
     *              id:
     *              name:
     *          }
     *      }
     *  }
     */
    render(response) {

    }
}