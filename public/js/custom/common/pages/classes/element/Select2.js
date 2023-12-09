
class Select2 {
    constructor(
        parentId,
        selectTitle, selectPlaceholder, selectId,
        required, withSearch = false,
        parentObject = null
    ) {
        this.parentId = parentId;
        this.selectId = selectId;
        this.selectTitleText = selectTitle;
        this.selectPlaceholder = selectPlaceholder;
        this.required = required;
        this.withSearch = withSearch;
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
    html()
    {
        let asterisk = this.required === true ? '*' : '';
        let class_ = this.withSearch === true ? 'select2-with-search' : 'select2';

        return `<div class="form-group ${this.selectParentClass}">
                    <p class="mg-b-0"><span>${asterisk}</span>${this.selectTitleText}</p>
                    <select class="form-control ${class_}"
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
        select.select2('destroy').select2();
    }

    populate(data) {
        this._populateSelectOptions(data);
        this._initSelect2();
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
}