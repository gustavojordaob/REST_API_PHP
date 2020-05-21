<?php
require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router("http://www.localhost:2000/REST_API/users");

$router->namespace("Source\Controllers");

$router->group("users");
$router->get("/{id}?","Users:index");
$router->post("/","Users:create");
$router->put("/{id}","Users:edit");
$router->delete("/{id}","Users:delete");
$router->put("/{id}/drink","Users:insertDrinkUser");

$router->group("login");
$router->post("/","Users:login");

$router->group("logout");
$router->post("/","Users:logout");

$router->group("ooops");
$router->get("/{errcode}", "Users:error");

$router->dispatch();

if($router->error()){
    $router->redirect("/ooops/{$router->error()}");
}