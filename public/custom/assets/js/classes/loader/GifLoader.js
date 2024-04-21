import CONST from "../../config/contants/constants.js";

class GifLoader {

    static loaderId = 'gif-loader';

    /**
     * Return requestTimeout
     * @param parent
     * @param timeout
     * @returns {number}
     */
    static showBeforeBegin(parent, timeout = 500) {
        return setTimeout(() => {
            parent.insertAdjacentHTML(
                'beforebegin',
                `<img src="${CONST.gifLoader}" id="${GifLoader.loaderId}">`
            );
        }, timeout);
    }
    static showAfterEnd(parent, timeout = 500) {
        return setTimeout(() => {
            parent.insertAdjacentHTML(
                'afterend',
                `<img src="${CONST.gifLoader}" id="${GifLoader.loaderId}">`
            );
        }, timeout);
    }
    static showInside(parent, timeout = 500) {
        return setTimeout(() => {
            parent.insertAdjacentHTML(
                'beforeend',
                `<img src="${CONST.gifLoader}" id="${GifLoader.loaderId}">`
            );
        }, timeout);
    }
    static hide(requestTimeout) {
        clearTimeout(requestTimeout);
        $(`#${GifLoader.loaderId}`).remove();
    }
}
export default GifLoader;