<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 9:42 AM
 */

namespace gtm\profiler;

require_once "application/models/ModelInterface.php";
require_once "application/models/entities/Panel.php";

/**
 * @property QueryInterface query
 */
class Panels implements ModelInterface
{

    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    function add(Panel $panel)
    {
        $ret = $this->query->query("INSERT INTO panels SET id = $panel->id, url = '$panel->url'");
        return $this->query->getInsertId();
    }
}