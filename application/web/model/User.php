<?php


namespace app\web\model;


use think\Model;

class User extends Model
{
    const TABLE = 'users';

    protected $table = self::TABLE;
}
