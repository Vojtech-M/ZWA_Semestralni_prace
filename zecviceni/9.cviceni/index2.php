<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php echo "Dnešní datum je: ". date("j.n.Y");
?>
<br>
<?php
    $date = "12.6.2008";
    $dateArray =  explode(".",$date);
    $day = $dateArray[0];
    list($den,$mesic,$rok) =  explode(".",$date);
    echo "$day";
    $timestamp = mktime(0,0,0,$mesic, $den,$rok);

    //var_dump($timestamp);
    //var_dum($den);
    ?>
    <br>
    <?php
    echo "timestamp je: ".$timestamp;
    echo "{$date} je". date("l",$timestamp)
    ?>
<hr>

<?php
$datum = "12.6.2003";
function dateToDay($datum){
  //  list($den, $mesic, $rok) = explode(".",$date);
    $timestamp = mktime(0,0,0,$mesic, $den,$rok);
    return "{$datum} je". date("l",$timestamp);
}
//echo dateToDay("5.3.2000")
?>
<hr>
<?php   
$data = ["12.6.2008","1.1.1998","2.2.2500"];

    foreach ($data as $datum){
        echo $datum;
    }
?>
<?php 

$data = ["12.6.2008","1.1.1998","2.2.2500"];
function getMonths($data){
    $mesice = [];
    foreach ($data as $datum){

        // in array()
        // funkce muze mit nepovinne parametry, jako treba $min = null / to bude vychozi hodnota
        $month = explode(".",$datum);
        $mesice[] = (int)$month;
    }

return $mesice;
}
var_dump(getMonths($data))
?>

<hr>

<?php






?>






    
</body>
</html>