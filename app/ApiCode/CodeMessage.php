<?php


namespace App\ApiCode;


class CodeMessage
{
    const OK=[10000,'执行成功'];
    const ErrorParameter=[10001,'缺少必要参数'];
    const ErrorFail=[10002,'网络错误，请重试'];
}
