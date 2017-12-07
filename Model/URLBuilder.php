<?php

namespace ArchivesOnlineSGV;

/**
 * Class Model_URLBuilder This class build the URL for the extended search request to the salsah API.
 * @package ArchivesOnlineSGV
 */
class Model_URLBuilder {
    /**
     * @var string First part of the search API URL.
     */
    const SEARCH_EXTENDED = "http://www.salsah.org/api/search/?searchtype=extended";
    /**
     * @var string Property parameter for the title.
     */
    const PROPERTY_TITLE = "&property_id[]=1";
    /**
     * @var string Property parameter for the date.
     */
    const PROPERTY_DATE = "&property_id[]=46";
    /**
     * @var string Compare parameter for exist.
     */
    const COMPARE_EXIST = "&compop[]=EXISTS";
    /**
     * @var string Compare parameter for equal.
     */
    const COMPARE_EQUAL = "&compop[]=EQ";
    /**
     * @var string Compare parameter for match.
     */
    const COMPARE_MATCH = "&compop[]=MATCH";
    /**
     * @var string Value parameter without value.
     */
    const VALUE = "&searchval[]=";
    /**
     * @var string Row number parameter without value.
     */
    const ROW_NUMBER = "&show_nrows=";
    /**
     * @var string Filter parameter for only SVG projects.
     */
    const FILTER_IMAGE = "&filter_by_restype=65";

    /**
     * Builds the URL for the request to the salsah API. The search is an extended search which will send the search words, period, the numbers of results and the conjunction information.
     * @param array $words The search words
     * @param int $number The number of results which the requester wants from the salsah API.
     * @param Model_Period $period The time period where the results fit in.
     * @param bool $isAND Contains the information of the conjunction. True means "AND" where false means "OR.
     * @return string Contains the URL with all the information needed to start the request.
     */
    public function getSearchURL( $words, $number, $period, $isAND) {
        $url = static::SEARCH_EXTENDED;
        $val_gregorian = static::VALUE . "GREGORIAN:" . $period->getFromYear(). ":". $period->getToYear();

        if ($isAND && count($words) >= 2) {
            $property = static::PROPERTY_DATE;
            $compare = static::COMPARE_EQUAL . static::COMPARE_EXIST;
            $value = $val_gregorian . static::VALUE;

            for($i = 0; $i<count($words); $i++) {
                $property = $property . static::PROPERTY_TITLE;
                $compare = $compare . static::COMPARE_MATCH;
                $value = $value . static::VALUE . \urlencode($words[$i]);
            }

            $property = $property . static::PROPERTY_TITLE;
            $url = $url . $property . $compare . $value . static::ROW_NUMBER . $number .static::FILTER_IMAGE;
        } else {
            $searchWords = $words[0];
            for($i = 1; $i< count($words); $i++) {
                $searchWords = $searchWords . "+" . $words[$i];
            }
            $url = $url . static::PROPERTY_DATE . static::PROPERTY_TITLE . static::PROPERTY_TITLE . static::COMPARE_EQUAL . static::COMPARE_EXIST . static::COMPARE_MATCH
                . $val_gregorian . static::VALUE . static::VALUE . \urlencode($searchWords) . static::ROW_NUMBER . $number .static::FILTER_IMAGE;
        }

        return $url;
    }
}
