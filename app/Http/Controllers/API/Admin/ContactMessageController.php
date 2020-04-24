<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Setting;
class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index(){
        $contactMessages = ContactMessage::all();
        $setting = Setting::first();
        return response()->json(['contactMessages' => $contactMessages, 'setting' => $setting]);
    }

    public function destroy($id){
        $contactMessage = ContactMessage::find($id);
        $contactMessage->delete();

        $notification = trans('admin_validation.Delete Successfully');
        return response()->json(['message' => $notification], 200);
    }

    public function handleSaveContactMessage(){
        $setting = Setting::first();
        if($setting->enable_save_contact_message == 1){
            $setting->enable_save_contact_message = 0;
            $setting->save();
            $message = trans('admin_validation.Disable Successfully');
        }else{
            $setting->enable_save_contact_message = 1;
            $setting->save();
            $message = trans('admin_validation.Enable Successfully');
        }
        return response()->json($message);
    }
}
