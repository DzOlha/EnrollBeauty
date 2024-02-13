import CONST from "../../../../constants.js";

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
    static hide(requestTimeout) {
        clearTimeout(requestTimeout);
        $(`#${GifLoader.loaderId}`).remove();
    }
}
export default GifLoader;