<?php
/**
 * Created by PhpStorm.
 * User: Liviu
 * Date: 21-Sep-18
 * Time: 9:49 AM
 */

namespace gtm\profiler;

use mysqli;

require_once "application/models/QueryInterface.php";

class Query implements QueryInterface
{
    private $credentials;
    private $mysqli;

    function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->connect();
    }

    function query($sql)
    {
        try {
            $result = $this->mysqli->query($sql);
        } catch (Exception $e) {
            echo "$e->getMessage()\n";
        }

        return $result;
    }

    private function connect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli($this->credentials['host'], $this->credentials['username'], $this->credentials['password'], $this->credentials['schema']);
    }

    function getInsertId()
    {
        return $this->mysqli->insert_id;
    }
}