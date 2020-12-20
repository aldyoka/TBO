<?php 

function read_file($path){
    $file = fopen($path,"r");
    $container = [];
    while ( $line = fgets($file) ){
        $container[] =  trim(strtolower($line));
    }
    fclose($file);
    return array_unique($container);
}

function get_rules($path){
    $clean_rules = [];
    $file = fopen($path,"r");
	$leksikon = ['BdLeksikon', 'SfLeksikon', 'BilLeksikon', 'GtLeksikon', 'KjLeksikon', 'PnLeksikon', 'PsLeksikon'];
    while( $rule = fgets($file) ){
        $new_rule = explode("->", $rule);
        $nonTerminal = trim($new_rule[0]);
        $rhs = trim($new_rule[1]);
        if( in_array($rhs, $leksikon) ){
            $rhs = read_file("./rule/" . $rhs . ".txt");
            $clean_rules[$nonTerminal] =  $rhs;
        }else{
            $clean_rules[$nonTerminal][] =  $rhs;
        }
    }
    return $clean_rules;
}

function part_of_speech($rules, $value){

    $arr = [];
    foreach($rules as $nonTerminal => $rhs){
        if( in_array($value, $rhs) ){
            $arr[] = $nonTerminal;
        }
    }
    return $arr;
}

function combine($left, $right){
    $left = explode(" ", $left);
    $right = explode(" ", $right);

    $new = [];
    foreach( $left as $l ){
        foreach( $right as $r ){
            $new[] = $l . " " . $r;
        }
    }

    return $new;
}

function get_combinations($arrays) {
	$result = array(array());
	foreach ($arrays as $property => $property_values) {
		$tmp = array();
		foreach ($result as $result_item) {
			foreach ($property_values as $property_value) {
				$tmp[] = array_merge($result_item, array($property => $property_value));
			}
		}
		$result = $tmp;
	}
	return $result;
}

