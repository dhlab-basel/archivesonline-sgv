<?php

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
    public function __construct($urlBuilder, $maxResult) {
        $this->urlBuilder = $urlBuilder;
        $this->maxResult = $maxResult;
    }

    /**
     * Extracts the information about the resources from the response.
     * @param array $subjects This contains the raw answer of the salsah API about the resources
     * @return array Returns a clean array with the needed information.
     */
    private function extractResource($subjects) {
        $resources = array();
        foreach ($subjects as $key => $value) {
            $id = $value->obj_id;
            $title = "NONE";
            $dates = null;
            $previewPath = "NONE";

            if (\property_exists($value, "value")) {
                $id = $value->obj_id;
                if (\is_array($value->value)) {
                    $title = $value->value["2"];
                    $dates = $value->value["1"];
                }
            };

            if (\property_exists($value, "preview_path")) {
                $previewPath = $value->preview_path;
            }

            if ($title === "") {
                $title = static::getTitleFromResource($id);
            }

            $previewPath = static::generatePictureURL($previewPath);
            $resource = new Model_Resource($id, $title, $dates, $previewPath);
            $resources[$id] = $resource;
        }
        return $resources;
    }

    /**
     * Initiates the GET Request to the salsah extended search API and returns an array with all the resource information.
     * @param array $searchWords Contains the search words
     * @param bool $isAND Tells how the search words (in case more than one) has to be conjuncted. There are two possible conjunctions: "AND" & "OR".
     * @param Model_Period|null $period Contains the period of the search. In case there was no period given, it sets a default period which starts with the year 1 until the current year (Gregorian calendar).
     * @return array Returns the array with the resource information.
     */
    public function httpGet($searchWords, $isAND, $period) {
        $url = "";

        if (!$period instanceof Model_Period) {
            $period = new Model_Period(Config::MIN_SEARCH_YEAR, intval(date("Y")));
        }
        $url = $this->urlBuilder->getSearchURL($searchWords, $this->maxResult, $period, $isAND);

        $res_str = \file_get_contents($url);
        $res_obj = \json_decode($res_str);
        $subjects = $res_obj->subjects;
        $size = \count($subjects);

        return ($size == 0)? array(): $this->extractResource($subjects);
    }

    /**
     * Tries to get the title of the resource by doing a resource request in case the first request had given an empty string for the $title.
     * @param number $id
     * @return string Returns the title if it exists
     */
    private function getTitleFromResource($id) {
        $url = $this->urlBuilder->getResourceURL($id);

        $res_str = \file_get_contents($url);
        $res_obj = \json_decode($res_str);
        $props = $res_obj->props;

        if (\property_exists($props, "dc:title") && \property_exists($props->{"dc:title"}, "values") && isset($props->{"dc:title"}->{"values"}[1])) {
            return $props->{"dc:title"}->{"values"}[1];
        } else {
            return "";
        }

    }

    /**
     * Generates the url for the picture of a resource.
     * @param string $previewPath
     * @return string Returns the url for the picture if it is possible.
     */
    private static function generatePictureURL($previewPath) {
        $pattern = "/^http:\/\/www\.salsah\.org\/core\/location\.php/";

        if( \preg_match($pattern, $previewPath)) {
            //Default picture in case there is no access for the picture URL
            return $previewPath;
        } else {
            return \str_replace("qtype=thumb", "qtype=full&reduce=2", $previewPath);
        }

    }

}
