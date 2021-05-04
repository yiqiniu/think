<?php

namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\ErrorException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use yiqiniu\extend\facade\Logger;
use yiqiniu\extend\exception\ApiException;


/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {

        // 处理数据库的异常
        // 处理数据库的异常
        if (!($exception instanceof HttpResponseException)) {

            if(!($exception) instanceof ApiException){
                //不记录404的异常信息
                if (!method_exists($exception, 'getStatusCode') || $exception->getStatusCode() !== 404) {
                    Logger::exception($exception);
                }
            }
            //
            if ($exception instanceof ErrorException) {
                echo json_encode(['code' => API_ERROR, 'msg' => $exception->getMessage()], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
                exit;
            }
            api_result(API_ERROR, $exception->getMessage());
        }
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
