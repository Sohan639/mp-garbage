<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        
        $this->renderable(function (QueryException $e, $request) {
            return response()->json(['status' => false, 'code'=>500, 'error' => $e->getMessage()], 500);
        });
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json(['status' => false, 'code'=>404, 'error' => $e->getMessage()], 404);
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(['status' => false, 'code'=>405, 'error' => $e->getMessage()], 405);
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json(['status' => false, 'code'=>404, 'error' => $e->getMessage()], 404);
        });
        
        /*  $this->renderable(function (Throwable $e, $request) {
            return response()->json(['success' => false, 'code'=>400, 'error' => $e->getMessage()], 400);
        });*/
    }
}
