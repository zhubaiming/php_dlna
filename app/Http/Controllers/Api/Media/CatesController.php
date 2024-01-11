<?php

namespace App\Http\Controllers\Api\Media;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Api\MediaCateResource;
use App\Services\ModelService\MediaCate;
use Illuminate\Http\Request;

class CatesController extends Controller
{
    public function __construct(MediaCate $mediaCate)
    {
        parent::__construct();

        $this->modelService = $mediaCate;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($type): \Illuminate\Http\JsonResponse
    {
        $method = 'get' . ucfirst($type) . 'AllNotPage';

        return $this->success(MediaCateResource::collection($this->modelService->{$method}())->groupBy('type')->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
