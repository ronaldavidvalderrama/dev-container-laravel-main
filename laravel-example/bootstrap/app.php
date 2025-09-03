<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonwhen(
            fn($request) =>$request->is('api/*' || $request->expectsJson())
        );


        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'metodo no permitido',
                'errors' => [],
            ], 405);
        });

        // No autenticado (401)
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No autenticado.',
                'errors'  => [],
            ], 401);
        });

        // No autorizado (403)
        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No autorizado.',
                'errors'  => [],
            ], 403);
        });

        // Modelo no encontrado (404)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            $model = class_basename($e->getModel());
            return response()->json([
                'status'  => 'error',
                'message' => "$model no encontrado.",
                'errors'  => [],
            ], 404);
        });

        // Ruta no encontrada (404)
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ruta no encontrada.',
                'errors'  => [],
            ], 404);
        });

        // Método no permitido (405)
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Método no permitido.',
                'errors'  => [],
            ], 405);
        });

        // Genérico
        $exceptions->render(function (\Throwable $e, $request) {
            $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            return response()->json([
                'status' => 'error',
                'message' => $status === 500 ? 'Error interno en el servidor' : $e->getMessage(),
                'errors' => [
                    'exception' => $e
                ]
            ], $status);
        });

    })->create();
