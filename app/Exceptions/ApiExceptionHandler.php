<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiExceptionHandler extends Exception
{

    /**
     * Handle the exception and provide a json response
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        if ($this->code == 0){
            $this->code = 404;
        } elseif ($this->code < 100) {
            $this->code = 500;
        }

        switch ($this->code) {
            case 400:
                $errorResponse = 'Bad Request';
                break;
            case 401:
                $errorResponse  = 'Unauthenticated';
                break;
            case 403:
                $errorResponse  = 'Forbidden';
                break;
            case 404:
                $errorResponse  = 'File Not Found';
                break;
            case 405:
                $errorResponse  = 'Method Not Allowed';
                break;
            default:
                $errorResponse  = ($this->code == 500) ? 'Something went wrong, Please try after some time.' : $this->getMessage();
                break;
        }


        return new JsonResponse($errorResponse, $this->code);
    }
}
