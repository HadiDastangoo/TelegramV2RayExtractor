<?php
// all output to json
header("Content-type: application/json;");

// stat for cron Log file
$startProcess = date('Y-m-d H:i:s');

// include main config file & modules
include 'config.php';
include 'functions.php';
include CHANNELS_DIR . 'channels.php';

// set timezone
date_default_timezone_set(TIME_ZONE);

// check channels directory
if ( !file_exists( CHANNELS_DIR ) )
    mkdir( CHANNELS_DIR ) or die('Error making directory (Line: ' . __LINE__ . ')');

// define all allowed protocol types
$all_types = ['vmess', 'vless', 'trojan'];


// ******************** <Extraction> ********************
// extract configs
foreach ( $channels as $channel => $types )
{
    for ( $type_count = 0; $type_count < count($types); $type_count++ )
    {
        if ( in_array( $types[$type_count], $all_types ) )
            $mix[$types[$type_count]][] = @get_config($channel, $types[$type_count]);
    }
}
// ******************** </Extraction> ********************


// get protocol details array
$vmess_data = $mix['vmess'];
$vless_data = $mix['vless'];
$trojan_data = $mix['trojan'];

// vmess
foreach ( $vmess_data as $object )
{
    if ( is_array( $object ) )
    {
        foreach ( $object as $details )
        {
            $vmess_array[] = $details['config'];
        }
    }
}

// vless
foreach ( $vless_data as $object )
{
    if ( is_array( $object ) )
    {
        foreach ( $object as $details )
        {
            $vless_array[] = $details['config'];
        }
    }
}

// trojan
foreach ( $trojan_data as $object )
{
    if ( is_array( $object ) )
    {
        foreach ( $object as $details )
        {
            $trojan_array[] = $details['config'];
        }
    }
}

// mix all!
$mix_array = array_merge( $vmess_array, $vless_array, $trojan_array );


// prepare config data plaintext
$vmess = implode("\n", $vmess_array);
$vless = implode("\n", $vless_array);
$trojan = implode("\n", $trojan_array);

// remove duplicates
$fixed_vmess = remove_duplicate_vmess( $vmess );
$fixed_vless = remove_duplicate_xray( str_replace("&amp;", "&", $vless), "vless" );
$fixed_reality = get_reality( $fixed_vless );
$fixed_trojan = remove_duplicate_xray( str_replace("&amp;", "&", $trojan), "trojan");
$fixed_mix = "$fixed_vmess\n$fixed_vless\n$fixed_trojan";


// ******************** Create Subscription ********************

// check subscription directory
if ( !file_exists( SUB_DIR ) )
    mkdir( SUB_DIR ) or die('Error making directory! (' . __LINE__ . ')');

// normal version
file_put_contents( SUB_DIR . "vmess", $fixed_vmess);
file_put_contents( SUB_DIR . "vless", $fixed_vless);
file_put_contents( SUB_DIR . "reality", $fixed_reality);
file_put_contents( SUB_DIR . "trojan", $fixed_trojan);
file_put_contents( SUB_DIR . "mix", $fixed_mix);

// base64 version
file_put_contents( SUB_DIR . "vmess_base64", base64_encode($fixed_vmess));
file_put_contents( SUB_DIR . "vless_base64", base64_encode($fixed_vless));
file_put_contents( SUB_DIR . "reality_base64", base64_encode($fixed_reality));
file_put_contents( SUB_DIR . "trojan_base64", base64_encode($fixed_trojan));
file_put_contents( SUB_DIR . "mix_base64", base64_encode($fixed_mix));



// ******************** Update Channels Data ********************

$channel_array = [];

foreach ( $channels as $channel => $data_array )
{
    // get telegram channel contents
    $html = file_get_contents("https://t.me/s/" . $channel);
    
    // prepare channel properties' pattern
    $title_pattern = '#<meta property="twitter:title" content="(.*?)">#';
    $image_pattern = '#<meta property="twitter:image" content="(.*?)">#';
    
    // get channel property
    preg_match($image_pattern, $html , $image_match);
    preg_match($title_pattern, $html , $title_match);
    
    // put logo into channel directory and set channel property into array
    file_put_contents( CHANNELS_ASSETS_DIR . $channel . ".jpg", file_get_contents($image_match[1]));
    $channel_array[$channel]['types'] = $data_array;
    $channel_array[$channel]['title'] = $title_match[1];
    $channel_array[$channel]['logo'] = "https://raw.githubusercontent.com/yebekhe/TelegramV2rayCollector/main/modules/channels/" . $channel . ".jpg";
}

// update channels data into json
file_put_contents( CHANNELS_DIR . "channels.json", json_encode($channel_array , JSON_PRETTY_PRINT));



// ******************** Report ********************

echo "\n" . str_repeat('=', 50) . "\n";
echo "VMess:\t" . count($vmess_array);
echo "\n" . str_repeat('-', 50) . "\n";
echo "VLESS:\t" . count($vless_array) . "\t(Reality: " . count( explode("\n", $fixed_reality) ) . ")";
echo "\n" . str_repeat('-', 50) . "\n";
echo "Trojan:\t" . count($trojan_array);
echo "\n" . str_repeat('=', 50) . "\n";
echo "Sum:\t" . count($mix_array);
echo "\n" . str_repeat('-', 50) . "\n";
echo "Total:\t" . count(explode("\n", $fixed_mix)) . "\t(" . ( count($mix_array) - count(explode("\n", $fixed_mix)) ) . " duplicates merged)";
echo "\n" . str_repeat('=', 50) . "\n";
echo "\n";



// ******************** Log ********************

if ( CRON_LOG_ENABLED )
{
    $sapi_name = php_sapi_name();
    $statFile = fopen( CRON_LOG_FILE, "a+" ) or die('Error openning log file (line:' . __LINE__ . ')');
    $endProcess = date('Y-m-d H:i:s');
    $log = "$startProcess\t$endProcess\t" . count($mix_array) . "\t" . count(explode("\n", $fixed_mix)) . "\t$sapi_name\n";
    fwrite( $statFile, $log );
    fclose( $statFile );
}

?>
