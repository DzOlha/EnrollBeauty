
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
                `<img src="/public/images/mockup/pre-loader-1.gif" id="${GifLoader.loaderId}">`
            );
        }, timeout);
    }
    static hide(requestTimeout) {
        clearTimeout(requestTimeout);
        $(`#${GifLoader.loaderId}`).remove();
    }
}