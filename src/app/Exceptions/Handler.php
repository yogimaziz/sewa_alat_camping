<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */


    public function render($request, Exception $exception)
    {
        if (env('APP_DEBUG')) {
            return parent::render($request, $exception);
        }
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpResponseException) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR; 
        }else if($exception instanceof MethodNotAllowedHttpException){
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
            $exception = 'HTTP_METHOD_NOT_ALLOWED';
        }else if($exception instanceof NotFoundHttpException){
            $status = Response::HTTP_NOT_FOUND;
            $exception = 'HTTP_NOT_FOUND';
        }else if($exception instanceof AuthorizationException){    
            $status = Response::HTTP_FORBIDDEN;
            $exception = 'HTTP_FORBIDDEN';
        }else if($exception instanceof \Doten\Exception\ValidationException && $Exception->getResponse()){
            $status = Response::HTTP_BAD_REQUEST;
            $exception = 'HTTP_BAD_REQUEST';
        }else if($exception){
            $exception = 'HTTP_INTERNAL_SERVER_ERROR';
        }   
        return response()->json([
            'success'   =>  false,
            'status'    =>  $status,
            'message'   =>  $exception
        ], $status);
    }

}
