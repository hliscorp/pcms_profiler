<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 12:40 PM
 */

namespace gtm\profiler;

require_once "application/models/entities/Menu.php";

class Menus implements ModelInterface
{
    public function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    function add(Menu $menu)
    {
        $this->query->query("INSERT INTO menu SET panel_id = $menu->panel_id, holder = '$menu->holder'");
        return $this->query->getInsertId();
    }
}