class OptionBuilder {
    createOptionLabel(text = 'Choose one') {
        return `<option label="${text}"></option>`
    }

    createOption(id, name) {
        return `<option value="${id}">
                        ${name}
                 </option>`
    }
}