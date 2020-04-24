<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductGallery;
use App\Models\Product;
use Illuminate\Http\Request;
use Image;
use File;
use Str;
class ProductGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index($productId)
    {
        $product = Product::find($productId);
        if($product){
            $gallery = ProductGallery::with('product')->where('product_id',$productId)->get();

            return response()->json(['product' => $product, 'gallery' => $gallery]);

        }else{
            $notification = trans('admin_validation.Something went wrong');
            return response()->json(['message' => $notification],400);
        }

    }

    public function store(Request $request)
    {
        $rules = [
            'images' => 'required',
            'product_id' => 'required',
        ];

        $customMessages = [
            'images.required' => trans('admin_validation.Image is required'),
            'product_id.required' => trans('admin_validation.Product is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = Product::find($request->product_id)->first();
        if($product){
            if($request->images){
                foreach($request->images as $index => $image){
                    $extention = $image->getClientOriginalExtension();
                    $image_name = 'Gallery'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
                    $image_name = 'uploads/custom-images/'.$image_name;
                    Image::make($image)
                        ->save(public_path().'/'.$image_name);
                    $gallery = new ProductGallery();
                    $gallery->product_id = $request->product_id;
                    $gallery->image = $image_name;
                    $gallery->save();
                }

                $notification = trans('admin_validation.Uploaded Successfully');
                return response()->json(['message' => $notification],200);
            }
        }else{
            $notification = trans('admin_validation.Something went wrong');
            return response()->json(['message' => $notification],400);
        }

    }


    public function destroy($id)
    {
        $gallery = ProductGallery::find($id);
        $old_image = $gallery->image;
        $gallery->delete();
        if($old_image){
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        $notification = trans('admin_validation.Delete Successfully');
        return response()->json(['message' => $notification],200);
    }

    public function changeStatus($id){
        $gallery = ProductGallery::find($id);
        if($gallery->status == 1){
            $gallery->status = 0;
            $gallery->save();
            $message = trans('admin_validation.Inactive Successfully');
        }else{
            $gallery->status = 1;
            $gallery->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
