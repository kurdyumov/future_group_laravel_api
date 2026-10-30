<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (Throwable $e, Request $rq) {

            if ($e instanceof QueryException) {
                return response()->json([
                    'title'=>'Faield to perform SQL',
                    'status'=>400,
                    'detail'=>$e->errorInfo[2],
                    'instance'=>$rq->decodedPath(),
                ], 400);
            }
            else if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'title'=>'Route not found',
                    'status'=>404,
                    'detail'=>'Wrong API request route',
                    'instance'=>$rq->decodedPath()
                ], 404);
            }

            return response()->json([
                'title'=>'Failed to perform a request',
                'status'=>500,
                'detail'=>'Something occurred while performing API response',
                'instance'=>$rq->decodedPath()
            ], 500);
        });
    })->create();
