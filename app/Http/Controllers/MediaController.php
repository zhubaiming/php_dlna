<?php

namespace App\Http\Controllers;

use App\Http\Resources\wechatMiniApp\MediaCollection;
use App\Http\Resources\wechatMiniApp\MediaResource;
use App\Models\Media;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->whenHas('page', function (int $input) {
            $this->setOffset($input);
        });
        $request->whenHas('per_page', function (int $input) {
            $this->setLimit($input);
        });

        $data = new MediaCollection(Media::where(['cateId' => $request->query('cate_id')])->orderBy('id', 'asc')->simplePaginate(...$this->getPageArray()));

        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
//    public function show(string $id)
    public function show(Request $request)
    {
        try {
            $data = new MediaResource(Media::with('urls')->findOrFail($request->query('id')));

            return response()->json([
                'code' => 200,
                'message' => 'ok',
                'data' => $data
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'code' => -1,
                'message' => 'ok',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
