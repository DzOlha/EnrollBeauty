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
        return `<img src="/public/images/mockup/pre-loader-1.gif" id="${loaderId}">`
    }
}