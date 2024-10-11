<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageItem;
use App\Models\User;
use Illuminate\Http\Request;

class PageItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // token =  1|YFa1bINHpNLR9GwxfUULaICXDj2qQLH4ovuEilST8f792407
        // $userId = request('userid');

        // $user = User::find($userId)->first();
        // $token = $user->createToken('front-end')->plainTextToken;
        // return response()->json([
        //     'token' => $token,
        // ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageitem = PageItem::find($id);

        if($pageitem)
        {
            $content = html_entity_decode($pageitem->description);
            $data = str_replace(['<pre>','</pre>'],'',$content);

            // $json_encoded = json_encode($content);

            return response()->json([
                'data' => $data,
                'message' => 'success',
                'status'    => 200
            ], 200);
        }

        return response()->json([
            'message' => 'empty',
            'status'  => '200'
        ], 200);
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
