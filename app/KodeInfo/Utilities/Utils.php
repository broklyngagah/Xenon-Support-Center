<?php

namespace KodeInfo\Utilities;

use Config;
use DB;

class Utils
{

    static function isBackendUser($user_id)
    {
        return self::inGroup('admin', $user_id) || self::inGroup('operator', $user_id) || self::inGroup('department-admin', $user_id);
    }

    static function isDepartmentAdmin($user_id)
    {
        return self::inGroup('department-admin', $user_id);
    }

    static function isAdmin($user_id)
    {
        return self::inGroup('admin', $user_id);
    }

    static function isOperator($user_id)
    {
        return self::inGroup('operator', $user_id);
    }

    static function inGroup($group_name_or_id, $user_id)
    {
        $users_groups_table = Config::get("user-management::users_groups_table");
        $groups_table = Config::get("user-management::groups_table");

        if (is_integer($group_name_or_id)) {
            $groups = DB::table($users_groups_table)->where("user_id", $user_id)->where("group_id", $group_name_or_id)->get();
        } else {
            $group = DB::table($groups_table)->where("name", $group_name_or_id)->first();
            $groups = DB::table($users_groups_table)->where("user_id", $user_id)->where("group_id", $group->id)->get();
        }

        if (sizeof($groups) > 0) {
            return true;
        }

        return false;
    }

    static function prettyDate($date,$time=true) {
        $format = $time ? "F jS, Y H:i:s" : "F jS, Y";
        return date($format,strtotime($date));
    }

    public static function decodePermissions($permissions)
    {

        if (strlen($permissions) <= 0 || is_null($permissions) || empty($permissions)) {
            return 'NONE';
        }

        $permissions = (array)json_decode($permissions);

        $wherein = array_keys($permissions);

        if (sizeOf($wherein) > 0) {
            $permissions = \Permissions::whereIn('key', $wherein)->lists('text');
            return implode('</br>', $permissions);
        } else {
            return "NONE";
        }

    }


    public static function fileUpload($file, $folder)
    {

        $timestamp = time();
        $ext = $file->getClientOriginalExtension();
        $name = $timestamp . "_file." . $ext;

        if (!\File::exists(public_path() . '/uploads/' . $folder)) {
            \File::makeDirectory(public_path() . '/uploads/' . $folder);
        }

        // move uploaded file from temp to uploads directory
        if ($file->move(public_path() . '/uploads/' . $folder . '/', $name)) {
            return '/uploads/' . $folder . '/' . $name;
        } else {
            return false;
        }
    }

    public static function imageUpload($file, $folder)
    {

        $timestamp = time();
        $ext = $file->guessClientExtension();
        $name = $timestamp . "_photo." . $ext;

        if (!\File::exists(public_path() . '/uploads/' . $folder)) {
            \File::makeDirectory(public_path() . '/uploads/' . $folder);
        }

        // move uploaded file from temp to uploads directory
        if ($file->move(public_path() . '/uploads/' . $folder . '/', $name)) {
            return '/uploads/' . $folder . '/' . $name;
        } else {
            return false;
        }
    }

    public static function buildMessages($messages)
    {

        $response = "";

        foreach ($messages as $message) {
            $response .= "<li>{$message}</li>";
        }

        return $response;
    }

    public static function timestamp()
    {
        $dt = new \DateTime;
        return $dt->format('Y-m-d H:i:s');
    }

    static function object_to_array($obj)
    {
        if (is_object($obj)) $obj = (array)$obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = Utils::object_to_array($val);
            }
        } else $new = $obj;
        return $new;
    }

    public static function generatePassword($length = 9, $strength = 4)
    {
        $vowels = 'aeiouy';
        $consonants = 'bcdfghjklmnpqrstvwxz';
        if ($strength & 1) {
            $consonants .= 'BCDFGHJKLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEIOUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

} 