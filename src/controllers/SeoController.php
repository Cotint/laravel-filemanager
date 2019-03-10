<?php

namespace Unisharp\Laravelfilemanager\controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Unisharp\Laravelfilemanager\models\File;
use Illuminate\Http\Request;
use Unisharp\Laravelfilemanager\Services\FileService;

/**
 * Class SeoController.
 */
class SeoController extends LfmController
{
    protected $errors;

    public function __construct()
    {
        parent::__construct();
        $this->errors = [];
    }

    /**
     * @param Request $request
     */
    public function saveSeo(Request $request)
    {
        $item = $request->item;
        $image = DB::table('files')->where('name', $item)->first();
        if ($image) {
            DB::table('files')
                ->where('name', $item)
                ->update(['alt' => $request->alt, 'title' => $request->title]);
        } else {
            $image = new File();
            $image->name = $item;
            $image->size = FileService::getFileSize($item);
            $image->mime_type = FileService::getMimeType($item);
            $image->title = $request->title;
            $image->alt = $request->alt;
            $image->save();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getImage(Request $request)
    {
        $item = explode('http://'.$request->getHttpHost(), $request->item)[1];
        $image = DB::table('files')->where('name', $item)->first();
        if ($image) {
            $output['title'] = $image->title;
            $output['alt'] = $image->alt;
            $output['filepath'] = $image->name;

            return new JsonResponse($output);
        } else {
            $output['filepath'] = $item;

            return new JsonResponse($output);
        }
    }
}
