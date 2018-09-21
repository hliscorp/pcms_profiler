<?php
require_once("application/controllers/AbstractLoggedInController.php");
require_once("hlis/profilers/src/PageProfiler.php");
require_once("hlis/profilers/src/PageProfilerResults.php");

class SlowPagesController extends AbstractLoggedInController
{
    protected function service() {
        if(!empty($_POST)) {
            $profiler = new PageProfiler($this->application);
            $profiler->setStatus($_POST["status"]);
        }

        $profiler = new PageProfiler($this->application);
        $this->response->setAttribute("status", $profiler->getStatus());

        $profilingResults = new PageProfilerResults($this->application);
        $this->response->setAttribute("results", $profilingResults->getLines());
    }
}