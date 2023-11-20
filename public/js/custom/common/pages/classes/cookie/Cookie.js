class Cookie {
    static set(name, value) {
        document.cookie = `${name}=${value}`;
    }

    static get(name) {
        const _name = name + "=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const cookieArray = decodedCookie.split(';');
        for (let i = 0; i < cookieArray.length; i++) {
            let cookie = cookieArray[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1);
            }
            if (cookie.indexOf(_name) === 0) {
                return cookie.substring(_name.length, cookie.length);
            }
        }
        return "";
    }

    static remove(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }
}