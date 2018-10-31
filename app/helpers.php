<?php
/**
 * 自定义函数
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/10/31
 * Time: 10:59
 */

/**
 * @param string $msg
 * @param array $data
 * @param int $httpCode
 * @return \Illuminate\Http\JsonResponse
 */
function ajaxSuccess(string $msg = 'success', array $data = [], int $httpCode = 200)
{
    $return = [
        'status' => 1,
        'msg'    => $msg,
        'data'   => $data,
    ];
    return response()->json($return, $httpCode);
}

/**
 * @param string $errMsg
 * @param int $httpCode
 * @return \Illuminate\Http\JsonResponse
 */
function ajaxError(string $errMsg = 'error' ,int $httpCode = 200)
{
    $return = [
        'status' => 0,
        'msg'    => $errMsg
    ];
    return response()->json($return, $httpCode);
}