<?php
require_once("Profiler.php");

/**
 * Toggles page speed profiling in remote sites
 */
class PageProfiler extends Profiler
{
    /**
     * Gets remote file name, including extension, where profiling is activated
     *
     * @return string
     */
    protected function getFileName() {
        return "index.php";
    }
}