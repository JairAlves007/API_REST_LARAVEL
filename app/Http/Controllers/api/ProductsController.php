<?php

namespace App\Http\Controllers\api;

use App\Api\ApiError;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['data' => $this->product->paginate(10)];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $this->product->create($data);

            $return = [
                'data' => [
                    'message' => 'Product has been created.'
                ]
            ];

            return response()->json($return, 201);
        } catch (\Exception $e) {

            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }

            return response()->json('Houve Um Erro Ao Criar Um Produto', 1010);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->findOrFail($id);

        if(!$product) return response()->json(['data' => ['message' => 'Product is not found.']], 404);

        $data = ['data' => $id];

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();

            $product = $this->product->findOrFail($id);

            $product->update($data);

            $return = [
                'data' => [
                    'message' => 'Product has been updated.',
                    'product' => $product
                ]
            ];

            return response()->json($return, 201);

        } catch (\Exception $e) {

            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }

            return response()->json(ApiError::errorMessage('Houve Um Erro Ao Atualizar Esse Produto', 1010), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $product = $this->product->findOrFail($id);

            if(!$product) return response()->json(['data' => ['message' => 'Product is not found.']], 404);
            
            $productName = $product['name'];

            $product->delete();

            $return = [
                'data' => [
                    'message' => "The Product {$productName} has been deleted."
                ]
            ];

            return response()->json($return, 201);

        } catch (\Exception $e) {

            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010));
            }

            return response()->json('Houve Um Erro Ao Deletar Esse Produto', 1010);
        }
    }
}
