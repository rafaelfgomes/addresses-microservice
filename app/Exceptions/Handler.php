<?php

namespace App\Exceptions;

use Exception;
use ReflectionException;
use BadMethodCallException;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FatalErrorException;

class Handler extends ExceptionHandler
{

    use ApiResponser;

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
        if ($exception instanceof HttpException) {

            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);

        }

        if ($exception instanceof ModelNotFoundException) {

            $model = $this->getModelName(strtolower(class_basename($exception->getModel())));
            $message = "Não existe(m) nenhum(a) $model cadastrado(s) com esse id";
            $code = Response::HTTP_NOT_FOUND;

            return $this->errorResponse($message, $code);

        }

        if ($exception instanceof AuthorizationException) {

            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);

        }

        if ($exception instanceof AuthenticationException) {

            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);

        }

        if ($exception instanceof ValidationException) {

            return $this->errorResponse($exception->validator->errors()->messages(), Response::HTTP_FORBIDDEN);

        }

        if ($exception instanceof ReflectionException ||
            $exception instanceof QueryException ||
            $exception instanceof BadMethodCallException ||
            $exception instanceof FatalErrorException) {

            return $this->errorResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        if (env('APP_DEBUG', false)) {

            return parent::render($request, $exception);

        }

        $this->errorResponse('Erro inesperado', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getModelName($name)
    {

        switch ($name) {
            case 'user':
                $modelName = 'usuário(s)';
                break;

            default:
                $modelName = $name;
                break;
        }

        return $modelName;

    }
    
}
