<?php

class RecentActivities extends Eloquent {

    protected $table="recent_activities";

    static function createActivity($message){
        $recent = new RecentActivities();
        $recent->message = $message;
        $recent->save();
    }
} 