<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UmkmRequest;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use App\Models\UmkmPhoto;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmkmController extends Controller
{
    use ApiTrait;
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
    public function store(UmkmRequest $request)
    {
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $dataUmkm = $credentials;
            unset($dataUmkm['photos']);
            $obj = Umkm::create([
                "name" => $credentials['name'],
                "description" => $credentials['description'],
                "address" => $credentials['address'],
                "city" => $credentials['city'],
                "province" => $credentials['province'],
                "owner_name" => $credentials['owner_name'],
                "contact" => $credentials['contact'],
            ]);
            $photo = [];
            foreach ($credentials['photos'] as $img) {
                $temp = new UmkmPhoto([
                    'name' => $img,
                ]);
                $photo[] = $temp;
            };
            // return $photo;
            $obj->photos()->saveMany($photo);
            DB::commit();
            return new UmkmResource($obj);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = Umkm::with(['photos'])->where('id', $id)->first();
        if (is_null($find)) {
            return $this->responseNotFound();
        }
        return new UmkmResource($find);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UmkmRequest $request, string $id)
    {
        $obj = Umkm::find($id);
        $credentials = $request->all();
        try {
            DB::beginTransaction();
            $obj->update($credentials);
            $photo = [];
            foreach ($credentials['photos'] as $img) {
                $temp = new UmkmPhoto([
                    'name' => $img,
                ]);
                $photo[] = $temp;
            };
            $obj->photos()->delete();
            $obj->photos()->saveMany($photo);
            DB::commit();
            return new UmkmResource($obj);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Umkm::find($id);
        try {
            DB::beginTransaction();
            $find->delete();
            DB::commit();
            return $this->responseNoContent();
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->responseNotAccept($th->getMessage());
        }
    }
}
