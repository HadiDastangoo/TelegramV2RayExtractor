<?php
function compare_time( $a, $b )
{
	$a_time = strtotime( $a->time );
	$b_time = strtotime( $b->time );

	if ( $a_time == $b_time )
		return 0;

	return ($a_time > $b_time) ? -1 : 1;
}

function convert_to_timezone( $utc_timestamp, $timezone = 'Asia/Tehran' )
{
	$utc_datetime = new DateTime( $utc_timestamp );
	$utc_datetime->setTimezone(new DateTimeZone( $timezone ));
	return $utc_datetime->format("Y-m-d H:i:s");
}

function create_config_name( $channel, $flag, $ping, $type = null )
{
    $subscripts = array('₀', '₁', '₂', '₃', '₄', '₅', '₆', '₇', '₈', '₉');
    
    // create unique id from date/time & 3digits random number & ping
    $id = str_replace( range(0, 9), $subscripts, date("Y₋m₋d H₋i₋s") . rand( 100, 999) . "₋$ping");

    // flag
    $flag = is_null($flag) ? "🚩" : $flag;

    // final config name
    $config_name = $flag . " @$channel "  . ( $type === 'reality' ? ' • Reality' : '' ) . " • $id";
    return $config_name;
}

function get_config($channel, $type)
{
    $final_data = [];
    $get = file_get_contents("https://t.me/s/" . $channel);
    $channels_assets = json_decode(file_get_contents("modules/channels/channels_assets.json"), true);

    switch( $type )
    {
        case 'vmess':
            preg_match_all(
                '/vmess:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
                $get,
                $matches
            );
            $pattern_config = "#vmess://(.*?)<#";
            break;

        case 'vless':
            preg_match_all(
                '/vless:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
                $get,
                $matches
            );
            $pattern_config = "#vless://(.*?)<#";
            break;

        case 'trojan':
            preg_match_all(
                '/trojan:\/\/[^"]+(?:[^<]+<[^<]+)*<time datetime="([^"]+)"/',
                $get,
                $matches
            );
            $pattern_config = "#trojan://(.*?)<#";
            break;
            
        default:
            return false;
    }

    preg_match_all($pattern_config, $get, $configs);
    $key_limit = count($configs[1]) - 3;

    foreach ( $configs[1] as $key => $config )
    {
        if ( $key > $key_limit )
        {
            if ( !stripos($config, "...") )
            {
                if ( strpos($config, "<br/>") )
                {
                    $config = substr( $config, 0, strpos($config, "<br/>") );
                }

                if ( $type == 'vmess' )
                {
                    $the_config = decode_vmess( $type . "://" . $config );
                    $ip = !empty($the_config["sni"])
                        ? $the_config["sni"]
                        : ( !empty($the_config["host"]) ? $the_config["host"] : $the_config["add"] );
                }
                else
                {
                    $the_config = parse_proxy_url( $type . "://" . $config, $type );
                    $ip = !empty($the_config["params"]["sni"])
                        ? $the_config["params"]["sni"]
                        : ( !empty($the_config["params"]["host"]) ? $the_config["params"]["host"] : $the_config["hostname"]);
                    
                    // change type if it is Reality
                    $type = stripos($config, "reality") ? "reality" : $type;
                }
                $port = $the_config["port"];

                // check ping base on IP & port
                $ping = ping( $ip, $port );

                if ( $ping )
                {
                    $ip_info = ip_info( $ip );
                    $flag = isset( $ip_info["country"] ) ? get_flag( $ip_info["country"] ) : null;
                    
                    if ( $type == 'vmess' )
                    {
                        $the_config["ps"] = create_config_name( $channel, $flag, $ping, $type );
                        $final_config = encode_vmess($the_config);

                    }
                    else
                    {
                        $the_config["hash"] = create_config_name( $channel, $flag, $ping, $type );
                        $final_config = build_proxy_url($the_config, $type);
                    }
                    
                    $final_data[$key]["channel"]['username'] = $channel;
                    $final_data[$key]["channel"]['title'] = $channels_assets[$channel]['title'];
                    $final_data[$key]["channel"]['logo'] = $channels_assets[$channel]['logo'];
                    $final_data[$key]["type"] = $type;
                    $final_data[$key]["config"] = ($type == 'vmess') ? $final_config : urldecode($final_config);
                    $final_data[$key]["time"] = @convert_to_iran_time( $matches[1][$key] );
                }
            }
        }
    }
    
    return $final_data;
}

