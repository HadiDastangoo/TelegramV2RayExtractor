<?php
// error handling
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'errors.log');

// default parameters
define('TIME_ZONE',				'Asia/Tehran');
define('SUB_DIR',				'subscriptions/');
define('CHANNELS_DIR',			'channels/');
define('CHANNELS_ASSETS_DIR',	'channels/assets/');
define('CRON_LOG_ENABLED',		false);
define('CRON_LOG_FILE',			'log.txt');
