<?php

namespace App\System\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class Responder
{
    /**
     * Response Service unauthorized.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function unauthorized($message = '', $hints = '', array $header = [])
    {
        return self::response([], 401, $message, $hints, $header);
    }

    /**
     * Response Service timeout.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function timeout($message = '', $hints = '', array $header = [])
    {
        return self::response([], 408, $message, $hints, $header);
    }

    /**
     * Response Service methodFalse.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function methodFalse($message = '', $hints = '', array $header = [])
    {
        return self::response([], 405, $message, $hints, $header);
    }

    /**
     * Response Service forbidden.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function forbidden($message = '', $hints = '', array $header = [])
    {
        return self::response([], 403, $message, $hints, $header);
    }

    /**
     * Response Service unavailable.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function unavailable($message = '', $hints = '', array $header = [])
    {
        return self::response([], 503, $message, $hints, $header);
    }

    /**
     * Response Internal Server Error.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function error($message = '', $hints = '', array $header = [])
    {
        return self::response([], 500, $message, $hints, $header);
    }

    /**
     * Response 404 not found.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function notFound($message = '', $hints = '', array $header = [])
    {
        return self::response([], 404, $message, $hints, $header);
    }

    /**
     * Response 419 token mismatch.
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function tokenMismatch($message = '', $hints = '', array $header = [])
    {
        return self::response([], 419, $message, $hints, $header);
    }

    /**
     * Response invalid.
     * @param array $errors
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function invalid(array $errors, $message = '', $hints = '', array $header = [])
    {
        return self::response($errors, 422, $message, $hints, $header);
    }

    /**
     * Response data for save data.
     * @param array|object $data
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function saved($data, $message = '', $hints = '', array $header = [])
    {
        return self::response($data, 201, $message, $hints, $header);
    }

    /**
     * Response data.
     * @param array|object $data
     * @param string $message
     * @param string $hints
     * @param array $header
     * @return ResponseFactory|Response
     */
    public static function data($data, $message = '', $hints = '', array $header = [])
    {
        return self::response($data, 200, $message, $hints, $header);
    }

    /**
     * Auto content type response by Accept header request.
     * @param array|object $data
     * @param int $status
     * @param array $header
     * @param string $message
     * @param string $hints
     * @return ResponseFactory|Response
     */
    public static function response($data = [], $status = 200, $message = '', $hints = '', array $header = [])
    {
        // Cast object to array.
        if ($data instanceof Collection or $data instanceof Model) {
            $data = $data->toArray();
        }

        // Format body response.
        $content = static::body($status, $data, $message, $hints);

        // Add more header response info.
        $header = static::header($header);

        // Response as access defined.
        foreach (request()->getAcceptableContentTypes() as $type) {
            switch ($type) {
                case 'application/xml':
                case 'text/xml':
                case 'xml':
                    // Not support.
                    break;

                case 'text/html':
                    // Not support.
                    break;

                case 'application/json':
                case 'application/x-javascript':
                case 'text/json':
                case 'text/x-json':
                case 'text/javascript':
                case 'text/x-javascript':
                case 'json':
                default:
                    return response()->json($content, $status, $header);
                    break;
            }
        }

        return response()->json($content, $status, $header);
    }

    /**
     * Format body response.
     * @param int $status
     * @param mixed $data
     * @param string $message
     * @param string $hints
     * @return array
     */
    private static function body($status, $data = [], $message = 'ok', $hints = '')
    {
        return [
            'meta' => [
                'code' => self::statusToCodes($status),
                'message' => $message,
                'hints' => $hints,
            ],
            'data' => $data
        ];
    }

    /**
     * Update header before response.
     * @param $header
     * @return array
     */
    private static function header($header)
    {
        $header['Content-Language'] = app()->getLocale();
        $header['X-Powered-By'] = config('main.api_auth');
        $header['X-Version'] = config('main.api_version');

        return $header;
    }

    /**
     * Return message code.
     * @param int $status
     * @return mixed|string
     */
    private static function statusToCodes($status)
    {
        $httpStatus = [
            200 => 'ok',
            201 => 'created',
            401 => 'unauthorized',
            403 => 'forbidden',
            404 => 'not_found',
            405 => 'method_not_allowed',
            422 => 'invalid',
            408 => 'request_timeout',
            419 => 'token_mismatch',
            500 => 'internal_server_error',
            503 => 'internal_server_error',
        ];

        return $httpStatus[$status] ?? 'unknown';
    }
}
