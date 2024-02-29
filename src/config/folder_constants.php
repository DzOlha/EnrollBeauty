<?php
//str_replace('\\', '/', dirname(__FILE__, 3))
define('ENROLL_BEAUTY_ROOT', dirname(__FILE__, 3));
const SRC = ENROLL_BEAUTY_ROOT . '/src';
const TEST = ENROLL_BEAUTY_ROOT . '/test';
const PUBLIC_ = '../../public';

const VIEW_FRONTEND = SRC . '/view/frontend/';
const VIEW_ADMIN = SRC . '/view/adminhtml/';
const VIEW_COMMON = SRC . '/view/common/';

const CSS_FRONTEND = PUBLIC_ . '/css/frontend/';
const CSS_ADMIN = PUBLIC_ . '/css/adminhtml/';
const CSS_COMMON = PUBLIC_ . '/css/common/';

const JS_FRONTEND = PUBLIC_ . '/js/frontend/';
const JS_ADMIN = PUBLIC_ . '/js/adminhtml/';
const JS_COMMON = PUBLIC_ . '/js/common/';

const PHOTO_FRONTEND = PUBLIC_ . '/photo/frontend/';
const PHOTO_ADMIN = PUBLIC_ . '/photo/adminhtml/';
const PHOTO_COMMON = PUBLIC_ . '/photo/common/';
const PUBLIC_PHOTO_COMMON_DB_BOOKS_FOLDER = PHOTO_COMMON.'db/books/';

const VALID_TIME_RECOVERY_CODE = 3600*30; //in seconds

/**
 * Mailing service constants
 */
const EMAIL_WITH_LINK = SRC.'/Service/Sender/impl/email/templates/email_with_link.html';
const COMPANY_NAME = 'Enroll Beauty';
const COMPANY_EMAIL = 'enroll@beauty.com';

const ARROW_DOWN = '/public/images/custom/system/icons/arrows_down.svg';
const NO_PHOTO = '/public/images/custom/system/nophoto.jpg';

const TEMP_EMAIL_IMAGES_UPLOAD_FOLDER = '/public/images/custom/uploads/tmp_email_images/';

const UPLOADS_FOLDER = '/public/images/custom/uploads/';
const WORKERS_PHOTO_FOLDER = UPLOADS_FOLDER . 'workers/';
const ADMINS_PHOTO_FOLDER = UPLOADS_FOLDER . 'admins/';
const USERS_PHOTO_FOLDER = UPLOADS_FOLDER . 'users/';


