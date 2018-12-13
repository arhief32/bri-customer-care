<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponseCode extends Model
{
    public static function success()
    {
        return response()->json([
            'status' => '200',
            'message' => 'success',
        ]);
    }

    public static function userExist($data)
    {
        return response()->json([
            'status' => '200',
            'message' => 'User is exist',
            'data' => $data,
        ]);
    }
}
