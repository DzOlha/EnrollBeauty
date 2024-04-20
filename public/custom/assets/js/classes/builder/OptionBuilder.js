import CONST from "../../config/contants/constants.js";


class OptionBuilder {
    createOptionLabel(text = 'Choose one') {
        return `<option label="${text}"></option>`
    }

    createOption(id, name) {
        return `<option value="${id}">
                        ${name}
                 </option>`
    }
    static createGifLoader(loaderId = 'gif-loader') {
        return `<img src="${CONST.gifLoader}" id="${loaderId}">`
    }

    static createCellWithCheckbox(
        objectId, dataAttributeId,
        checkboxClass, classTdWithCheckbox
    ) {
        return `<td class="${classTdWithCheckbox} td-row-${objectId}" 
                    ${dataAttributeId}="${objectId}">
                    <input type="checkbox" class="form-check-input ${checkboxClass}">
                    <span>${objectId}</span>
                </td>`;
    }

    static createStatusCell(status) {
        let props = {
            '-1': {
                'color': 'bg-danger',
                'text': 'Canceled'
            },
            '0': {
                'color': 'bg-info',
                'text': 'Upcoming'
            },
            '1': {
                'color': 'bg-success',
                'text': 'Completed'
            }
        }

        return `<td class="${props[status]['color']} text-center">
                    ${props[status]['text']}
                </td>`
    }

    static createButton(buttonId, buttonClass, text, disabled = false)
    {
        let dis = disabled === true ? 'disabled' : '';
        return `<button id="${buttonId}" class="${buttonClass} btn btn-block" ${dis} type="button">
                    ${text}
                </button>`
    }
}
export default OptionBuilder;