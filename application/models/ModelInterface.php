<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 10:44 AM
 */

namespace gtm\profiler;


interface ModelInterface
{
    function __construct(QueryInterface $query);
}