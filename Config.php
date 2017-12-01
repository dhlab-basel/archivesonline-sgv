<?php

declare(strict_types = 1);

namespace ArchivesOnlineSGV;

/**
 * Class Config This class contains only static constants
 * @package ArchivesOnlineSGV
 */
class Config {

    /**
     *  @var integer Minimum of search results that can be defined.
     */
    const MIN_SEARCH_RESULTS = 1;
    /**
     * @var integer Maximum of search results that can be defined.
     */
    const MAX_SEARCH_RESULTS = 50;
    /**
     * @var integer In case there was no period, this constant will be taken as the start date.
     */
    const MIN_SEARCH_YEAR = 1;

}
