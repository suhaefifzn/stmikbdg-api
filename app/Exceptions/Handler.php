<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $exception)
    {
        $explodePath = explode('/', $request->path());

        if ($explodePath[0] === 'docs') {
            return redirect()->route('docs_home');
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'fail',
                'message' => 'URL tidak ditemukan.'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
