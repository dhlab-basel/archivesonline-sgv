<?php

namespace ArchivesOnlineSGV;

/**
 * Class Controller_Api
 * @package ArchivesOnlineSGV
 */
class Controller_Api {

    /**
     * Controller_Api constructor.
     */
    function __construct() {
        \header("Content-type: text/xml");

        $p = isset($_GET["p"]) ? "/" . $_GET["p"] : "";

        switch ($p) {
            /* If you deploy this to the server use {1} instead of {2}
                {1} = case "";
                {2} = case "/sgv/";
            */
            case "/sgv/":
                $this->sgv();
                break;
            default:
                $this->notImplemented();
                break;
        }
    }

    /**
     * Checks the query, instantiates all the necessary classes, initiates the salsah request and delegates the creation of the XML.
     */
    function sgv() {
        $query = isset($_GET["query"]) ? $_GET["query"] : "";
        $maxRecords = isset($_GET["maximumRecords"]) ? \intval($_GET["maximumRecords"]) : Config::MAX_SEARCH_RESULTS;

        $requestParams = Model_RequestParams::fromArchivesOnlineRequest($query, $maxRecords);

        $urlBuilder = new Model_URLBuilder();

        $requester = new Model_Requester($urlBuilder, $requestParams->getMaxRecords());

        $xmlBuilder = new Model_XMLBuilder($requestParams->getMaxRecords());

        $results = $requester->httpGet($requestParams->getSearch(), $requestParams->isAND(), $requestParams->getPeriod());

        echo $xmlBuilder->createXML($results);
    }

    /**
     * Will be shown in case the URL is not implemented.
     */
    function notImplemented() {
        echo "<xml><error>Not implemented exception</error></xml>";
    }

}

?>