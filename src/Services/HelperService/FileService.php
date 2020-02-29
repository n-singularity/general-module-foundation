<?php

namespace Nsingularity\GeneralModul\Foundation\Services\HelperService;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class FileService
{
    /**
     * @param $pathFilename
     * @param $image
     * @param int $width
     * @param int $height
     * @param int $x
     * @param int $y
     * @param int $rzWidth
     * @param int $rzHeight
     * @return string
     */
    public function uploadImage($pathFilename, $image, $width = 0, $height = 0, $x = 0, $y = 0, $rzWidth = 0, $rzHeight = 0)
    {
        /** @var Image $img */
        $img = ImageManagerStatic::make($image);

        //croping
        if ($width && $height) {
            $img->crop($width, $height, $x, $y);
        }

        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        if ($rzWidth && $img->width() > $rzWidth) {
            $img->resize($rzWidth, null, function ($constraint) {
                /**@var Constraint $constraint */
                $constraint->aspectRatio();
            });
        }

        if ($rzHeight && $img->height() > $rzHeight) {
            $img->resize(null, $rzHeight, function ($constraint) {
                /**@var Constraint $constraint */
                $constraint->aspectRatio();
            });
        }

        $mime = $img->mime();  //edited due to updated to 2.x

        if ($mime == 'image/jpeg')
            $extension = 'jpg';
        elseif ($mime == 'image/png')
            $extension = 'png';
        elseif ($mime == 'image/gif')
            $extension = 'gif';
        else
            $extension = '';

        $img          = $img->encode($extension);
        $pathFilename = $pathFilename . "." . $extension;

        if (env("FILESYSTEM_DRIVER") == "s3") {
            Storage::disk("s3")->put($pathFilename, $img->__toString());
            return Storage::disk("s3")->url($pathFilename);
        } else {
            Storage::put($pathFilename, $img->__toString());
            return url()->to(env("FILE_URL", "file-content") . "/" . $pathFilename);
        }
    }

    public function uploadImageWithAes($pathFilename, $image, $width = 0, $height = 0, $x = 0, $y = 0, $rzWidth = 0, $rzHeight = 0){
        $url = $this->uploadImage($pathFilename, $image, $width, $height, $x, $y, $rzWidth, $rzHeight);
        return $url."?".encrypt($url);
    }


    public function uploadPdf($pathFilename, $fileOutput)
    {
        if (env("FILESYSTEM_DRIVER") == "s3") {
            Storage::disk("s3")->put($pathFilename, $fileOutput);
            return Storage::disk("s3")->url($pathFilename);
        } else {
            Storage::put($pathFilename, $fileOutput);
            return url()->to(env("FILE_URL", "file-content") . "/" . $pathFilename);
        }
    }

    public function uploadXls($pathFilename, $fileOutput)
    {
        if (env("FILESYSTEM_DRIVER") == "s3") {
            Storage::disk("s3")->put($pathFilename, $fileOutput);
            return Storage::disk("s3")->url($pathFilename);
        } else {
            Storage::put($pathFilename, $fileOutput);
            return url()->to(env("FILE_URL", "file-content") . "/" . $pathFilename);
        }
    }

    public function base64ToFile($base64)
    {
        $img  = $base64;
        $img  = str_replace('data:image/png;base64,', '', $img);
        $img  = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        return $data;
    }
}
