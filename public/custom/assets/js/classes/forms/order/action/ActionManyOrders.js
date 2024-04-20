import Notifier from "../../../notifier/Notifier.js";
import OptionBuilder from "../../../builder/OptionBuilder.js";

class ActionManyOrders
{
    constructor(
        requester, table
    ) {
        this.requester = requester;
        this.table = table;
        this.buttonsWrapperId = 'action-buttons-wrapper';
        this.deleteEmptyMessage = 'Please, choose some rows to perform the action';

        // the same here and for all tables with mass actions
        this.dataIdAttribute = 'data-id';
        this.tdCheckClass = `td-checkbox-cell`;
        this.checkboxClass = 'checkbox';

        this.actionButtonId = '';
        this.listenerAttr = 'data-listener-set';
    }

    appendButton(buttonId, buttonClass, buttonText, apiAction){
        let parent = document.getElementById(this.buttonsWrapperId);
        if(!parent) return;
        parent.insertAdjacentHTML(
            'beforeend',
            OptionBuilder.createButton(
                buttonId, buttonClass, buttonText, true
            )
        )

        this.addListenerHandleMassAction(apiAction);
    }


    /**
     * Callback to run after filling the table
     */
    addListenerClickOnCellOfCheckbox(buttons) {
        let tds = document.getElementsByClassName(
            `${this.tdCheckClass}`
        );

        let size = tds.length;
        for (let i = 0; i < size; i++) {
            tds[i].addEventListener('click', (e) => {
                this.listenerCheckCheckboxCell(e, buttons)
            }, false);
        }
    }

    listenerCheckCheckboxCell = (e, buttons) => {
        let checkbox = e.currentTarget.querySelector(`.${this.checkboxClass}`);

        let checked = checkbox.getAttribute('checked');
        if (!checked || checked === false) {
            checkbox.setAttribute('checked', true);

            buttons.forEach((button) => {
                this.changeSubmitBtnStatus(1, button);
            })
        } else {
            checkbox.removeAttribute('checked');
        }
    }

    _getAllCheckedIds() {
        let checkboxes = document.querySelectorAll(
            `.${this.checkboxClass}[checked="true"]`
        );
        let size = checkboxes.length;

        let ids = [];
        for(let i = 0; i < size; i++) {
            let row = checkboxes[i].parentElement.parentElement;
            if (row === null) continue;

            let id = parseInt(row.getAttribute(this.dataIdAttribute));
            ids.push(id);
        }
        //console.log(ids);
        return ids;
    }

    changeSubmitBtnStatus(status, buttonId) {
        let btn = document.getElementById(buttonId);
        if(status === 1) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', '');
        }
    }

    addListenerHandleMassAction(apiUrl)
    {
        let btn = document.getElementById(this.actionButtonId);
        if(!btn) return;

        if(!btn.getAttribute(this.listenerAttr)) {
            btn.addEventListener('click', () => {
                let ids = this._getAllCheckedIds();

                if(ids.length === 0) {
                    Notifier.showErrorMessage(this.deleteEmptyMessage);
                    return;
                }

                this.requester.post(
                    apiUrl,
                    {ids: ids},
                    this.successCallback.bind(this),
                    this.errorCallback.bind(this)

                )
            });
            btn.setAttribute(this.listenerAttr, 'true');
        }
    }

    successCallback(response) {
        Notifier.showSuccessMessage(response.success);
        this.table.regenerate();
    }

    errorCallback(response) {
        Notifier.showErrorMessage(response.error);
    }

}
export default ActionManyOrders;