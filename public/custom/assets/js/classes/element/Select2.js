
class Select2 {
    constructor(
        parentId,
        selectTitle, selectPlaceholder, selectId,
        required, withSearch = false,
        parentObject = null,
        multiple = false,
    ) {
        this.parentId = parentId;
        this.selectId = selectId;
        this.selectTitleText = selectTitle;
        this.selectPlaceholder = selectPlaceholder;
        this.required = required;
        this.withSearch = withSearch;
        this.multiple = multiple === true ? "multiple='multiple'" : '';
        this.selectParentClass = `${this.selectId}-select-parent`;
        this.selectErrorId = `${this.selectId}-error`;

        this.select2ContainerClass = 'select2-container';

        this.borderErrorClass = 'border-error';

        this.parentObject = parentObject;

        this._init();
    }
    _init() {
        let parent = document.getElementById(this.parentId);
        parent.insertAdjacentHTML('beforeend', this.html());
        this._initSelect2();
    }
    _initSelect2() {
        if(this.parentObject) {
            $(`#${this.selectId}`).select2({
                dropdownParent:  this.parentObject,
                placeholder: this.selectPlaceholder
            });
        } else {
            $(`#${this.selectId}`).select2({
                placeholder: this.selectPlaceholder
            });
        }
    }
    reset() {
        $(`#${this.selectId}`).val('').trigger('change');
        this._initSelect2();
    }
    set(value) {
        $(`#${this.selectId}`).val(value).trigger('change');
        this._initSelect2();
    }
    get() {
        return $(`#${this.selectId}`).val();
    }
    html()
    {
        let asterisk = this.required === true ? '*' : '';
        let class_ = this.withSearch === true ? 'select2-with-search' : 'select2';

        return `<div class="form-group ${this.selectParentClass}">
                    <p class="mg-b-0"><span>${asterisk}</span>${this.selectTitleText}</p>
                    <select class="form-control ${class_}" ${this.multiple}
                            id="${this.selectId}">
                        <option label="Choose one">
                        </option>
                    </select>
                    <div class="error text-danger" id="${this.selectErrorId}"></div>
               </div>`
    }
    htmlOptionLabel(text = 'Choose one') {
        return `<option label="${text}"></option>`
    }
    htmlOption(id, name) {
        return `<option value="${id}">
                        ${name}
                 </option>`
    }

    emptyOption() {
        return `<option value="clear">Clear Choice</option>`
    }

    /**
     * @param data
     * @private
     */
    _populateSelectOptions(data) {
        let select = $(`#${this.selectId}`);

        select.html('');
        select.append(this.htmlOptionLabel());

        data.forEach((item) => {
            select.append(this.htmlOption(
                item.id, item.name
            ));
        })
        if(!this.required) {
            select.prepend(this.emptyOption());
        }
        select.select2('destroy').select2();
    }

    static populate(
        selectId, data, parentObject = null,
        placeholder = 'Choose one'
    ) {
        let select = $(`#${selectId}`);

        select.html('');
        select.append(`<option label="${placeholder}" value=" ">${placeholder}</option>`);

        data.forEach((item) => {
            select.append(`<option value="${item.id}">
                                ${item.name}
                           </option>`
            );
        })
        select.select2('destroy').select2();

        if(parentObject) {
            select.select2({
                dropdownParent:  parentObject,
                placeholder: placeholder
            });
        } else {
            select.select2({
                placeholder: placeholder
            });
        }
    }

    populate(data) {
        this._populateSelectOptions(data);
        this._initSelect2();

        /**
         * Clear button
         */
        if(!this.required) {
           this.addListenerResetValue();
        }
    }

    addListenerResetValue() {
        $(`#${this.selectId}`).on('select2:select', () => {
            let value = $(`#${this.selectId}`).val();
            if(value === 'clear') {
                this.reset();
            }
        })
    }

    validate(key, validationCallback = null, errorId = null)
    {
        let select = $(`#${this.selectId}`);
        let value = select.val();

        let wrapper = $(`.${this.selectParentClass} .${this.select2ContainerClass}`);
        let errorBlock = errorId ? $(`#${errorId}`) : $(`#${this.selectErrorId}`);

        if(!value && this.required) {
            wrapper.addClass(this.borderErrorClass);
            errorBlock.html(`${this.selectTitleText} is required field!`);
            return false;
        } else {
            let result = validationCallback ? validationCallback(value) : {};
            if(result.error) {
                wrapper.addClass(this.borderErrorClass);
                errorBlock.html(result.error);
                return false;
            } else {
                wrapper.removeClass(this.borderErrorClass);
                errorBlock.html('');
                result[key] = value;
                return result;
            }
        }
    }

    static _setSelect2(selectId, value, placeholder, modalBody) {
        $(`#${selectId}`).val(value).trigger('change');
        this._initOneSelect2(selectId, placeholder, modalBody);
    }
    static _initOneSelect2(selectId, placeholder, modalBody) {
        $(`#${selectId}`).select2({
            dropdownParent: modalBody,
            placeholder: placeholder,
        });
    }

    addCallbackAfterSelectValue(callback) {
        $(`#${this.selectId}`).on('select2:select', () => {
            callback($(`#${this.selectId}`).val());
        })
    }
}
export default Select2;