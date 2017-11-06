<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Model_RequestParams
 * @package ArchivesOnlineSGV
 */
class Model_RequestParams {

    /**
     * @var string
     */
    private $search;

    /**
     * @var Model_Period|null
     */
    private $period;

    /**
     * @var int
     */
    private $maxRecords;

    /**
     * @return string
     */
    public function getSearch(): string {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch(string $search) {
        $this->search = $search;
    }

    /**
     * @return Model_Period|null
     */
    public function getPeriod(): ?Model_Period {
        return $this->period;
    }

    /**
     * @param Model_Period|null $period
     */
    public function setPeriod(?Model_Period $period) {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getMaxRecords(): int {
        return $this->maxRecords;
    }

    /**
     * @param int $maxRecords
     */
    public function setMaxRecords(int $maxRecords) {
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
     * @throws
     */
    public static function fromArchivesOnlineRequest(string $query, int $maxRecords): Model_RequestParams {

        $requestParams = new Model_RequestParams();

        $queryArray = \explode("AND", $query);

        switch (\count($queryArray)) {
            case 1:
                $requestParams->setSearch(static::getFirstStringInQuotes($queryArray[0]));
                $requestParams->setPeriod(null);
                break;
            case 2:
                $requestParams->setSearch(static::getFirstStringInQuotes($queryArray[0]));
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
     * Gets the first appearance of a quoted string in a string.
     * @param string $string
     * @return string
     */
    private static function getFirstStringInQuotes(string $string): string {
        $array = [];
        if (\preg_match_all("|\"(.*)\"|U", $string, $array) === false) return "";
        return isset($array[1][0]) ? $array[1][0] : "";
    }

}