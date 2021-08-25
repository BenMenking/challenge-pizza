<?php

$start = microtime(true);

$data = json_decode(file_get_contents('http://files.olo.com/pizzas.json'), true);

$popular = [];
$clutch = 0;

foreach($data as $pizza) {
    // sort toppings alphabetically, we can't assume they are in the same order each time
    $hash1 = sha1(implode(',', $pizza['toppings']));
    asort($pizza['toppings']);
    $hash2 = sha1(implode(',', $pizza['toppings']));

    if( $hash1 !== $hash2 ) $clutch++;

    // create a hash we'll use to aggregate the pizza combinations
    $hash = implode(', ', $pizza['toppings']);

    // initialize our associative array key if it doesn't exist
    if( !isset($popular[$hash]) ) {
        $popular[$hash] = 0;
    }
    
    $popular[$hash]++;
}

// reverse-sort, maintaining key association
arsort($popular);

echo "Took " . (microtime(true) - $start) . " seconds\n";
// puke out result of top 20
//print_r(array_slice($popular, 0, 20));
    // make it pretty
foreach(array_slice($popular, 0, 20) as $toppings=>$count) {
    echo "$toppings" . str_pad(' ', 35 - strlen($toppings)) . number_format($count, 0) . "\n";
}

echo "There were $clutch anomalies\n";