<?php

namespace Nsingularity\GeneralModule\Foundation\Http\Controller\Api;

use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Services\HelperService\FileService;

class GeneralController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($messages, $status = true)
    {
        $response = [
            "status" => $status,
            "result" => $messages,
        ];

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return string
     * @throws CustomMessagesException
     */
    public function uploadImage(Request $request)
    {
        $image = $request->file("file") ? $request->file("file") : $request->input("file");
        $rule  = ["file" => "required|image_base64"];

        if ($image instanceof UploadedFile) {
            $rule = ["file" => "required|image|mimes:jpeg,png"];
        }

        customValidation($request->all(), $rule);

        $uploadService = new FileService();

        $pathDir      = 'images/temporary/' . (new DateTime())->format("Ymd");
        $pathFilename = $pathDir . '/' . time() . "_" . str_random(6);

        $width  = $request->input("width");
        $height = $request->input("height");
        $x      = $request->input("x");
        $y      = $request->input("y");

        $url = $uploadService->uploadImage($pathFilename, $image, $width, $height, $x, $y);

        return $this->response([
            "url" => $url."?".encrypt($url)
        ]);
    }

    /**
     * @param $file
     * @return string
     * @throws CustomMessagesException
     */
    protected function uploadImageTemporaryReturnUrl($file)
    {
        $image = $file;
        $rule  = ["file" => "required|image_base64"];

        if ($image instanceof UploadedFile) {
            $rule = ["file" => "required|image|mimes:jpeg,png"];
        } elseif (filter_var($file, FILTER_VALIDATE_URL)) {
            $rule = ["file" => "required"];
            $file = strtok($file, "?");
        }

        customValidation(["file" => $file], $rule);

        $uploadService = new FileService();

        $pathDir      = 'images/temporary/' . (new DateTime())->format("Ymd");
        $pathFilename = $pathDir . '/' . time() . "_" . str_random(6);

        return $uploadService->uploadImage($pathFilename, $image, 0, 0, 0, 0, 2000, 2000);
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file)
    {
        $uploadService = new FileService();

        $pathDir      = 'images/temporary/' . (new DateTime())->format("Ymd");
        $pathFilename = $pathDir . '/' . time() . "_" . str_random(6);

        return $uploadService->uploadImage($pathFilename, $file);
    }

    /**
     * @param UploadedFile $file
     * @param $folder
     * @return string
     */
    public function uploadFileSpecificFolder(UploadedFile $file, $folder)
    {
        $uploadService = new FileService();

        $pathDir      = $folder;
        $pathFilename = $pathDir . '/' . time() . "_" . str_random(6);

        return $uploadService->uploadImage($pathFilename, $file);
    }

    /**
     *
     */
    public function getFile()
    {
        $path     = implode("/", func_get_args());
        $path     = storage_path("app/" . $path);
        $fileName = $path;
        if (!file_exists($fileName)) {
            http_response_code(404);
            die();
        }

        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $fileName);
        header("Content-Disposition:inline;filename=$fileName");
        header("Content-Type: $type");
        readfile($path);
    }

    public function responsePagination($totalData, $totalPage, $data, $perPage = 50)
    {
        $perPage = (int)$perPage ? $perPage : 50;

        return $this->response(
            [
                "total_data"   => (int)$totalData,
                "total_page"   => (int)$totalPage,
                "current_page" => (int)(request()->input("page") ? request()->input("page") : 1),
                "per_page"     => (int)(request()->input("per_page") ? request()->input("per_page") : $perPage),
                "data"         => $data
            ]
        );
    }

    public function decodeRequest(Request $request, &$filter, &$sort, &$search)
    {
        $filter = $request->input("filter");
        $sort   = $request->input("sort");
        $search = $request->input("search");

        if ($filter && !is_array($filter)) {
            $filter = json_decode($filter, 1);
        }

        if ($sort && !is_array($sort)) {
            $sort = json_decode($sort, 1);
        }

    }
}
