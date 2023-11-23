
class FormModal {
    constructor(modalId) {
        this.parent = 'page';
        this.modalId = `${modalId}`;
        this.modalHeadlineId = `${modalId}-headline`;
        this.modalContentId = `${modalId}-content`;
        this.modalCloseId = `${modalId}-close`;
        this.modalSubmitId = `${modalId}-submit`;
    }
    show(headline, content, buttonText) {
        /**
         * Insert the outline of the modal in the page structure
         * @type {string}
         */
        let modal = FormBuilder.createModalForm(this.modalId);
        let page = document.getElementById(this.parent);
        if(!page) return;
        page.insertAdjacentHTML('afterend', modal);

        /**
         * Populate modal with the content
         */
        $(`#${this.modalHeadlineId}`).html(headline);
        $(`#${this.modalSubmitId}`).html(buttonText);
        let c = $(`#${this.modalContentId}`);
        c.html('');
        c.append(content);

        $(`#${this.modalId}`).show();
    }

    hide() {
        $(`#${this.modalId}`).hide();
    }
    submit(callback, data) {
        let f = () => {
            callback(data);
        }
        let confirm = document.getElementById(this.modalSubmitId);

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
        let close = document.getElementById(this.modalCloseId);
        close.removeEventListener('click', callback); // Remove previous listener
        close.addEventListener('click', callback);
    }
}