<?php

namespace App\Http\Controllers;

use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use Illuminate\Http\Request;

class PublicUmkmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;
        $perPage = request()->per_page;
        $items = Umkm::oldest()->with(['photos']);
        if (!is_null($search)) {
            $items->search($search);
        }
        if (!is_null($perPage)) {
            return UmkmResource::collection($items->paginate($perPage));
        } else {
            return UmkmResource::collection($items->get());
        }
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
