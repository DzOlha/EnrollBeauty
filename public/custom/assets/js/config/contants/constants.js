
const PUBLIC_IMAGES = '/public/custom/assets/img/';
const CONST = {
    closeIcon: PUBLIC_IMAGES + 'system/icons/close.svg',
    gifLoader: '/public/mockups/open/assets/img/pre-loader-1.gif',
    arrowDown: PUBLIC_IMAGES + 'system/icons/arrows_down.svg',
    arrowUp: PUBLIC_IMAGES + 'system/icons/arrows_up.svg',

    workerSystemProfile: '/web/admin/worker/profile',

    adminImgFolder: PUBLIC_IMAGES + 'uploads/admins',
    workerImgFolder: PUBLIC_IMAGES + 'uploads/workers/worker_',
    userImgFolder: PUBLIC_IMAGES + 'uploads/users/user_',

    noPhoto: PUBLIC_IMAGES + 'system/nophoto.jpg',

    WORKER_SOCIAL_NETWORKS_ROOT_URLS:
    {
        Instagram: 'https://www.instagram.com/',
        Facebook: 'https://www.facebook.com/',
        TikTok: 'https://www.tiktok.com/',
        YouTube: 'https://youtube.com/',
        LinkedIn: 'https://www.linkedin.com/',
        Github: 'https://github.com/',
        Telegram: 'https://t.me/'
    },

    WORKER_SOCIAL_NETWORKS_URLS_REGEX:
    {
        Instagram: /^https:\/\/www\.instagram\.com\/[a-zA-Z0-9_]+\/?(?:\?[\w=&]+)?$/,
        Facebook:  /^https:\/\/www\.facebook\.com\/(profile\.php\?id=)?[0-9]{1,}$/,
        TikTok: /^https:\/\/www\.tiktok\.com\/@[\w.-]+(\?_t=.+)?$/,
        YouTube: /^https:\/\/youtube\.com\/@[\w.-]+(?:\?si=[a-zA-Z0-9_-]+)?$/,
        LinkedIn: /^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9_-]+(\?.*)?$/,
        Github: /^https:\/\/github\.com\/[a-zA-Z0-9-]+$/,
        Telegram:  /^https:\/\/t\.me\/[a-zA-Z0-9_]+$/
    }
}
export default CONST;