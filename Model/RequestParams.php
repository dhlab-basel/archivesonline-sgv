<?php

namespace ArchivesOnlineSGV;

//include("Period.php");

/**
 * Class Model_RequestParams
 * @package ArchivesOnlineSGV
 */
class Model_RequestParams {

    /**
     * @var array
     */
    private $searchWords;

    /**
     * @var Model_Period|null
     */
    private $period;

    /**
     * @var int
     */
    private $maxRecords;

    /**
     * @var bool
     */
    private $isAND;

    /**
     * @return bool
     */
    public function isAND() {
        return $this->isAND;
    }

    /**
     * @param bool $isAND
     */
    public function setIsAND($isAND) {
        $this->isAND = $isAND;
    }

    /**
     * @return array searchWords
     */
    public function getSearch() {
        return $this->searchWords;
    }

    /**
     * @param array $searchWords
     */
    public function setSearch($searchWords) {
        $this->searchWords = $searchWords;
    }

    /**
     * @return Model_Period|null
     */
    public function getPeriod() {
        return $this->period;
    }

    /**
     * @param Model_Period|null $period
     */
    public function setPeriod($period) {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getMaxRecords() {
        return $this->maxRecords;
    }

    /**
     * @param int $maxRecords
     */
    public function setMaxRecords($maxRecords) {
        $this->maxRecords = $maxRecords >= Config::MIN_SEARCH_RESULTS && $maxRecords <= Config::MAX_SEARCH_RESULTS ? $maxRecords : Config::MAX_SEARCH_RESULTS;
    }

    /**
     * Model_RequestParams constructor.
     */
    private function __construct() {}

    /**
     * Generates an instance from the original request params.
     * @param string $query
     * @param int $maxRecords
     * @return Model_RequestParams
     * @throws \Exception in case the query is invalid.
     */
    public static function fromArchivesOnlineRequest($query, $maxRecords) {
        $requestParams = new Model_RequestParams();
        $queryArray = \explode("AND", $query);

        switch (\count($queryArray)) {
            case 1:
                $requestParams->setSearch(static::getStringArrayInQuotes($queryArray[0]));
                $requestParams->setIsAND(static::getConjunctionInfo($queryArray[0]));
                $requestParams->setPeriod(null);
                break;
            case 2:
                $requestParams->setSearch(static::getStringArrayInQuotes($queryArray[0]));
                $requestParams->setIsAND(static::getConjunctionInfo($queryArray[0]));
                $years = \explode(" ", static::getFirstStringInQuotes($queryArray[1]));
                if (\is_array($years) === false || \count($years) === 0) {
                    $requestParams->setPeriod(null);
                } else {
                    $fromYear = \intval($years[0]);
                    $toYear = isset($years[1]) ? \intval($years[1]) : $fromYear;
                    $requestParams->setPeriod(new Model_Period($fromYear, $toYear));
                }
                break;
            default:
                throw new \Exception("Invalid query string");
                break;
        }

        $requestParams->setMaxRecords($maxRecords);
        return $requestParams;
    }

    /**
     * @param string $string splits the string into arrays with no spaces
     * @return array
     */
    private static function getStringArrayInQuotes($string) {
        return \explode(" ", static::getFirstStringInQuotes($string));
    }

    /**
     * Gets the first appearance of a quoted string in a string.
     * @param string $string
     * @return string
     */
    private static function getFirstStringInQuotes($string) {
        $array = [];
        if (\preg_match_all("|\"(.*)\"|U", $string, $array) === false) return "";
        return isset($array[1][0]) ? $array[1][0] : "";
    }

    /**
     * Extracts the conjunction information of the query.
     * @param string $query
     * @return bool True means "AND" and false means "OR" conjunction.
     * @throws \Exception in case the query is invalid.
     */
    private static function getConjunctionInfo($query) {
        if (preg_match("|^Serverchoice all \".*\"|i", $query)) {
            return true;
        } else if (preg_match("|^Serverchoice any \".*\"|i", $query)) {
            return false;
        } else {
            throw new \Exception("Invalid query string (preg_match -> fail)");
        }
    }

}
