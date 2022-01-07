<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @var object
     */
    protected $exectionTime;

    /**
     * @var string
     */
    protected $data;

    /**
     * @param $message
     * @param $status_code
     * @param string $data
     * @param string $executionTime
     * @return object
     */
    public function sendResponse($message, $status_code, $data, string $executionTime = '')
    {
        $this->data = !empty($data) ? $data : '';
        $this->exectionTime = !empty($executionTime) ? $executionTime : microtime(true);

        $response = [
            'status' => true,
            'execution_time' => $this->exectionTime,
            'content_size' => intval($_SERVER['CONTENT_LENGTH']),
            'status_code' => $status_code,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $status_code);
    }

    /**
     * Return error response.
     * @param $error
     * @param int $status_code
     * @param string $executionTime
     * @return JsonResponse
     */
    public function sendError($error, int $status_code = 404, string $executionTime = ''): JsonResponse
    {
        $this->exectionTime = !empty($executionTime) ? $executionTime : microtime(true);
        if ($status_code == 422) {
            $response = [
                'status' => $status_code,
                'message' => array('status' => array($error)),
            ];
        } else {
            $response = [
                'status' => false,
                'execution_time' => $this->exectionTime,
                'content_size' => intval($_SERVER['CONTENT_LENGTH']),
                'status_code' => $status_code,
                'message' => $error,
            ];
        }

        return response()->json($response, $status_code);
    }

    /**
     * @return mixed
     */
    public function calculateTime()
    {
        return microtime(true);
    }

    /**
     * @param $e
     * @return JsonResponse
     */
    public function apiException($e)
    {
        app('sentry')->captureException($e);
        return response()->json(
            ['error' => ['message' => $e->getMessage()]],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
