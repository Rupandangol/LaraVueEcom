<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdminStoreRequest;
use App\Http\Requests\V1\AdminUpdateRequest;
use App\Http\Resources\V1\AdminCollection;
use App\Http\Resources\V1\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $array_filter=request()->only([
            'name',
            'email'
        ]);
        $admin=Admin::when(count($array_filter)>0,function($query) use ($array_filter){
            foreach($array_filter as $column =>$item){
                $query->where($column,'LIKE','%'.$item.'%');
            }
        })->paginate();
        return new AdminCollection($admin);   
        // return new AdminCollection(Admin::paginate());   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStoreRequest $request)
    {
        return new AdminResource(Admin::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return new AdminResource($admin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateRequest $request, string $id)
    {
        $admin=Admin::findOrFail($id);
        return $admin->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully'
        ], 200);
    }
}
