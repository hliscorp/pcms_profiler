<?php

namespace gtm\cms\plugin\profiler;

use gtm\profiler\Query;
use InstallInterface;
use mysqli;

$include = preg_replace("/\\\\/", "/", dirname(dirname(dirname(__FILE__))));
require_once "$include/application/models/InstallInterface.php";
require_once "$include/application/models/query/Query.php";

require_once "application/models/Resources.php";

/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 28-Aug-18
 * Time: 3:46 PM
 */
class SetupMessage
{
    public $status = false;
    public $message = null;
}

class Install implements InstallInterface
{

    private $documentRoot;
    private $tmsServerRoot;
    private $configurationXml;
    private $configurationJson;
    private $configurationJsonObject;
    private $configurationXmlObject;
    private $mysqli;

    function __construct()
    {
        $this->documentRoot = preg_replace("/\\\\/", "/", dirname(dirname(dirname(__FILE__))));
        $this->tmsServerRoot = "hlis/tms_server";
    }

    /**
     * This is the second step
     */
    public function install()
    {
        $dbSetup = $this->setupDb();
        $menuSetup = $this->setupMenu();

        return array($dbSetup, $menuSetup);
    }

    private function setupDb()
    {
        // TODO: add setup db code here
        $setupMessage = new SetupMessage();
        $setupMessage->status = true;
        $setupMessage->message = "DB setup successfully";
        return $setupMessage;
    }

    private function getRoutes($fileLocation)
    {
        $xml = simplexml_load_file($fileLocation);
        $output = array();
        $tmp = (array)$xml->routes;
        $tmp = $tmp["route"];
        foreach ($tmp as $info) {
            $output[] = (string)$info['url'];
        }
        return $output;
    }

    private function checkRequirements()
    {
        // Check if we have anything we need to proceed with the installation
        $ret = array();
        $ret[] = $this->checkTmsServer();
        return $ret;
    }

    /**
     * @return SetupMessage
     */
    private function checkTmsServer()
    {
        $filename = $this->documentRoot . "/" . $this->tmsServerRoot;
        $setupMessage = new SetupMessage();

        if (file_exists($filename)) {
            $setupMessage->status = true;
            $setupMessage->message = "The TMS Server was is present.";
        } else {
            $setupMessage->status = false;
            $setupMessage->message = "TMS Server not found";
        }

        return $setupMessage;
    }

    /**
     * @param $requirementsMeet
     * @return mixed
     */
    private function requirementMeet($requirementsMeet)
    {
        foreach ($requirementsMeet as $requirement) {
            if ($requirement->status == false) return false;
        }
        return true;
    }

    function setConfigurationXml($configurationXml)
    {
        $this->configurationXml = $configurationXml;
        $this->configurationXmlObject = simplexml_load_file($this->configurationXml);
    }

    function setConfigurationJson($configurationJson)
    {
        $this->configurationJson = $configurationJson;
        // $this->configurationJsonObject = json_decode(file_get_contents($this->configurationJson));
    }

    private function getEnvironment()
    {
        //return $this->configurationJsonObject->persistence->environment;
        return $this->configurationXmlObject->plugins_configuration->environment;
    }

    private function getCredentials($environment)
    {
        $attr = array();

        $attributes = $this->configurationXmlObject->servers->sql->$environment->server->attributes();
        $attr = (array)$attributes;

        return $attr['@attributes'];
    }

    private function saveRoutes($routes, $link)
    {
        foreach ($routes as $route) {
            $this->query("INSERT IGNORE INTO tms__route_patterns SET value = '$route'");
        }
    }

    private function connect()
    {
        if ($this->mysqli) return $this->mysqli;
        $env = (string)$this->getEnvironment();
        $credentials = $this->getCredentials($env);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($credentials['host'], $credentials['username'], $credentials['password'], $credentials['schema']);
        return $this->mysqli;
    }

    /**
     * Setup menu items
     */
    private function setupMenu()
    {

        $query = new Query($this->getCredentials($this->getEnvironment()));
        $resources = new \gtm\profiler\Resources($query);

        $resourceId = $this->resourceExists('Profiler');
        $setupMessage = new SetupMessage();

        if ($resourceId) {
            $setupMessage->status = true;
            $setupMessage->message = "Profiler menu already exists";
            return $setupMessage;
        }

        /* ------------------------ Slow Queries ------------------------ */

        // create resources
        $this->query("INSERT INTO resources SET name = 'Slow Queries'");
        $resourceId = $this->getInsertId();
        $parentId = $resourceId;

        // create the panels
        $ret = $this->query("INSERT INTO panels SET id = $resourceId, url = 'slow-queries'");
        $panelId = $this->getInsertId();
        $masterPanelId = $panelId;

        // create rights
        $this->query("INSERT INTO rights SET resource_id = $panelId, department_id = 2, level_id = 1");

        /* ------------------------ Slow Pages ------------------------ */

        // create resources
        $this->query("INSERT INTO resources SET name = 'Player Reviews Change Dashboard'");
        $resourceId = $this->getInsertId();

        // create the panels
        $this->query("INSERT INTO panels SET id = $resourceId, parent_id = $parentId, url = 'player-reviews-dashboard/change'");
        $panelId = $this->getInsertId();

        // create rights
        $this->query("INSERT INTO rights SET resource_id = $panelId, department_id = 2, level_id = 1");


        // set parent menu
        $this->query("INSERT INTO menu SET panel_id = $masterPanelId, holder = 'Admin'");

        $setupMessage->status = true;
        $setupMessage->message = "Menu setup successfully";
        return $setupMessage;

    }

    private function query($query)
    {
        $this->connect();
        try {
            $ret = $this->mysqli->query($query);
        } catch (Exception $e) {
            echo "$e->getMessage()\n";
        }

        return $ret;
    }

    private function getInsertId()
    {
        return $this->mysqli->insert_id;
    }

    private function resourceExists($name)
    {
        $result = $this->query("SELECT id FROM resources WHERE name = '$name'");

        if ($result) {
            while ($row = $result->fetch_row()) {
                return (int)$row[0];
            }
        }

        return null;
    }
}