<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        if ($exception instanceof \App\Exceptions\ImportDataException)  {
            return $exception->render($request);
        }

          // custom error message
        if ($exception instanceof \ErrorException && !$request->ajax()) {

            if ($request->ajax() || $request->wantsJson()) {
                $messageCatched = "Line: " .$exception->getLine(). ', Error: '. $exception->getMessage();
            return response()->json(
                                ['message' =>  $messageCatched],500
                            );
            }else{
                if (app()->environment() == 'production') {
                    $messageCatched = "Se produjo un conflicto interno con los archivos del sistema, comunícate con soporte técnico para resolver este problema";
                    return response()->view('errors.500',compact(["messageCatched"]));
                }else{
                    $messageCatched = "Line: " .$exception->getLine(). ', Error: '. $exception->getMessage();
                    return response()->view('errors.500',compact(["messageCatched"]));
                }
            }
             
            
            
        } else {
            
            return parent::render($request, $exception);
        }
        return parent::render($request, $exception);
    }
    

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
