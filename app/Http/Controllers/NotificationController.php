<?php

namespace App\Http\Controllers;

use App\Notification as AppNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request){
        $success = DB::table('notifications')
            ->where('notifiable_id',Auth::User()->id)
            ->where('notifiable_type','App\User')
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        return response(["success" => $success]);
    }

    public function list(Request $request){
        $view = view('layouts/header/navbar/notifications')->render();
        return response(["success" => true, 'view' => $view]);
    }

    public function destroy(Request $request, $notification){
        $response = DB::table('notifications')->where('id', $notification)->delete();
        return response(['success' => $response, "message" => !$response ? 'Error eliminando esta notificación': '']);
    }

    public function destroyAll(Request $request){
    $response = DB::table('notifications')->where('notifiable_id', Auth::User()->id)->delete();
        return response(['success' => $response, "message" => !$response ? 'Error eliminando las notificaciones': '']);
    }
}