function get_flag( $country_code )
{
    $country_list = array(
        "AF" => "Afghanistan",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "BQ" => "British Antarctic Territory",
        "IO" => "British Indian Ocean Territory",
        "VG" => "British Virgin Islands",
        "BN" => "Brunei",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CT" => "Canton and Enderbury Islands",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos [Keeling] Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo - Brazzaville",
        "CD" => "Congo - Kinshasa",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "CI" => "Côte d’Ivoire",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "NQ" => "Dronning Maud Land",
        "DD" => "East Germany",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "FQ" => "French Southern and Antarctic Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GG" => "Guernsey",
        "GN" => "Guinea",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and McDonald Islands",
        "HN" => "Honduras",
        "HK" => "Hong Kong SAR China",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IM" => "Isle of Man",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JE" => "Jersey",
        "JT" => "Johnston Island",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Laos",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macau SAR China",
        "MK" => "Macedonia",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "FX" => "Metropolitan France",
        "MX" => "Mexico",
        "FM" => "Micronesia",
        "MI" => "Midway Islands",
        "MD" => "Moldova",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "ME" => "Montenegro",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar [Burma]",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NT" => "Neutral Zone",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "KP" => "North Korea",
        "VD" => "North Vietnam",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PC" => "Pacific Islands Trust Territory",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territories",
        "PA" => "Panama",
        "PZ" => "Panama Canal Zone",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "YD" => "People's Democratic Republic of Yemen",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn Islands",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RO" => "Romania",
        "RU" => "Russia",
        "RW" => "Rwanda",
        "RE" => "Réunion",
        "BL" => "Saint Barthélemy",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "MF" => "Saint Martin",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and the Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "RS" => "Serbia",
        "CS" => "Serbia and Montenegro",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and the South Sandwich Islands",
        "KR" => "South Korea",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syria",
        "ST" => "São Tomé and Príncipe",
        "TW" => "Taiwan",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania",
        "TH" => "Thailand",
        "TL" => "Timor-Leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UM" => "U.S. Minor Outlying Islands",
        "PU" => "U.S. Miscellaneous Pacific Islands",
        "VI" => "U.S. Virgin Islands",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "SU" => "Union of Soviet Socialist Republics",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "ZZ" => "Unknown or Invalid Region",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VA" => "Vatican City",
        "VE" => "Venezuela",
        "VN" => "Vietnam",
        "WK" => "Wake Island",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
        "AX" => "Åland Islands",
    );
    
    if ( !array_key_exists( strtoupper($country_code), $country_list) )
        return "🚩";

    return (string) preg_replace_callback(
        '/./',
        static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5),
        $country_code
    );
}

function get_reality($input)
{
    $array = explode("\n", $input);
    $output = "";
    foreach ( $array as $item )
    {
        if ( stripos($item, "reality") )
        {
            $output .= $output === "" ? $item : "\n$item";
        }
    }
    return $output;
}

