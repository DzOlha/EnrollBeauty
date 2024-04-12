
class ImageDropify {
    constructor(
        imageWrapper, imageInputId, fieldName,
        allowedImageExtensions = ['jpg', 'svg', 'png', 'jpeg']
    ) {
        this.errorClass = 'error';
        this.dropifyClass = 'dropify';

        this.imageInputId = imageInputId;
        this.fieldName = fieldName;

        this.inputFileWrapperSelector = imageWrapper;
        this.allowedImageExtensions = allowedImageExtensions;
    }

    static init(imageInputId) {
        $(`#${imageInputId}`).dropify();
    }

    set(imageInputId, imagePath) {
        this._setDefaultImage(imageInputId, imagePath);
    }

    /**
     *
     * @param imageInputId
     * @param imagePath
     * @private
     *
     * set preview of the image, but not the value of input (type file)
     */
    _setDefaultImage(imageInputId, imagePath) {
        let imageInputDropify = $(`#${imageInputId}`).dropify(
            {
                defaultFile: imagePath
            });
        imageInputDropify = imageInputDropify.data(this.dropifyClass);
        imageInputDropify.resetPreview();
        imageInputDropify.clearElement();
        imageInputDropify.settings.defaultFile = imagePath;
        imageInputDropify.destroy();
        imageInputDropify.init();
    }

    validate = (value) =>  {
        let result = {};

        let wrapper = document.querySelector(this.inputFileWrapperSelector)
        if (!value) {
            return result;
        }

        const fileName = value.name;
        const fileExtension = fileName.split('.').pop();

        if (!this.allowedImageExtensions.includes(fileExtension)) {
            wrapper.classList.add(this.errorClass);
            result.error = `Your ${this.fieldName} photo should be one of the following format: .jpg, .jpeg, .png, .svg`
            return result;
        }
        if (wrapper.classList.contains(this.errorClass)) {
            wrapper.classList.remove(this.errorClass);
        }
        return result;
    }
}
export default ImageDropify;