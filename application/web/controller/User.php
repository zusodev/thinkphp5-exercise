<?php


namespace app\web\controller;


use think\Controller;
use think\Db;

class User extends Controller
{
    public function index()
    {
//        return view("index");
        return $this->fetch();
    }
}
