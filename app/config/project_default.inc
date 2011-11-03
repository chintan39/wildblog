<?php

// debugging info 0 = off, 1 = on
Config::Set('DEBUG_MODE', 0);
Config::Set('PROJECT_STATUS', PROJECT_READY); // PROJECT_DB_NEED_CHECK, PROJECT_READY

// debugging info 0 = off, 1 = on
Config::Set('PROJECT_TITLE', 'Default Project Title');
Config::Set('PROJECT_DESCRIPTION', '');

// project config
Config::Set('PROJECT_URL', '');
Config::Set('PROJECT_USE_HTTPS_FRONT_END', 0);
Config::Set('PROJECT_USE_HTTPS_BACK_END', 0);

// default visual temes
Config::Set('THEME_FRONT_END', 'Default');
Config::Set('THEME_BACK_END', 'DefaultAdmin');

// default action in format Package::Controller::action
Config::Set('HOMEPAGE_ACTION', 'Basic::Articles::actionHomepageArticle');
Config::Set('HOMEPAGE_ACTION_ADMIN', 'auto|Basic::Articles::actionListing'); 

// default email to send emails to or from
Config::Set('DEFAULT_EMAIL', 'horak.jan@centrum.cz', 'Default e-mail address');

// default Language
Config::Set('DEFAULT_LANGUAGE', 'cs');
Config::Set('DEFAULT_LANGUAGE_BACK_END', 'cs');

// project specific default paging limit
Config::Set('DEFAULT_PROJECT_PAGING_LIMIT', DEFAULT_PAGING_LIMIT);

// Cache settings
Config::Set('ALLOW_CACHE', false);

// path to image on error page
Config::Set('ERROR_IMAGE_PATH', 'app/error_page.jpg');

// true if selectors should be initialized immediately in the HTML
// false if selectors should be initialized after page is loaded
Config::Set('SELECTOR_IMMEDIATELY', true);

// true if tabs should be initialized immediately in the HTML
// false if tabs should be initialized after page is loaded
Config::Set('TABS_IMMEDIATELY', true);

// how many pages should be displayed in paging list
Config::Set('PAGING_LIST_COUNT', 6);

// timeouts
Config::Set('SESSION_TIMEOUT', 3*60*60, 'Session timeout', Config::INT, true);
Config::Set('EDIT_TIMEOUT_WARNING', (3*60-10)*60, 'Edit timeout warning', Config::INT, true);

// captcha timer use a key to cypher timestamp
Config::Set('CAPTCHA_TIMER_KEY', '__secret_key__', 'Key to crypt timestamp in form to protect form from robots', Config::STRING, true);
Config::Set('CAPTCHA_TIMER_LIMIT', 60, 'Form is considered invalid, if number of seconds spent by filling it was less then this value', Config::INT, true);

?>
