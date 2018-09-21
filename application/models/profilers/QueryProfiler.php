<?php

namespace gtm\profiler;

use gtm\profiler\Profiler;

require_once("Profiler.php");

/**
 * Toggles query speed profiling in remote sites
 */
class QueryProfiler extends Profiler
{
    /**
     * Gets remote file name, including extension, where profiling is activated
     *
     * @return string
     */
    protected function getFileName()
    {
        return "application/models/DB.php";
    }
}