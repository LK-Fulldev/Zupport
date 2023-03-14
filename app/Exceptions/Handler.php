<?php

namespace App\Exceptions;

use Throwable;
use Carbon\Carbon;
use ArgumentCountError;
use App\Services\ApiCode;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Facade\Ignition\Exceptions\ViewException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        Log::debug($exception);

        if ($exception instanceof ConnectionException) {
            return response()->json([
                'message' => "cUrl Error"
            ], 503);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => "Not Found (page or other resource doesn't exist)"
            ], 404);
        }

        if ($exception instanceof ArgumentCountError) {
            return response()->json([
                'message' => 'Too few arguments or missing arguments, Try again'
            ], 400);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }
        if ($exception instanceof UnauthorizedException || $exception instanceof AuthorizationException) {
            return response()->json([
                'code' => 'ERROR_UNAUTHORIZE',
                'message' => $exception->getMessage(),
                'timestamp' => Carbon::now()->toDateTimeString(),
            ], 403);
        }

        if ($exception instanceof ValidationException) {
            foreach ($exception->errors() as $error) {
                return response()->json([
                    'code' => ApiCode::VALIDATION_ERROR,
                    'message' => implode(" ", $error),
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ], 400);
            }
        }

        if ($exception instanceof NotAllowedRuleException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => 'ERROR_KEY_NOT_ALLOWED',
                'timestamp' => Carbon::now()->toDateTimeString(),
            ], 400);
        }

        if ($exception instanceof ErrorException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => 'ERROR_INTERNAL_SERVER_ERROR',
                'timestamp' => Carbon::now()->toDateTimeString(),
            ], 500);
        }
    }


    /**
     *
     * @param mixed $exception
     * @return mixed
     */
    private function respondWithValidationError($exception)
    {
        return ResponseBuilder::asError(ApiCode::VALIDATION_ERROR)
            ->withData($exception->errors())
            ->withHttpCode(422)
            ->build();
    }
}
