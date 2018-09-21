<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 10:04 AM
 */

namespace gtm\profiler;


interface QueryInterface
{
    function __construct(array $credentials);

    function query($sql);

    function getInsertId();
}