<?php
/**
 * Created by PhpStorm.
 * User: vijeinath
 * Date: 30.10.17
 * Time: 17:07
 */

declare(strict_types = 1);

include "Model/Resource.php";

class Requester {
    private const DEFAULT_NR_RESULTS = 50;

    private $urlBuilder;
    private $maxResult;

    public function __construct(URLBuilder $urlBuilder, int $maxResult) {
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

            $resource = new Resource($id, $title, $date);
            array_push($resources, $resource);

        }
        return $resources;
    }

    public function httpGet(): array {
        $arg_num = func_num_args(); //Get the numbers of parameters
        $args = func_get_args();    //Get the parameter
        $url = "";

        switch ($arg_num) {
            case 0:
                return array();
            case 1:
                $url = $this->urlBuilder->fulltextURL($args[0], $this->maxResult);
                break;
            case 2:
                if (gettype($args[1]) == "object") {
                    $url = $this->urlBuilder->extendedURL($args[0], $this->maxResult ,$args[1]);
                } else {
                    return array();
                }
                break;
        }

        $res_str = file_get_contents($url);
        $res_obj = json_decode($res_str);

        $subjects = $res_obj->subjects;
        $size = count($subjects);

        return ($size == 0)? array(): $this->getResources($subjects);

    }
}