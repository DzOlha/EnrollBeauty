class OptionBuilder {
    static createOptionLabel(text = 'Choose one') {
        return `<option label="${text}"></option>`
    }

    static createOption(id, name) {
        return `<option value="${id}">
                        ${name}
                 </option>`
    }
}