<?php


namespace MyModule;

use app\web\model\User as UserModel;
use Carbon\Carbon;

class MyUserService
{
    public static function create(array $user)
    {
        $now = Carbon::now()->format("Y-m-d H:i:s");
        $user["created_at"] = $now;
        $user["updated_at"] = $now;

        return UserModel::insert($user);
    }

    public static function update()
    {

    }

}
