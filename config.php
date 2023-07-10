<?php
// error handling
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'errors.log');

// set timezone
date_default_timezone_set("Asia/Tehran");

// default directories (with trailing '/')
define('SUB_DIR',               'sub/');
define('CHANNELS_DIR',          'channels/');
define('CHANNELS_ASSETS_DIR',   'channels/assets/');
define('CRON_LOG_FILE',          'log');
