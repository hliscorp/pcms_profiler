<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 12:06 PM
 */

namespace gtm\profiler;


class ToList
{
    private $result;
    private $list;

    function __construct($result)
    {
        $this->result = $result;
        $fields = $result->fetch_fields();

        while ($row = $result->fetch_row()) {
            $column = 0;
            foreach ($fields as $field) {
                $set[$field->name] = $row[$column];
                $column++;
            }
            $this->list[] = $set;
        }

    }

    function getList()
    {
        return $this->list;
    }
}