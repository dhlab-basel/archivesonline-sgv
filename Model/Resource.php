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
     * @var string Represents the start date of a period reduced to the precision.
     */
    private $date1_reduced;
    /**
     * @var string Represents the end date of a period reduced to the precision.
     */
    private $date2_reduced;
    private $date1_full;
    private $date2_full;

    /**
     * Converts the parameter $date, which is julian day (!not the same like julian calendar), into the gregorian calendar.
     * @param int $date Represents the julian day
     * @param string $precision Gives the precision of the day. There are currently three possible precisions: DAY, MONTH, YEAR
     * @return string
     */
    private static function convertDate(int $date, string $precision, bool $reduction): string {
        $date_parts = \explode("/" , \jdtogregorian($date));
        if ($reduction) {
            switch ($precision) {
                case "DAY":
                    return \strval($date_parts[1] . "." . \strval($date_parts[0]) . "." . \strval($date_parts[2]));
                case "MONTH":
                    return \strval($date_parts[0]) . "." . \strval($date_parts[2]);
                case "YEAR":
                    return \strval($date_parts[2]);
                default:
                    return "NONE";
            }
        } else {
            return $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
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
            $this->date1_reduced = static::convertDate((int)$dateValues->dateval1, $dateValues->dateprecision1, true);
            $this->date2_reduced = static::convertDate((int)$dateValues->dateval2, $dateValues->dateprecision2, true);
            $this->date1_full = static::convertDate((int)$dateValues->dateval1, $dateValues->dateprecision1, false);
            $this->date2_full = static::convertDate((int)$dateValues->dateval2, $dateValues->dateprecision2, false);
        } else {
            $this->date2_reduced = "Undatiert";
            $this->date1_reduced = "Undatiert";
            $this->date1_full = "";
            $this->date2_full = "";
        }
    }

    /**
     * Gets the ID of the resource. ID can be used with salsah API to check the details of the resource.
     * The API is : http://www.salsah.org/api/resource/{ID} and you receive a JSON.
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
        return ($this->date1_reduced === $this->date2_reduced) ? $this->date1_reduced: $this->date1_reduced . "- " . $this->date2_reduced;
    }

    /**
     * Gets the start date of the resource without reduction.
     * @return string
     */
    public function getDate1Full(): string {
        return $this->date1_full;
    }


    /**
     * Gets the end date of the resource without reduction.
     * @return string
     */
    public function getDate2Full(): string {
        return $this->date2_full;
    }

}
