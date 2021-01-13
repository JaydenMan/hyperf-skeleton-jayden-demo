<?php


namespace App\Service;


class UserService
{

    public function __construct($config = [])
    {

    }

    public function getInfoById(int $id)
    {
        return ['id' => $id, 'name' => '李明', 'age' => '21'];
    }
}