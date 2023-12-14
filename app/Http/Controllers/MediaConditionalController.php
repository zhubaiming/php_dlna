<?php

namespace App\Http\Controllers;

use App\Models\SourcesArea;
use App\Models\SourcesCate;
use Illuminate\Http\Request;

class MediaConditionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = [];
        for ($i = (int)date('Y'); $i > 1999; $i--) {
            array_push($years, ['id' => $i, 'name' => (string)$i]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'ok',
            'data' => [
                'years' => $years,
                'areas' => SourcesArea::get(),
                'cates' => SourcesCate::cate()->with(['withType'])->get()
            ]
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
    public function show(string $id)
    {
        //
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
