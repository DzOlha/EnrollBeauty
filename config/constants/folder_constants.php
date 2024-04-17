<?php
//str_replace('\\', '/', dirname(__FILE__, 3));
define('ROOT', dirname(__FILE__, 3));

const SRC = ROOT . '/src';
const CONFIG = ROOT . '/config';
const ADMIN_DEFAULT_CREDENTIALS = CONFIG.'/credentials/default_admin_credentials.php';
const LOGS_FOLDER = ROOT . '/logs';

const VIEW_PROTECTED_PAGES = 'public/custom/view/protected/pages';
const VIEW_PROTECTED_BLOCKS = 'public/custom/view/protected/blocks';
const VIEW_OPEN_PAGES = 'public/custom/view/open/pages';
const VIEW_OPEN_BLOCKS = 'public/custom/view/open/blocks';

const CUSTOM_ASSETS = 'public/custom/assets';

const MOCKUP_OPEN_FOLDER = 'public/mockups/open';
const MOCKUP_PROTECTED_FOLDER = 'public/mockups/protected';

const FOLDERS = [
    'public' => [
        'custom' => [
            'assets' => 'public/custom/assets',
            'view' => [
                'open' => 'public/custom/view/open',
                'protected' => 'public/custom/view/protected'
            ]
        ],
        'mockups' => [
            'open' => [
                'assets' => 'public/mockups/open/assets'
            ],
            'protected' => [
                'assets' => 'public/mockups/protected/assets'
            ]
        ]
    ]
];

const VALID_TIME_RECOVERY_CODE = 3600*30; //in seconds 30 hours

/**
 * Mailing service constants
 */
const EMAIL_WITH_LINK = SRC.'/Service/Sender/impl/email/templates/html/email_with_link.html';

const ARROW_DOWN = '/'.CUSTOM_ASSETS. '/img/system/icons/arrows_down.svg';
const NO_PHOTO = '/'.CUSTOM_ASSETS . '/img/system/nophoto.jpg';
const UPLOADS_FOLDER = '/'.CUSTOM_ASSETS . '/img/uploads/';
const WORKERS_PHOTO_FOLDER = UPLOADS_FOLDER . 'workers/';
const ADMINS_PHOTO_FOLDER = UPLOADS_FOLDER . 'admins/';
const USERS_PHOTO_FOLDER = UPLOADS_FOLDER . 'users/';


const TEMP_EMAIL_IMAGES_UPLOAD_FOLDER = '/public/custom/assets/img/uploads/tmp_email_images/';

const WORKER_SOCIAL_NETWORKS_ROOT_URLS = [
    'Instagram' => 'https://www.instagram.com/',
    'Facebook' => 'https://www.facebook.com/',
    'TikTok' => 'https://www.tiktok.com/',
    'YouTube' => 'https://youtube.com/',
    'LinkedIn' => 'https://www.linkedin.com/',
    'Github' => 'https://github.com/',
    'Telegram' => 'https://t.me/'
];



