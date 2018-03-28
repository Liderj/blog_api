<?php

namespace App\Exceptions;

use App\MyTrait\ApiMessage;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    use ApiMessage;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
      // 参数验证错误的异常，
      if ($exception instanceof ValidationException ) {
        return $this->failed(array_first(array_collapse($exception->errors())));
      }
      // 用户认证的异常，
      if ($exception instanceof UnauthorizedHttpException) {
        if($exception->getCode() == 403){
          return  $this->failed($exception->getMessage(),'error',403);
        }
        return  $this->failed($exception->getMessage(),'error',405);
      }
      return parent::render($request, $exception);
    }
}
