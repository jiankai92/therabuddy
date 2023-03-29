<?php

namespace App\Services\Ajax;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AjaxResponseService
{
    protected int $code;
    protected $body;
    protected string $message;
    protected $pagination;

    public function __construct()
    {
        $this->code = 200;
        $this->body = null;
        $this->message = 'OK';
        $this->pagination = null;
    }

    public function setCode($code): static
    {
        $this->code = $code;
        return $this;
    }

    public function setBody($body): static
    {

        if (is_object($body) && get_class($body) == LengthAwarePaginator::class) {
            $body = $body->items();
        }

        $this->body = $body;
        return $this;
    }

    public function setMessage($message): static
    {
        $this->message = $message;
        return $this;
    }

    public function setError($error_message, $http_code): static
    {
        $this->message = $error_message;
        $this->code = $http_code;
        return $this;
    }

    public function setPagination($body): static
    {

        $this->pagination = [
            'current_page' => $body->currentPage(),
            'last_page' => $body->lastPage(),
            'path' => $body->getOptions()['path'],
            'per_page' => $body->perPage(),
            'total_items' => $body->total()
        ];

        return $this;
    }

    public function send(): \Illuminate\Http\JsonResponse
    {

        $body = [
            'code' => $this->code,
            'message' => $this->message,
            'body' => $this->body,
            'timestamp' => Carbon::now('UTC')->toIso8601String()
        ];

        if (!empty($this->pagination)) {
            $body['pagination'] = $this->pagination;
        }

        return response()->json(
            $body,
            $this->code
        );

    }
}