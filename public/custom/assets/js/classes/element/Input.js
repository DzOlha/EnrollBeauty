
class Input {
    static borderDanger = 'border-danger';
    static validateInput(
        selectorId, key, validationCallback,
        typeFile = false,
        errorSelectorId = null
    ) {
        let input = document.getElementById(selectorId);

        let error = null;
        if (errorSelectorId !== null) {
            error = document.getElementById(`${errorSelectorId}`);
        } else {
            error = document.getElementById(`${selectorId}-error`);
        }


        let value = null;
        if(!typeFile) {
            value = input.value.trim();
        } else {
            value = input.files[0];
        }
        let valid = true;

        let callback = validationCallback(value);
        if (callback.error) {
            valid = false;
            input.classList.add(Input.borderDanger);
            input.focus();
            error.innerHTML = callback.error;
        }

        if (valid) {
            if (input.classList.contains(Input.borderDanger)) {
                input.classList.remove(Input.borderDanger);
            }
            error.innerHTML = '';
        }

        /**
         * Result
         */
        if (valid) {
            let result = {};
            result[key] = value;

            return result
        } else {
            return false;
        }
    }
}
export default Input;