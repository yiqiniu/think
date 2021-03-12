<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
return [
    // 默认日志记录通道
    'default'      => env('log.channel', 'file'),
    // 日志记录级别
    'level'        => ['debug','error','request'],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],
    // 关闭全局日志写入
    'close'        => false,
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        'file' => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => '',
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => [],
            // 最大日志文件数量
            'max_files'      => 10,
            // 使用JSON格式记录
            'json'           => false,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => false,
            // 日志输出格式化
            'format'         => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],
        'socket'=>[
            'host' => env('log.socket_host','127.0.0.1'),
            // socket服务器端口
            'port' => env('log.socket_port',1116),
            // 是否显示加载的文件列表
            'show_included_files' => env('log.socket_show_included_files',false),
            // 日志强制记录到配置的client_id
            'force_client_ids'    => [],
            // 限制允许读取日志的client_id
            'allow_client_ids'    => [],
            // 调试开关
            'debug'               => env('log.socket_debug',false),
            // 输出到浏览器时默认展开的日志级别
            'expand_level'        => [],

        ]
        // 其它日志通道配置
    ],

];
