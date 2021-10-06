<?php
require './vendor/autoload.php';

$redis = new Predis\Client();
$cachedEntry = $redis->get('tx');
if($cachedEntry){
    //display the result from the cache 
    echo "From Redis Cache <br>";
    echo $cachedEntry;
    exit();
}
else{
    // connect with database, get data, display and cache it in Redis as well
    $conn = new mysqli('localhost:3306', 'root', '@Dcc2590792@', 'usertx');
    $sql = "select tx_number, to_address, from_address, amount from tx;";
    $result = $conn->query($sql);
    echo "From Database <br>";
    $temp = '';
    while($row = $result->fetch_assoc()){
        echo $row['tx_number'] . '<br>';
        echo $row['to_address']. '<br>';
        echo $row['from_address']. '<br>';
        echo $row['amount'];
        $temp .= $row['tx_number'] . '   ' . $row['to_address'] . '   ' . $row['from_address'] . '   ' . $row['amount'] .'<br>'; 
    }
    $redis->set('tx', $temp);
    exit();

}
?>