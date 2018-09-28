<?php

use gtm\profiler\Menus;
use gtm\profiler\Menu;
use gtm\profiler\Query;
use gtm\profiler\Resource;
use gtm\profiler\Resources;
use gtm\profiler\Panels;
use gtm\profiler\Panel;
use gtm\profiler\Right;
use gtm\profiler\Rights;

$include = preg_replace("/\\\\/", "/", dirname(dirname(dirname(__FILE__))));
require_once "$include/application/models/InstallInterface.php";

require_once "$include/application/models/query/Query.php";
require_once "$include/application/models/query/Resources.php";
require_once "$include/application/models/query/Panels.php";
require_once "$include/application/models/query/Rights.php";
require_once "$include/application/models/query/Menus.php";

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

    function __construct()
    {
        $this->documentRoot = preg_replace("/\\\\/", "/", dirname(dirname(dirname(__FILE__))));
        $this->tmsServerRoot = "hlis/tms_server";
    }

    /**
     * This is the second step
     */
    function install()
    {
        $dbSetup = $this->setupDb();
        $menuSetup = $this->setupMenu();

        return array($dbSetup, $menuSetup);
    }

    private function setupDb()
    {
        return $this->makeResponse("DB setup successfully");
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
        return $this->configurationXmlObject->plugins_configuration->environment;
    }

    private function getCredentials($environment)
    {
        $attr = array();

        $attributes = $this->configurationXmlObject->servers->sql->$environment->server->attributes();
        $attr = (array)$attributes;

        return $attr['@attributes'];
    }

    /**
     * Setup menu items
     */
    private function setupMenu()
    {

        $query = new Query($this->getCredentials($this->getEnvironment()));

        $resources = new Resources($query);
        $panels = new Panels($query);
        $rights = new Rights($query);

        /* -------- Slow Queries -------- */
        $ret = $resources->getByName("Slow Queries");

        if ($ret) return $this->makeResponse("Slow Queries already exists");

        $resource = new Resource();
        $resource->name = "Slow Queries";
        $resourceId = $resources->add($resource);

        $panel = new Panel();
        $panel->url = "slow-queries";
        $panel->id = $resourceId;
        $panelId = $panels->add($panel);

        $menus = new Menus($query);
        $menu = new Menu();
        $menu->holder = "Admin";
        $menu->panel_id = $panelId;
        $menuId = $menus->add($menu);

        $right = new Right();
        $right->resource_id = $resourceId;
        $rights->add($right);

        /* -------- Slow Pages -------- */
        $resource = new Resource();
        $resource->name = "Slow Pages";
        $resourceId = $resources->add($resource);

        $panel = new Panel();
        $panel->url = "slow-pages";
        $panel->id = $resourceId;
        $panelId = $panels->add($panel);

        $right = new Right();
        $right->resource_id = $resourceId;
        $rights->add($right);

        $menus = new Menus($query);
        $menu = new Menu();
        $menu->holder = "Admin";
        $menu->panel_id = $panelId;
        $menuId = $menus->add($menu);

        return $this->makeResponse("Menu setup successfully");

    }

    /**
     * @return SetupMessage
     */
    private function makeResponse($message, $status = true)
    {
        $setupMessage = new SetupMessage();
        $setupMessage->status = true;
        $setupMessage->message = $message;
        return $setupMessage;
    }
}