
class ConfirmationModal {
    constructor() {
        this.confirmationModalId = 'modalAlertConfirmation';
        this.confirmationHeadlineId = 'modalAlertConfirmation-headline';
        this.confirmationMessageId = 'modalAlertConfirmation-message';
        this.confirmationSubmitId = 'modalAlertConfirmation-submit';
        this.confirmationContentId = 'modalAlertConfirmation-content';
        this.confirmationCloseId = 'modalAlertConfirmation-close';
    }
    show(headline, content, message) {
        $(`#${this.confirmationHeadlineId}`).html(headline);

        let c = $(`#${this.confirmationContentId}`);
        c.html('');
        c.append(content);
        $(`#${this.confirmationMessageId}`).html(
            message
        );
        $(`#${this.confirmationModalId}`).show();
    }

    hide() {
        $(`#${this.confirmationModalId}`).hide();
    }

    submit(callback) {
        let confirm = document.getElementById(this.confirmationSubmitId);
        confirm.removeEventListener('click', callback); // Remove previous listener
        confirm.addEventListener('click', callback);
    }
    close() {
        let callback = () => {
            this.hide();
        }
        let close = document.getElementById(this.confirmationCloseId);
        close.removeEventListener('click', callback); // Remove previous listener
        close.addEventListener('click', callback);
    }
}