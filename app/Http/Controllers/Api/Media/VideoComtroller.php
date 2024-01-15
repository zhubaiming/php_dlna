<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\Video\IndexRequest;
use App\Http\Resources\Api\VideoResource;
use App\Services\ModelService\Video;
use Illuminate\Http\Request;

class VideoComtroller extends Controller
{
    public function __construct(Video $video)
    {
        parent::__construct();

        $this->modelService = $video;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $validated = $request->validated();

        $list = $this->modelService->getByCate($validated['cate_id'] ?? 0)
            ->getByArea($validated['area_id'] ?? 0)
            ->getByYear($validated['year'] ?? 0)
            ->getByType($validated['type_id'] ?? 0)
            ->getByName($validated['search_key_word'] ?? null)
            ->getAllPaginate($validated['per_page'] ?? 10, $validated['page'] ?? 1);

        return $this->success(VideoResource::collection($list));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->success(new VideoResource($this->modelService->getOneById($id)));
    }
}
