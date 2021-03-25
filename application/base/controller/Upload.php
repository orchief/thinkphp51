<?php

// +----------------------------------------------------------------------
// | Description: 图片上传 (因为数据库路径转义问题  以下路径只考虑了windows系统 linux系统需要在使用路径的接口进行替换处理)
// +----------------------------------------------------------------------
// | Author: orchief
// +----------------------------------------------------------------------

namespace app\base\controller;

use think\Request;
use Utility\Controller;
use think\facade\Env;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * @route('base/upload')
 */
class Upload extends Controller
{
    public function save()
    {
        $file = request()->file('file');
        if($file){
            $files = [$file];
        }else{
            $files = request()->file('files');
        }

        continue_if($files, '请上传文件');
        // 获取表单上传文件
        $imgUrl = array();
        foreach ($files as $file) {
            $info = $file->move(Env::get('root_path') . '/public/uploads');
            continue_if($info, '文件上传失败');
            $object = 'uploads'. '/' . str_replace('\\', '/', $info->getSaveName());
            // $filePath = Env::get('root_path') . 'public' . '/' . 'uploads'. '/' . str_replace('\\', '/', $info->getSaveName());
            // $accessKeyId = Env::get('ALI_OSS.ALI_OSS_ACCESS_ID');
            // $accessKeySecret = Env::get('ALI_OSS.ALI_OSS_SECRET_KEY');
            // $endpoint = Env::get('ALI_OSS.ALI_OSS_ENDPOINT');
            // $bucket = Env::get('ALI_OSS.ALI_OSS_BUCKET');
            // $object = 'uploads/'.str_replace('\\', '/', $info->getSaveName());
            $imgUrl[] = $object;
            // try{
            //     $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            //     $ossClient->uploadFile($bucket, $object, $filePath);
            //     unlink($filePath);
            // } catch(OssException $e) {
            //     throw $e;
            // }
        }
        $url = Env::get('app.app_host');
        foreach ($imgUrl as $k=>$v){
            $imgUrl[$k] = $url.$v;
        }
        if($file){
            result($imgUrl[0]);
        }else{
            result($imgUrl);
        }
    }
}
