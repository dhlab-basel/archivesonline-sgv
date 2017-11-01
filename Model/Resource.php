<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

class Model_Resource {
    private $id;
    private $title;
    public $dateValues;
    private $date1;
    private $date2;

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

    public function __construct(string $id, string $title, \stdClass $dateValues) {
        $this->id = $id;
        $this->title = $title;
        $this->dateValues = $dateValues;
        if ($dateValues != null) {
            $this->date1 = self::convertDate((int)$dateValues->dateval1, $dateValues->dateprecision1);
            $this->date2 = self::convertDate((int)$dateValues->dateval2, $dateValues->dateprecision2);
        } else {
            $this->date1 = "NONE";
            $this->date2 = "NONE";
        }
    }

    public function getID(): string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDate(): string {
        return "ConvertedDate 1: " . $this->date1 . " | ConvertedDate 2: " . $this->date2;
    }

}