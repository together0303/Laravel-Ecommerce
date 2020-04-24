<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\CountryState;
use App\Models\Country;
use Str;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Models\User;
class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $cities = City::with('countryState')->get();
        $billingAddress = BillingAddress::with('country','countryState','city')->get();
        $shippingAddress = ShippingAddress::with('country','countryState','city')->get();
        $users = User::with('seller','city','state','country')->get();

        return response()->json(['cities' => $cities, 'billingAddress' => $billingAddress, 'shippingAddress' => $shippingAddress, 'users' => $users], 200);
    }


    public function create()
    {
        $countries = Country::all();
        return view('admin.create_city', compact('countries'));
    }


    public function store(Request $request)
    {
        $rules = [
            'country'=>'required',
            'state'=>'required',
            'name'=>'required|unique:cities'
        ];

        $customMessages = [
            'country.required' => trans('admin_validation.Country is required'),
            'state.required' => trans('admin_validation.State is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules,$customMessages);

        $city=new City();
        $city->country_state_id=$request->state;
        $city->name=$request->name;
        $city->slug=Str::slug($request->name);
        $city->status=$request->status;
        $city->save();

        $notification=trans('admin_validation.Created Successfully');
        return response()->json(['notification' => $notification], 200);
    }


    public function show($id)
    {
        $states = CountryState::with('cities','country')->get();
        $city = City::with('countryState')->find($id);
        $countries = Country::with('countryStates')->get();

        return response()->json(['states' => $states, 'city' => $city, 'countries' => $countries], 200);
    }

     public function edit($id)
    {
        $states = CountryState::all();
        $city = City::find($id);
        $countries = Country::all();
        return view('admin.edit_city', compact('states','city','countries'));
    }




    public function update(Request $request, $id)
    {
        $city = City::find($id);
        $rules = [
            'country'=>'required',
            'state'=>'required',
            'name'=>'required|unique:cities,name,'.$city->id
        ];
        $customMessages = [
            'country.required' => trans('admin_validation.Country is required'),
            'state.required' => trans('admin_validation.State is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name already exist'),
        ];
        $this->validate($request, $rules,$customMessages);

        $city->country_state_id=$request->state;
        $city->name=$request->name;
        $city->slug=Str::slug($request->name);
        $city->status=$request->status;
        $city->save();

        $notification=trans('admin_validation.Update Successfully');
        return response()->json(['notification' => $notification], 200);
    }


    public function destroy($id)
    {
        $city = City::find($id);
        $city->delete();
        $notification=trans('admin_validation.Delete Successfully');
        return response()->json(['notification' => $notification], 200);
    }

    public function changeStatus($id){
        $city = City::find($id);
        if($city->status==1){
            $city->status=0;
            $city->save();
            $message=trans('admin_validation.Inactive Successfully');
        }else{
            $city->status=1;
            $city->save();
            $message=trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }
}
