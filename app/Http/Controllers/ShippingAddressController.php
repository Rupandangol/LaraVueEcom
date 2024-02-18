<?php

namespace App\Http\Controllers;

use App\Http\Requests\V1\ShippingAddressStoreRequest;
use App\Http\Resources\V1\ShippingAddressResource;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShippingAddressStoreRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        return ShippingAddress::updateOrCreate(['user_id' => $data['user_id']], $data);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $userId=Auth::user()->id;
        $shippingAddress=ShippingAddress::where('user_id',$userId)->first();
        return new ShippingAddressResource($shippingAddress);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        //
    }
}
