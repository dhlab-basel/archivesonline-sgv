<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

class Model_Requester {
    private const DEFAULT_NR_RESULTS = 50;

    private $urlBuilder;
    private $maxResult;

    public function __construct(Model_URLBuilder $urlBuilder, int $maxResult) {
        $this->urlBuilder = $urlBuilder;
        $this->maxResult = $maxResult;
    }

    private function getResources(array $subjects) {
        $resources = array();
        foreach ($subjects as $key => $value) {

            $id = $value->obj_id;
            $title = "[no title]";
            $date = null;

            $res_str = file_get_contents($this->urlBuilder->resourceURL($id));
            $res_obj = json_decode($res_str);

            //Gets the title of the resource
            if (property_exists($res_obj->props,"dc:title")) {
                if (property_exists($res_obj->props->{"dc:title"},"values")) {
                    $title = reset($res_obj->props->{"dc:title"}->values);
                }


            }

            //Gets dates of the resource
            if (property_exists($res_obj->props,"dc:date")) {
                if (property_exists($res_obj->props->{"dc:date"}, "values")) {
                    $date = reset($res_obj->props->{"dc:date"}->values); // $date is of type "stdClass"
                }
            }

            $resource = new Model_Resource($id, $title, $date);
            array_push($resources, $resource);

        }
        return $resources;
    }

    public function httpGet(string $search, ?Model_Period $period = null): array {

        $url = "";
        if ($period instanceof Model_Period) {
            $url = $this->urlBuilder->extendedURL($search, $this->maxResult, $period);
        } else {
            $url = $this->urlBuilder->fulltextURL($search, $this->maxResult);
        }


        $res_str = \file_get_contents($url);

        $res_obj = \json_decode($res_str);

        $subjects = $res_obj->subjects;
        $size = \count($subjects);

        return ($size == 0)? array(): $this->getResources($subjects);

    }
}