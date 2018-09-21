<?php
require_once("application/controllers/AbstractLoggedInController.php");
require_once("plugins/pcms_profiler/application/models/profilers/QueryProfilerResults.php");
require_once("plugins/pcms_profiler/application/models/profilers/QueryProfiler.php");

class SlowQueriesController extends AbstractLoggedInController
{
    protected function service()
    {
        if (!empty($_POST)) {
            $profiler = new QueryProfiler($this->application);
            $profiler->setStatus($_POST["status"]);
        }

        $profiler = new QueryProfiler($this->application);
        $this->response->setAttribute("status", $profiler->getStatus());

        $profilingResults = new QueryProfilerResults($this->application);
        $this->response->setAttribute("results", $profilingResults->getLines());
    }
}