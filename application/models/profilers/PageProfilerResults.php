<?php
require_once("ProfilerResults.php");

/**
 * Reads page profiling results from associated log file in remote site
 */
class PageProfilerResults extends ProfilerResults
{
    protected function getFileName()
    {
        return "benchmark.log";
    }

    protected function getLineDetails($parts)
    {
        return array(
            "date"=>$parts[0],
            "host"=>$parts[1],
            "url"=>$parts[2],
            "duration"=>round($parts[3],3)
        );
    }
}