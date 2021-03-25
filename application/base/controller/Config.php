<?php

namespace app\base\controller;

use Utility\Controller;

/**
 * @route('base/config')
 */
class Config extends controller
{
    public $modelName = 'Config';

    /**
    * @OA\Get(
    *     path="/base/config",
    *     tags={"系统通用公开信息"},
    *     summary="获取全部信息",
    *     @OA\Parameter(
    *         name="name",
    *         in="query",
    *         description="参数名称 建议用大写下划线的风格",
    *         required=false,
    *         @OA\Schema(
    *             type="string",
    *             format="string",
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="value",
    *         in="query",
    *         description="配置值",
    *         required=false,
    *         @OA\Schema(
    *             type="integer",
    *             format="string",
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="remark",
    *         in="query",
    *         description="参数注释",
    *         required=false,
    *         @OA\Schema(
    *             type="string",
    *             format="string",
    *         )
    *     ),
    *     @OA\Response(
    *     response=200,
    *     description="用户留言列表",
    *     @OA\JsonContent(
    *     type="array",
    *     @OA\Items(
    *                   @OA\Property(
    *                       property="name",
    *                       description="参数名称 建议用大写下划线的风格",
    *                       type="string"
    *               ),
    *                   @OA\Property(
    *                       property="value",
    *                       description="配置值",
    *                       type="integer"
    *               ),
    *                   @OA\Property(
    *                       property="remark",
    *                       description="参数注释",
    *                       type="string"
    *               )
    *
    *     )
    *     )
    *     )
    * )
    */
    use \Rest\Index;
    use \Rest\Read;
}
