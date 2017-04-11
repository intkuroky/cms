<?php
/**
 * Created by PhpStorm.
 * User: WangSF
 * Date: 2017/1/2
 * Time: 22:32
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fileUpload(Request $request)
    {

        $filesUploadResult = true;
        $filesUploadResultPath = [];

        if ( ! empty($files = $request->file())) {
            foreach ($files as $key => $file) {
                $originalName      = $file->getClientOriginalName();
                $uploadResult      = \Storage::disk('uploads')->put($originalName,
                    file_get_contents($file->getRealPath()));
                $filesUploadResult = $uploadResult ? $filesUploadResult : false;
                if ($uploadResult) {
                    $filesUploadResultPath[$key]    = asset('uploads/'.$originalName);
                }
            }
        }
        if($filesUploadResult){
            return responseSuccess('文件上传成功', $filesUploadResultPath);
        }
        return responseError('文件上传失败');
    }

}