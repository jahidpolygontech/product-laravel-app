<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveProductRequest;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get top 3 products based on quantity purchased
        $topProducts = Product::withCount([
            'orderItems as total_quantity' => function ($query) {
                $query->select(\DB::raw('COALESCE(SUM(quantity), 0)'));
            }
        ])
        ->orderByDesc('total_quantity')
        ->limit(3)
        ->get();

        // Get all products with pagination
        $products = Product::orderBy('created_at', 'desc')->paginate(5);

        return view('products.index', compact('products', 'topProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveProductRequest $request)
    {

       $product= Product::create($request->validated());

        return redirect()->route('products.index',$product)->with('status','Product Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
       return view('products.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveProductRequest $request, Product $product)
    {
     $product->update($request->validated());
     return redirect()->route('products.index',$product)->with('status','Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('status','Product deleted');
    }
}
