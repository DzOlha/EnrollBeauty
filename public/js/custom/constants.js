
const PUBLIC_IMAGES = '/public/images/';
const CONST = {
    closeIcon: PUBLIC_IMAGES + 'custom/system/icons/close.svg',
    gifLoader: PUBLIC_IMAGES + 'mockup/pre-loader-1.gif',
    arrowDown: PUBLIC_IMAGES + 'custom/system/icons/arrows_down.svg',
    arrowUp: PUBLIC_IMAGES + 'custom/system/icons/arrows_up.svg',

    workerSystemProfile: '/web/admin/worker/profile',

    adminImgFolder: PUBLIC_IMAGES + 'custom/uploads/admins',
    workerImgFolder: PUBLIC_IMAGES + 'custom/uploads/workers/worker_',
    userImgFolder: PUBLIC_IMAGES + 'custom/uploads/users/user_',

    noPhoto: PUBLIC_IMAGES + 'custom/system/nophoto.jpg',

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
        Instagram: /^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9_]+\/?(?:\?[\w=&]+)?$/,
        Facebook:  /^(https?:\/\/)?(www\.)?facebook\.com\/(profile\.php\?id=)?[0-9]{1,}$/,
        TikTok: /^(https?:\/\/)?(www\.)?tiktok\.com\/@[\w.-]+(\?_t=.+)?$/,
        YouTube: /^(https?:\/\/)?(www\.)?youtube\.com\/@[\w.-]+\?si=[a-zA-Z0-9_-]+$/,
        LinkedIn: /^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9_-]+(\?.*)?$/,
        Github: /^(https?:\/\/)?(www\.)?github\.com\/[a-zA-Z0-9-]+$/,
        Telegram:  /^(https?:\/\/)?(www\.)?t\.me\/[a-zA-Z0-9_]+$/
    }
}
export default CONST;