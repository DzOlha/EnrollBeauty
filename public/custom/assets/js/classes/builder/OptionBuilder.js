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
}
export default OptionBuilder;