<?php
// all output to json
header("Content-type: application/json;");

// change timezone to Iran Standard Timezone
date_default_timezone_set("Asia/Tehran");

// error handling
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log');

// defaults
define('SUB_DIR',   'sub/');

require("modules/get_data.php");
require("modules/channels.php");

foreach ( $channels as $channel => $types )
{
    for ( $type_count = 0; $type_count < count($types); $type_count++ )
    {
        $mix[$types[$type_count]][] = @get_config($channel, $types[$type_count]);
    }
}

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

// mix
$mix_array = array_merge( $vmess_array, $vless_array, $trojan_array );


// create config data plaintext
$vmess = implode("\n", $vmess_array);
$vless = implode("\n", $vless_array);
$trojan = implode("\n", $trojan_array);

// remove duplicates
$fixed_vmess = remove_duplicate_vmess( $vmess );
$fixed_vless = remove_duplicate_xray( str_replace("&amp;", "&", $vless), "vless" );
$fixed_reality = get_reality( $fixed_vless );
$fixed_trojan = remove_duplicate_xray( str_replace("&amp;", "&", $trojan), "trojan");
$fixed_mix = "$fixed_vmess\n$fixed_vless\n$fixed_trojan";

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


// ******************** Output Statistics ********************
function line( $sep = '-', $mult = 50 )
{
    echo "\n" . str_repeat( $sep, $mult) . "\n";
}
echo line();
echo "VMess:\t" . count($vmess_array);
echo line();
echo "VLESS:\t" . count($vless_array) . "\t(Reality: " . count( explode("\n", $fixed_reality) ) . ")";
echo line();
echo "Trojan:\t" . count($trojan_array);
echo line('=');
echo "Sum:\t" . count($mix_array);
echo line();
echo "Total:\t" . count(explode("\n", $fixed_mix)) . "\t(" . ( count($mix_array) - count(explode("\n", $fixed_mix)) ) . " duplicates merged)";
echo line('=');
echo "\n";
// print_r( $fixed_mix );
// ************************************************************
