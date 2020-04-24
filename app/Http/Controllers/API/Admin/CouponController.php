<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Setting;
class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $coupons = Coupon::orderBy('id','desc')->get();
        $setting = Setting::first();

        return response()->json(['coupons' => $coupons, 'setting' => $setting], 200);
    }

    public function store(Request $request){
        $rules = [
            'name'=>'required',
            'code'=>'required|unique:coupons',
            'number_of_time'=>'required|numeric',
            'offer_type'=>'required',
            'discount'=>'required|numeric',
            'status'=>'required',
            'expired_date'=>'required',
            'status'=>'required',
        ];
        $customMessages = [
            'code.required' => trans('admin_validation.Code is required'),
            'code.unique' => trans('admin_validation.Code already exist'),
            'name.required' => trans('admin_validation.Name is required'),
            'number_of_time.required' => trans('admin_validation.Number of time is required'),
            'offer_type.required' => trans('admin_validation.Offer type is required'),
            'discount.required' => trans('admin_validation.Discount is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'expired_date.required' => trans('admin_validation.Expired date is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->max_quantity = $request->number_of_time;
        $coupon->expired_date = $request->expired_date;
        $coupon->offer_type = $request->offer_type;
        $coupon->discount = $request->discount;
        $coupon->status = $request->status;
        $coupon->save();

        $notification=trans('admin_validation.Created Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function update(Request $request, $id){
        $rules = [
            'name'=>'required',
            'code'=>'required|unique:coupons,code,'.$id,
            'number_of_time'=>'required|numeric',
            'offer_type'=>'required',
            'discount'=>'required|numeric',
            'status'=>'required',
            'expired_date'=>'required',
            'status'=>'required',
        ];
        $customMessages = [
            'code.required' => trans('admin_validation.Code is required'),
            'code.unique' => trans('admin_validation.Code already exist'),
            'name.required' => trans('admin_validation.Name is required'),
            'number_of_time.required' => trans('admin_validation.Number of time is required'),
            'offer_type.required' => trans('admin_validation.Offer type is required'),
            'discount.required' => trans('admin_validation.Discount is required'),
            'status.required' => trans('admin_validation.Status is required'),
            'expired_date.required' => trans('admin_validation.Expired date is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $coupon = Coupon::find($id);
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->max_quantity = $request->number_of_time;
        $coupon->offer_type = $request->offer_type;
        $coupon->discount = $request->discount;
        $coupon->expired_date = $request->expired_date;
        $coupon->status = $request->status;
        $coupon->save();

        $notification=trans('admin_validation.Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function show($id){
        $coupon = Coupon::find($id);
        return response()->json(['coupon' => $coupon], 200);
    }

    public function destroy($id){
        $coupon = Coupon::find($id);
        $coupon->delete();
        $notification=trans('admin_validation.Delete Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function changeStatus($id){
        $coupon = Coupon::find($id);
        if($coupon->status == 1){
            $coupon->status = 0;
            $coupon->save();
            $message =  trans('admin_validation.Inactive Successfully');
        }else{
            $coupon->status = 1;
            $coupon->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

}
