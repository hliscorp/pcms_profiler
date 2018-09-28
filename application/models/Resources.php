<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 9:43 AM
 */

namespace gtm\profiler;

require_once "entities/Resource.php";

class Resources
{
    protected $query;

    function __construct(Query $query)
    {
        $this->query = $query;
    }

    function add(Resource $resource)
    {
        // create resources
        $this->query("INSERT INTO resources SET name = 'Slow Queries'");
        $resourceId = $this->getInsertId();
        $parentId = $resourceId;
    }
}