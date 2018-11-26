<?php
require_once("ProfilerResults.php");

/**
 * Reads query profiling results from associated log file in remote site
 */
class QueryProfilerResults extends ProfilerResults
{
    protected function getFileName()
    {
        return "query.log";
    }

    protected function getLineDetails($parts)
    {
        return array(
            "date"=>$parts[0],
            "host"=>$parts[1],
            "url"=>$parts[2],
            "duration"=>round($parts[3],3),
            "query"=>preg_replace("/(inner\ join|left join|from|where|group\ by|order\ by|having|limit)/i","<br/>".'${1}', $parts[4])
        );
    }
}