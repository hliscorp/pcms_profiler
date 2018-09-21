<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 9:43 AM
 */

namespace gtm\profiler;

require_once "application/models/ModelInterface.php";
require_once "application/models/entities/Resource.php";
require_once "application/models/Query.php";
require_once "application/models/ToList.php";

class Resources implements ModelInterface
{
    protected $query;
    private $parentId;

    function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    function add(Resource $resource)
    {
        $this->query->query("INSERT INTO resources SET name = '$resource->name'");
        $this->parentId = $this->query->getInsertId();
        return $this->parentId;
    }

    function getByName($name)
    {
        $result = $this->query->query("SELECT * FROM resources WHERE name = '$name'");
        $toList = new ToList($result);
        return $toList->getList();
    }
}