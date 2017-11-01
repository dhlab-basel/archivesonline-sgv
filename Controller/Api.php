<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

class Controller_Api {

    function __construct() {

        \header("Content-type: text/xml");

        $p = isset($_GET["p"]) ? "/" . $_GET["p"] : "";

        switch ($p) {
            case "/sgv/":
                $this->sgv();
                break;
            default:
                $this->notImplemented();
                break;

        }

    }

    function sgv() {

        $query = isset($_GET["query"]) ? $_GET["query"] : "";
        $maxRecords = isset($_GET["maximumRecords"]) ? \intval($_GET["maximumRecords"]) : Config::MAX_SEARCH_RESULTS;

        $requestParams = Model_RequestParams::fromArchivesOnlineRequest($query, $maxRecords);

        $urlBuilder = new Model_URLBuilder();
        $requester = new Model_Requester($urlBuilder, $requestParams->getMaxRecords());
        $xmlBuilder = new Model_XMLBuilder($requestParams->getMaxRecords());


        $results = $requester->httpGet($requestParams->getSearch(), $requestParams->getPeriod());

        echo $xmlBuilder->createXML($results);

    }

    function notImplemented() {
        echo "<xml><error>Not implemented exception</error></xml>";
    }

}

?>