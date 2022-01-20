<?php

namespace App\Http\Controllers;
use App\Models\notification;
use Exception;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    //
    function storeNoti(Request $request){
        try{
            $noti = new notification;
            $noti->user_name = $request->user_name;
            $noti->title = $request->title;
            $noti->body = $request->body;
            $noti->status = $request->status;
            $noti->save();
            return response()->json([
                "status" => true,
                "message" => "Notification Generated"
            ],200);
        }
        catch (Exception) {
            return response()->json([
                "status" => false,
                "message" => "Notification not Generated"
            ],200);
        }
    }
    function getNoti($username){
        try{
            $data = notification::where('user_name', $username)->orderBy('created_at', 'desc')->get();
            return response()->json([
                "status" => true,
                "data" => $data
            ],200);
        }
        catch (Exception) {
            return response()->json([
                "status" => false,
                "message" => "Can't get Notifications"
            ],200);
        }
    }
    function viewedNotification($id){
        try{
            $data = notification::find($id);
            $data->status = 1;
            $data->update();
            return response()->json([
                "status" => true,
                "message" => 'notification viewed'
            ],200);
        }
        catch (Exception) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong"
            ],200);
        }
    }
}
