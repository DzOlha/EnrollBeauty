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
        $(`#${this.confirmationModalId}`).fadeIn(400);
    }

    hide(duration = 400) {
        $(`#${this.confirmationModalId}`).fadeOut(duration);
    }

    submit(callback, data) {
        let f = () => {
            callback(data);
        }
        let confirm = document.getElementById(this.confirmationSubmitId);

        /**
         * Replace the submit button with its copy to reset all event listeners
         * @type {Node}
         */
        let copy = confirm.cloneNode(true);
        confirm.replaceWith(copy);

        copy.removeEventListener('click', f); // Remove previous listener
        copy.addEventListener('click', f);
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