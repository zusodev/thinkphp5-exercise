<?php


namespace app\web\controller;


use DateTime;
use think\Controller;
use think\Db;
use think\Request;
use function filter_var;
use function header;
use function is_string;
use function var_dump;
use const FILTER_VALIDATE_EMAIL;

class User extends Controller
{
    public function index()
    {
        $users = Db::query('SELECT * FROM users');
        $users = Db::table('users')->select();
        var_dump($users);
        die;
//        return view("index");
//        return $this->fetch();
    }

    public function create()
    {
        return $this->createUserView();
    }

    public function store()
    {
        $request = Request::instance();
        if (!$request->isPost()) {
            header('Location: /web/user/create');
            die;
        }

        $email = $request->post("email");
        $name = $request->post("name");

        if (empty($email) || !filter_var($request->post('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->createUserView($email, $name);
        }

        if (empty($name) || !is_string($name)) {
            return $this->createUserView($email, $name);
        }

        $user = [
            "email" => $email,
            "name" => $name,
        ];
        $now = (new DateTime())->format("Y-m-d H:i:s");
        $user["created_at"] = $now;
        $user["updated_at"] = $now;

        $result = Db::table("users")->insert($user);

        return $this->createUserView($email, $name, $result);
    }

    private function createUserView($email = '', $name = '', $result = false)
    {
        return $this->fetch('create', [
            'title' => 'create user',
            'email' => $email,
            'name' => $name,
            'success' => $result,
        ]);
    }
}
