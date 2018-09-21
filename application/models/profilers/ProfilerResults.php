<?php

namespace gtm\profiler;

require_once("ProfilerLine.php");

/**
 * Reads profiling results from associated log file in remote site
 */
abstract class ProfilerResults
{
    private $lines = array();

    /**
     * ProfilerResults constructor.
     *
     * @param Application $application Holds information about development environment and parent_site XML tag
     */
    public function __construct(Application $application) {
        $this->setLines($application);
    }

    /**
     * Opens log files and maps each line content to a ProfilerLine object
     *
     * @param Application $application
     */
    private function setLines(Application $application) {
        $parentProjectPath = (string) $application->getXML()->parent_site->{$application->getAttribute("environment")}["path"];
        $logFile = $parentProjectPath."/".$this->getFileName();
        if(!file_exists($logFile)) return;
        $currentDate = date("Y-m-d");
        $elements = file($logFile);
        foreach($elements as $line) {
            if(strpos($line, $currentDate) !==0) continue; // we only care for lines that happened today
            $parts = explode("\t", $line);
            $this->lines[] = $this->getLineDetails($parts);
        }
        // starts with newest lines
        $this->lines = array_reverse($this->lines);
    }

    /**
     * Returns encapsulated profiling results
     *
     * @return ProfilerLine[] List of log lines info.
     */
    public function getLines() {
        return $this->lines;
    }

    /**
     * Gets name of log file where profiling is saved.
     *
     * @return string
     */
    abstract protected function getFileName();

    /**
     * Encapsulates profiling line into a struct style object
     *
     * @param string[] $parts Items in log line, broken by TAB
     * @return ProfilerLine
     */
    abstract protected function getLineDetails($parts);
}