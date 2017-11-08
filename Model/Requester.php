<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Model_Requester This class initiates the request to the salsah API and extracts the information about the resources.
 * @package ArchivesOnlineSGV
 */
class Model_Requester {
    /**
     * @var Model_URLBuilder
     */
    private $urlBuilder;
    /**
     * @var int
     */
    private $maxResult;

    /**
     * Model_Requester constructor.
     * @param Model_URLBuilder $urlBuilder
     * @param int $maxResult
     */
    public function __construct(Model_URLBuilder $urlBuilder, int $maxResult) {
        $this->urlBuilder = $urlBuilder;
        $this->maxResult = $maxResult;
    }

    /**
     * Extracts the information about the resources from the response.
     * @param array $subjects This contains the raw answer of the salsah API about the resources
     * @return array Returns a clean array with the needed information.
     */
    private function extractResource(array $subjects): array {
        $resources = array();
        foreach ($subjects as $key => $value) {
            $id = $value->obj_id;
            $title = "NONE";
            $dates = null;

            if (\property_exists($value, "value")) {
                $id = $value->obj_id;
                if (\is_array($value->value)) {
                    $title = $value->value["1"];
                    $dates = $value->value["2"];
                }
            };

            $resource = new Model_Resource($id, $title, $dates);
            \array_push($resources, $resource);
        }
        return $resources;
    }

    /**
     * Initiates the GET Request to the salsah extended search API and converts the answer into a string.
     * @param string $search Contains the search word
     * @param Model_Period|null $period Contains the period of the search. In case there was no period given, it sets a default period which starts with the year 1 until the current year (Gregorian calendar).
     * @return array
     */
    public function httpGet(string $search, ?Model_Period $period = null): array {
        $url = "";

        if (!$period instanceof Model_Period) {
            $period = new Model_Period(Config::MIN_SEARCH_YEAR, intval(date("Y")));
        }
        $url = $this->urlBuilder->extendedURL($search, $this->maxResult, $period);

        $res_str = \file_get_contents($url);
        $res_obj = \json_decode($res_str);
        $subjects = $res_obj->subjects;
        $size = \count($subjects);

        return ($size == 0)? array(): $this->extractResource($subjects);
    }
}