<?php

function get_relay_ip(): array
{

    $for_gui = array();

    $input_ip = array(array("key" => "1020", "value" => '192.168.1.1'),
        array("key" => "1020", "value" => '192.168.1.0'),
        array("key" => "1020", "value" => '192.168.1.2'),
        array("key" => "1020", "value" => '192.168.1.3'),
        array("key" => "1022", "value" => '192.168.1.2'),
        array("key" => "1021", "value" => '192.168.1.2'),
        array("key" => "1021", "value" => '192.168.1.1'));
    $output_ip = array();

    foreach ($input_ip as $item) {
        if (array_key_exists($item['key'], $output_ip)) {

            $value_list = $output_ip[$item['key']];
            $value_list[] = $item['value'];
        } else {
            $value_list = array($item['value']);
        }
        $output_ip[$item['key']] = $value_list;
    }

    foreach ($output_ip as $range_key => $range_ip_list) {
        sort($range_ip_list);
        $cidr = get_cidr(sizeof($range_ip_list));
        $ip_address_cidr = $range_ip_list[0] . '/' . $cidr;
        if (empty(array_diff(get_all_ip(ipRange($ip_address_cidr)[0], ipRange($ip_address_cidr)[1]), $range_ip_list))) {
            echo "This is ip relay " . $ip_address_cidr . ' ';
            $for_gui[] = array("key" => $range_key, "value" => $ip_address_cidr);
        }

    }

    return $for_gui;

}

//print_r($output_ip);
function cidr_match($ip, $cidr): bool
{
    list($subnet, $mask) = explode('/', $cidr);

    if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
        return true;
    }

    return false;
}

function cidr_ip($cidr): array
{
    $ip_arr = explode('/', $cidr);

    return array('Test');
}

function ipRange($cidr): array
{
    $range = array();
    $cidr = explode('/', $cidr);
    $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
    $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
    return $range;
}

function get_all_ip($start_ip, $end_ip): array
{
    $return_list = array();
    $start_ip = ip2long($start_ip);
    $end_ip = ip2long($end_ip);
    while ($start_ip <= $end_ip) {

        $return_list[] = long2ip($start_ip);
        $start_ip++;

    }
    return $return_list;
}

function get_cidr($num_of_ip): string
{
    $cidr_list = array(1 => "32", 2 => "31", 4 => "30", 8 => "29", 16 => "28", 32 => "27",
        64 => "26", 128 => "25", 256 => "24", 512 => "23", 1024 => "22");

    return $cidr_list[$num_of_ip];

}

//print_r(cidr_match("192.168.0.15", "192.168.1.0/24" ));
$cidr = "192.168.1.0/29";

//print_r(get_all_ip(ipRange($cidr)[0], ipRange($cidr)[1]));
//print_r(array_diff(get_all_ip(ipRange($cidr)[0], ipRange($cidr)[1]), $output_ip));

print_r(get_relay_ip());