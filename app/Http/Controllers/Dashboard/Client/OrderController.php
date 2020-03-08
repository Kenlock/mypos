<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Client;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateOrderRequest;
use App\Order;
use App\Product;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read_orders'])->only('index');
        $this->middleware(['permission:create_orders'])->only('create');
        $this->middleware(['permission:update_orders'])->only('edit');
        $this->middleware(['permission:delete_orders'])->only('destroy');

    }// end of construct

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }// end of index

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Client $client)
    {
        $categories = Category::with('products')->get();

        return view('dashboard.clients.orders.create', compact('client', 'categories'));

    }// end of create

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrderRequest $request, Client $client)
    {
        $order = $client->orders()->create([]);

        $order->products()->attach($request->products);

        $total_price = 0;

        foreach ($request->products as $id=>$quantity) {
            
            $product = Product::FindOrFail($id);
            $total_price += $product->sale_price * $quantity['quantity'];

            $product->update([

                'stock' => $product->stock - $quantity['quantity']
            
            ]);

        }// end of foreach

        $order->update([
            
            'total_price' => $total_price
        
        ]);

    }// end of store

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client, Order $order)
    {


    }// end of edit

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateOrderRequest $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Client $client, Order $order)
    {


    }// end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client, Order $order)
    {
       
    }

}// end of controller