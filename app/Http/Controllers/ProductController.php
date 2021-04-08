<?php

namespace App\Http\Controllers;

use App\Product;
use Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function getProduct()
    {
        error_log('Product');
        return Product::all();
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_description' => 'required',
            'product_price' => 'required',    
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        error_log('Rashmi was here');
        $product = Product::create($request->all());
        return response()->json([
            "message" => "product list created",
        ], 201);

    }

    public function getfilteredproduct(Request $request){
        
        $product=[];
        if ($request->p0to50 && $request->p50to100 && $request->p100to500) {
            $product= Product::all();
        } else if ($request->p0to50 && $request->p50to100) {
            $product = Product::where('product_price', '<=', 100)->get();
            
        } else if ($request->p0to50 && $request->p100to500) {
            $product =  Product::where('product_price', '<=', 50)
                            ->orWhere(function($query) {
                                $query->where('product_price', '>', 100)
                                    ->where('product_price', '<=', 500);
                                })->get();
            
        } else if ($request->p50to100 && $request->p100to500) {
            $product =  Product::where('product_price', '>', 50)
                            ->where('product_price', '<=', 500)
                            ->get();
        } else if ($request->p0to50) {
            $product =  Product::where('product_price', '<=', 50)
                            ->get();
        } else if ($request->p50to100) {
            $product =  Product::where('product_price', '>', 50)
                            ->where('product_price', '<=', 100)
                            ->get();
        } else if ($request->p100to500) {
            $product =  Product::where('product_price', '>', 100)
                            ->where('product_price', '<=', 500)
                            ->get();
        } else {
            $product =  Product::all();
        }
        
        return response()->json($product,201);
    }

}
