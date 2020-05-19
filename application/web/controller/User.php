<?php


namespace app\web\controller;

use app\web\model\User as UserModel;
use DateTime;
use MyModule\MyUserService;
use think\Controller;
use think\Request;
use function filter_var;
use function header;
use function is_string;
use function model;
use function view;
use const FILTER_VALIDATE_EMAIL;

class User extends Controller
{
    public function index()
    {
        $request = Request::instance();
        $model = model('User');
        $email = $request->get("email");
        if ($email) {
            $model->where("email", "LIKE", "%{$email}%");
        }
        $name = $request->get("name");
        if ($name) {
            $model->where("name", "LIKE", "%{$name}%");
        }

        $users = $model->select();


        return view("index", [
            "title" => "user list",
            "users" => $users,
            "email" => $email,
            "name" => $name,
        ]);
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

        $result = MyUserService::create([
            "email" => $email,
            "name" => $name,
        ]);

        return $this->createUserView($email, $name, $result);
    }

    public function edit()
    {
        $request = Request::instance();

        if (!$request->get("id")) {
            header('Location: /web/user/index');
            die;
        }
        $user = UserModel::where(["id" => $request->get("id")])
            ->find();

        if (empty($user)) {
            header('Location: /web/user/index');
            die;
        }


        return $this->updateUserView($user);
    }

    public function update()
    {
        $request = Request::instance();

        $id = $request->post("id");
        if (!$id) {
            header('Location: /web/user/index');
            die;
        }
        $user = UserModel::where(["id" => $request->post("id")])
            ->find();
        if (!$user) {
            header('Location: /web/user/index');
            die;
        }

        $email = $request->post("email");
        $name = $request->post("name");


        if (empty($email) || !filter_var($request->post('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->updateUserView($user);
        }

        if (empty($name) || !is_string($name)) {
            return $this->updateUserView($user);
        }

        $user = [
            "email" => $email,
            "name" => $name,
            "updated_at" => (new DateTime())->format("Y-m-d H:i:s"),
        ];
        UserModel::where(["id" => $id])
            ->update($user);

        $user = UserModel::where(["id" => $request->post("id")])
            ->find();

        return $this->fetch('edit', [
            "title" => "User Edit Page",
            "user" => $user,
            "success" => true,
        ]);
    }

    public function destory()
    {
        $request = Request::instance();

        if (!$request->post("id")) {
            header('Location: /web/user/index');
            die;
        }


        UserModel::where(["id" => $request->post("id")])
            ->delete();
        header('Location: /web/user/index');
        die;
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

    public function updateUserView($user, $result = false)
    {
        return $this->fetch('edit', [
            'title' => 'create user',
            'user' => $user,
            'success' => $result,
        ]);

    }
}
