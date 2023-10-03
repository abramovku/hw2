<?php
namespace App\Lib;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\APC;

class Metrics
{
    private $prometheusRegistry;

    public function __construct()
    {
        $this->prometheusRegistry = new CollectorRegistry(new APC());
    }

    public function getPrometheusRegistry(): CollectorRegistry
    {
        return $this->prometheusRegistry;
    }
}
