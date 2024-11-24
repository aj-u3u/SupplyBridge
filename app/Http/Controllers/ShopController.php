<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->query('size') ? $request->query('size') : 12;
        $o_column = "";
        $o_order = "";
        $order = $request->query('order') ? $request-> query('order') : -1;
        $f_brands = $request->query('brands') ? $request->query('brands') : '';
        switch ($order)
        {
            case 1:
                $o_column='created_at';
                $o_order='DESC';
                break;
            case 2:
                $o_column='created_at';
                $o_order='ASC';
                break;
            case 3:
                $o_column='sale_price';
                $o_order='ASC';
                break;
            case 4:
                $o_column='sale_price';
                $o_order='DESC';
                break; 
            default:
                $o_column='id';
                $o_order='DESC';

        }
       $brands= Brand::orderBy('name','ASC')->get();
        $products = Product::when($f_brands, function($query) use($f_brands) {
            return $query->whereIn('brand_id', explode(',', $f_brands));
        })
        ->orderBy($o_column, $o_order)
        ->paginate($size);
        return view('shop', compact('products','size','order','brands','f_brands'));
    }

   public function product_details($product_slug)
   {
    $product = Product::where('slug',$product_slug)->first();
    $rproducts = Product::where('slug','<>', $product_slug)->get()->take(8);  
    return view('details', compact('product','rproducts'));
   }
}
