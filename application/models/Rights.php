<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 10:59 AM
 */

namespace gtm\profiler;

require_once "application/models/entities/Right.php";

class Rights implements ModelInterface
{

    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    function add(Right $right)
    {
        $this->query->query("INSERT INTO rights SET resource_id = $right->resource_id, department_id = $right->department_id, level_id = $right->level_id");
        return $this->query->getInsertId();
    }
}