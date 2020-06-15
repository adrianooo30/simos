<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\View;

use App\Employee;
use App\Notification;

use App\CustomClasses\NotificationGenerator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if request has count all unread notifications...
        if(request()->has('count_all_unread'))
            return [ 'all_notif_count' => auth()->user()
                                        ->unreadNotifications()
                                        ->count() ];

        // get notifications for current user
        $notifications = Employee::getNotifications();

        // return response
		return [
            'notifications_html' => View::make('includes.notifications.grouped-notifications', compact('notifications'))
                    ->render(),
            'notifications_count' => NotificationGenerator::getNotificationCounts(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        $notification->markAsRead();

        // switch()
        // {
        //     case '':
        //         return redirect()->route('');
        //     break;
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // marking as read
    public function update(Request $request, $notification)
    {
    	// mark as read...
    	// $notification->markAsRead();
        $notification = auth()->user()
                        ->unreadNotifications()
                        ->find($notification);

        $notification->markAsRead();

    	return [
            'notifications_html' => NotificationGenerator::createUiNotification($notification),
            'notifications_count' => NotificationGenerator::getNotificationCounts(),
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

    	return [
            'notifications_count' => NotificationGenerator::getNotificationCounts(),
        ];
    }
}
