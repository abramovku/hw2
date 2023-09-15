<?php

namespace App\Controllers;

use Prometheus\RenderTextFormat;
use App\Lib\Request;
use App\Lib\Response;
use App\Lib\Metrics;

class MainController extends Controller
{
    private $metricsService;

    public function __construct()
    {
        $this->metricsService = new Metrics();
    }

    public function metrics(Request $req, Response $res)
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->metricsService->getPrometheusRegistry()->getMetricFamilySamples());
        $res->raw($result);
    }
}
