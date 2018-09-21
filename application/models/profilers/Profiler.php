<?php

namespace gtm\profiler;

/**
 * Toggles profiling in remote sites. Requires:
 * - development environment already detected
 * - an entry in site CMS configuration.xml based on environment above holding site's hostname and root folder
 * Eg:
 * <parent_site>
 * <dev host="dev.casinofreak.com" path="/home/casinosf/public_html/dev/site"/>
 * <live host="www.casinofreak.com" path="/home/casinosf/public_html/live/site"/>
 * <lucinda host="www.casinofreak.local" path="/var/www/html/nonstopbonus"/>
 * </parent_site>
 */
abstract class Profiler
{
    private $filePath;

    /**
     * Profiler constructor.
     *
     * @param Application $application
     * @throws Exception
     */
    public function __construct(\Application $application)
    {
        $this->filePath = $this->getFilePath($application);
    }

    /**
     * Gets absolute path to profiling log file.
     *
     * @param Application $application
     * @return string
     * @throws Exception
     */
    private function getFilePath(\Application $application)
    {
        $parentProjectPath = (string)$application->getXML()->parent_site->{$application->getAttribute("environment")}["path"];
        $normalizedFile = $parentProjectPath . "/" . $this->getFileName();
        if (!file_exists($normalizedFile)) throw new Exception("Remote file in which profiling must be activated not found!");
        return $normalizedFile;
    }

    /**
     * Gets profiling status from remote PHP file
     *
     * @return bool True if active, false if not.
     * @throws Exception If remote file could not be found
     */
    public function getStatus()
    {
        $body = file_get_contents($this->filePath);
        preg_match('/\$benchmark[\ ]*=[\ ]*([a-zA-Z]+)[\ ]*/', $body, $matches);
        if (!isset($matches[1])) throw new Exception("Remote file cannot be set up for profiling!");
        return ($matches[1] == "false" ? false : true);
    }

    /**
     * Sets profiling status into remote PHP file. Profiling is toggled in remote file via line:
     * $benchmark = false;
     *
     * @param bool $isActive True if active, false if not
     */
    public function setStatus($isActive)
    {
        $body = file_get_contents($this->filePath);
        if ($isActive) {
            $body = str_replace('$benchmark = false', '$benchmark = true', $body);
        } else {
            $body = str_replace('$benchmark = true', '$benchmark = false', $body);
        }
        file_put_contents($this->filePath, $body);
    }

    /**
     * Gets profile log file name, including extension
     *
     * @return string
     */
    abstract protected function getFileName();
}