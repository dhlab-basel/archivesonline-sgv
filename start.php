<?php
/**
 * Created by PhpStorm.
 * User: vijeinath
 * Date: 30.10.17
 * Time: 17:17
 */

declare(strict_types = 1);

include "Requester.php";
include "XMLBuilder.php";
include "URLBuilder.php";
include "Model/Period.php";

const SEARCH_WORD = "Kind";
const FROM_YEAR = 1000;
const TO_YEAR = 2017;
const MAX_RESULT = 25;

$urlBuilder = new URLBuilder();
$requester = new Requester($urlBuilder, MAX_RESULT);
$xmlBuilder = new XMLBuilder(20);
$period = new Period(FROM_YEAR, TO_YEAR);


$results = $requester->httpGet(SEARCH_WORD, $period);

$xmlBuilder->createXML($results);




// -------------- Shows the results in the browser------------
displayResults($results);

function displayResults(?array $results):void  {
    if ($results == null) {
        echo "Results has no data";
    } else {
        foreach ($results as $key => $value){
            echo "Resource " . ++$key;
            echo "<br>";
            echo "[ID] " . $value->getID();
            echo "<br>";
            echo "[Title] " . $value->getTitle();
            echo "<br>";
            echo "[Date Val 1] " . $value->dateValues->dateval1;
            echo "<br>";
            echo "[Date Val 2] " . $value->dateValues->dateval2;
            echo "<br>";
            echo "[Date Precision 1] " . $value->dateValues->dateprecision1;
            echo "<br>";
            echo "[Date Precision 2] " . $value->dateValues->dateprecision2;
            echo "<br>";
            echo $value->getDate();
            echo "<br><br>";
        }
    }
}
?>