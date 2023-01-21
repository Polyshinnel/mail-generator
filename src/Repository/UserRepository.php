<?php


namespace App\Repository;


use App\Models\User;

class UserRepository
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function checkUser(array $data) {
        $filter = [
            'name' => $data['name'],
            'password' => $data['pass']
        ];

        return $this->userModel->where($filter)->get()->toArray();
    }
}