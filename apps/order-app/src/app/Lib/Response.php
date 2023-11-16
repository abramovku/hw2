<?php
namespace App\Lib;

class Response
{
    private const NAMESPACE = 'crudapp';
    private $status = 200;
    private $startTime;
    private $request;
    private $metricsService;

    private array $metricsRequestCounter = [
        'name' => 'app_request_count',
        'help' => 'Application Request Count',
        'labels' => [
            'method', 'endpoint', 'http_status'
        ]
    ];

    private array $metricsRequestLatency = [
        'name' => 'app_request_latency_seconds',
        'help' => 'Application Request Latency',
        'labels' => [
            "method", "endpoint"
        ]
    ];

    public function __construct()
    {
        $this->metricsService = new Metrics();
    }

    public function status(int $code)
    {
        $this->status = $code;
        return $this;
    }

    public function startTime(int $time)
    {
        $this->startTime = $time;
        return $this;
    }

    public function request(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function toJSON($data = [])
    {
        $this->metricStore();
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function raw(string $data)
    {
        http_response_code($this->status);
        echo $data;
    }

	public function view(string $path, array $vars = [])
	{
		http_response_code($this->status);
		(new View())->render($path, $vars);
	}

    private function metricStore()
    {
        $endTime = time() - $this->startTime;
        if(!$this->request) return;

        /** Calc request count */
        $requestCounter = $this->metricsService->getPrometheusRegistry()->getOrRegisterCounter(
            self::NAMESPACE,
            $this->metricsRequestCounter['name'],
            $this->metricsRequestCounter['help'],
            $this->metricsRequestCounter['labels']
        );

        $requestCounter->inc([
            $this->request->getMethod(),
            $this->request->getRequestUri(),
            $this->status
        ]);

        /** Calc request latency */
        $requestLatencyHistogram = $this->metricsService->getPrometheusRegistry()->getOrRegisterHistogram(
            self::NAMESPACE,
            $this->metricsRequestLatency['name'],
            $this->metricsRequestLatency['help'],
            $this->metricsRequestLatency['labels']
        );

        $requestLatencyHistogram->observe($endTime, [
            $this->request->getMethod(),
            $this->request->getRequestUri()
        ]);
    }
}