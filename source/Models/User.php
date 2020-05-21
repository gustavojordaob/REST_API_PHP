<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

class User extends DataLayer{

    public function __construct()
    {
        parent::__construct("users",["email", "name_user", "password_user"],"id",false);
    }
}