function is_ip( $string )
{
    return filter_var($string, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

function ip_info( $ip )
{
    if ( is_ip($ip) === false )
    {
        $ip_address_array = dns_get_record($ip, DNS_A);
        $ip = $ip_address_array[0]['ip'];
    }

	// change ip request to "curl"
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.country.is/$ip",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    
    $ipinfo = json_decode($response, true);
    
    if ( isset($ipinfo['error']) )
    {
        $ipinfo['ip'] = '127.0.0.1';
        $ipinfo['country'] = 'Unknown';
    }
    
    return $ipinfo;
}

function ping($ip, $port)
{
    $it = microtime(true);
    if ( is_numeric($port) )
    {
        try {
            $check = @fsockopen($ip, $port, $errno, $errstr, 1);
        } 
        catch (Exception $e) {
            return false;
        }
    }
    $ft = microtime(true);
    $militime = intval(($ft - $it) * 1e3, 2);
    return $militime;
}

function decode_vmess($vmess_config)
{
    $vmess_data = substr($vmess_config, 8); // remove "vmess://"
    $decoded_data = json_decode(base64_decode($vmess_data), true);
    return $decoded_data;
}

function encode_vmess($config)
{
    $encoded_data = base64_encode(json_encode($config));
    $vmess_config = "vmess://" . $encoded_data;
    return $vmess_config;
}

function remove_duplicate_vmess($input)
{
    $array = explode("\n", $input);
    foreach ($array as $item) {
        $parts = decode_vmess($item);
        $part_ps = $parts["ps"];
        unset($parts["ps"]);
        if (count($parts) >= 3) {
            ksort($parts);
            $part_serialize = serialize($parts);
            $result[$part_serialize][] = $part_ps ?? "";
        }
    }
    $finalResult = [];
    foreach ($result as $serial => $ps) {
        $partAfterHash = $ps[0] ?? "";
        $part_serialize = unserialize($serial);
        $part_serialize["ps"] = $partAfterHash;
        $finalResult[] = encode_vmess($part_serialize);
    }
    $output = "";
    foreach ($finalResult as $config) {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}

function parse_proxy_url($url, $type = "trojan")
{
    // Parse the URL into components
    $parsedUrl = parse_url($url);

    // Extract the parameters from the query string
    $params = [];
    if ( isset($parsedUrl["query"]) )
    {
        parse_str($parsedUrl["query"], $params);
    }

    // Construct the output object
    $output = [
        "protocol" => $type,
        "username" => isset($parsedUrl["user"]) ? $parsedUrl["user"] : "",
        "hostname" => isset($parsedUrl["host"]) ? $parsedUrl["host"] : "",
        "port" => isset($parsedUrl["port"]) ? $parsedUrl["port"]: "",
        "params" => $params,
        "hash" => isset($parsedUrl["fragment"]) ? $parsedUrl["fragment"] : "",
    ];

    return $output;
}

function build_proxy_url( $obj, $type = "trojan" )
{
    // Construct the base URL
    $url = $type . "://";

    if ( $obj["username"] !== "" )
    {
        $url .= $obj["username"];
        if ( isset($obj["pass"]) && $obj["pass"] !== "" )
        {
            $url .= ":" . $obj["pass"];
        }
        $url .= "@";
    }
    $url .= $obj["hostname"];
    
    if ( isset($obj["port"]) && $obj["port"] !== "" )
    {
        $url .= ":" . $obj["port"];
    }

    // Add the query parameters
    if ( !empty($obj["params"]) )
    {
        $url .= "?" . http_build_query($obj["params"]);
    }

    // Add the fragment identifier
    if ( isset($obj["hash"]) && $obj["hash"] !== "" )
    {
        $url .= "#" . $obj["hash"];
    }

    return $url;
}

function remove_duplicate_xray( $input, $type )
{
    if ( empty(trim( $input )) )
        return;

    $array = explode( "\n", $input );

    foreach ( $array as $item )
    {
        $parts = parse_proxy_url( $item, $type );
        $part_hash = $parts["hash"];
        unset($parts["hash"]);
        ksort($parts["params"]);
        $part_serialize = serialize( $parts );
        $result[$part_serialize][] = $part_hash ?? "";
    }

    $finalResult = [];
    foreach ( $result as $url => $parts )
    {
        $partAfterHash = $parts[0] ?? "";
        $part_serialize = unserialize( $url );
        $part_serialize["hash"] = $partAfterHash;
        $finalResult[] = build_proxy_url( $part_serialize, $type );
    }

    $output = "";
    foreach ( $finalResult as $config )
    {
        $output .= $output == "" ? $config : "\n" . $config;
    }
    return $output;
}
