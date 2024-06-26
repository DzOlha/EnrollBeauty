import Notifier from "../notifier/Notifier.js";


class OrderConfirmationModal
{
    constructor(requester, confirmationModal, apiUrl) {
        this.requester = requester;
        this.confirmationModal = confirmationModal;
        this.apiUrl = apiUrl;
        this.submitSearchButtonId = 'submit-search-button';
        this.cardBaseId = 'schedule-card';
    }
    getTriggerIcon(id) {
        // return document.querySelector(
        //     `#${this.cardBaseId}-${id} .fe-x`
        // );
        return null;
    }
    getDataAttributes(triggerIcon) {
        // let scheduleId = triggerIcon.getAttribute('data-schedule-id');
        // let orderId = triggerIcon.getAttribute('data-order-id');
        //
        // return {
        //     'order_id': orderId,
        //     'schedule_id': scheduleId
        // }
        return {};
    }
    getDataToSend(data) {
        return data;
    }
    getConfirmationModalContent(card = null, triggerIcon = null) {
        return {
            'headline': 'Confirmation!',
            'content': card ?? '',
            'message': ''
        }
    }
    addListener(scheduleId) {
        let triggerIcon = this.getTriggerIcon(scheduleId);

        if (triggerIcon === null) return;

        /**
         * Create order for service
         */
        const handleTriggerIconClick = (e) => {
            e.preventDefault();
            let selectedCard = document.getElementById(`${this.cardBaseId}-${scheduleId}`);
            let card = null;
            if(selectedCard !== null) {
                card = selectedCard.cloneNode(true);
            }

            let window = this.getConfirmationModalContent(card, triggerIcon);
            this.confirmationModal.show(
                window.headline,
                window.content,
                window.message
            )

            let data = this.getDataAttributes(triggerIcon);
            this.confirmationModal.submit(
                handleConfirmClick,
                this.getDataToSend(data)
            );
            this.confirmationModal.close();
        }

        const handleConfirmClick = (dataToSend) => {
            this.requestTimeout = this.confirmationModal.showLoader();
            this.requester.post(
                this.apiUrl,
                dataToSend,
                this._successCallback.bind(this),
                this._errorCallback.bind(this)
            );
        }

        triggerIcon.removeEventListener('click', handleTriggerIconClick); // Remove previous listener
        triggerIcon.addEventListener('click', handleTriggerIconClick);
    }
    _errorCallback(response) {
        this.confirmationModal.hideLoader(this.requestTimeout);
        Notifier.showErrorMessage(response.error);
    }
    _successCallback(response) {
        this.confirmationModal.hideLoader(this.requestTimeout);
        /**
         * Hide confirmation modal window
         */
        this.confirmationModal.hide();
        /**
         * Show success message
         */
        Notifier.showSuccessMessage(response.success);
    }
}
export default OrderConfirmationModal;