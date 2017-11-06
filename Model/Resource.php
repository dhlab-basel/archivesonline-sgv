<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Model_Resource This class represents the resource which stores the information.
 * @package ArchivesOnlineSGV
 */
class Model_Resource {
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var null|\stdClass Date Object which was fetched by the salsah API. This Object stores the two date values in julian day, the type of calendar, and the date precisions
     */
    private $dateValues;
    /**
     * @var string Represents the start date of a period in gregorian calendar format.
     */
    private $date1;
    /**
     * @var string Represents the end date of a period in gregorian calendar format.
     */
    private $date2;

    /**
     * Converts the parameter $date, which is julian day (!not the same like julian calendar), into the gregorian calendar.
     * @param int $date Represents the julian day
     * @param string $precision Gives the precision of the day. There are currently three possible precisions: DAY, MONTH, YEAR
     * @return string
     */
    private static function convertDate(int $date, string $precision): string {
        $date_parts = explode("/" ,jdtogregorian($date));
        switch ($precision) {
            case "DAY":
                return strval($date_parts[1] . "." . strval($date_parts[0]) . "." . strval($date_parts[2]));
            case "MONTH":
                return strval($date_parts[1]) . "." .strval($date_parts[0]);
            case "YEAR":
                return strval($date_parts[2]);
            default:
                return "NONE";
        }
    }

    /**
     * Model_Resource constructor.
     * @param string $id
     * @param string $title
     * @param null|\stdClass $dateValues
     */
    public function __construct(string $id, string $title, ?\stdClass $dateValues) {
        $this->id = $id;
        $this->title = $title;
        $this->dateValues = $dateValues;
        if ($dateValues != null) {
            $this->date1 = self::convertDate((int)$dateValues->dateval1, $dateValues->dateprecision1);
            $this->date2 = self::convertDate((int)$dateValues->dateval2, $dateValues->dateprecision2);
        } else {
            $this->date2 = "Undatiert";
            $this->date1 = "Undatiert";
        }
    }

    /**
     * Gets the ID of the resource. ID can be used with salsah API to check the details of the resource in JSON format.
     * The API is : http://www.salsah.org/api/resource/{ID}
     * @return string
     */
    public function getID(): string {
        return $this->id;
    }

    /**
     * Gets the title of the resource.
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * Gets the date of the resource in the right format.
     * @return string Returns the date. In case the date represent a period (= date1 and date2 are the same), the string contains both dates separated by a hyphen.
     */
    public function getDate(): string {
        return ($this->date1 === $this->date2) ? $this->date1: $this->date1 . "- " . $this->date2;
    }

}