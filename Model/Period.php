<?php
/**
 * Created by PhpStorm.
 * User: vijeinath
 * Date: 31.10.17
 * Time: 11:42
 */

class Period {
    private $fromYear;
    private $toYear;

    public function __construct(int $fromYear, int $toYear) {
        $this->fromYear = $fromYear;
        $this->toYear = $toYear;
    }

    public function getFromYear() {
        return $this->fromYear;
    }

    public function getToYear() {
        return $this->toYear;
    }

    public function isSameYear(): bool {
        return $this->fromYear == $this->toYear;
    }

    public function __toString() {
        return "Period: ". $this->fromYear. "- ". $this->toYear;
    }
}