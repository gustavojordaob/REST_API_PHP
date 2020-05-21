<?php

namespace Source\Controllers;

require_once __DIR__ . '../../Services/UsersService.php';

use UserService;

class Users
{
    private $userService;
    
    public function index($data){
        $userService = new UserService();
        if(isset($data["id"])){
            $userService->getById($data["id"]);
        }

        $userService->getAll();
    }

    public function create(){
        $userService = new UserService();
        $userService->create();
    }

    public function edit($data){
        $userService = new UserService();
        $userService->update($data['id']);
    }

    public function delete($data){
        $userService = new UserService();
        $userService->delete($data['id']);
    }

    public function insertDrinkUser($data){
        $userService = new UserService();
        $userService->InsertDrink($data['id']);
    }

    public function login(){
        $userService = new UserService();
        $userService->authenicatedLogin();
    }

    public function logout(){
        $userService = new UserService();
        $userService->logout();
    }

    public function error($data){
        echo "<h1>Não existe esse endeço</h1>";
        var_dump($data);
    }
}

/*switch($_SERVER["REQUEST_METHOD"]){
    case "POST";
        $data = json_decode(file_get_contents("php://input"));
        if(!$data){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "Nenhum dado informado"));
            exit;
        }

        $erros = array();

        if(!Validations::validationString($data->name)){
            array_push($erros,"Nome informado é invalido");
        }

        if(!Validations::validationEmail($data->email)){
            array_push($erros,"Email informado é invalido");
        }

        if(!Validations::validationPassword($data->password)){
            array_push($erros,"Email informado é invalido");
        }

        if(count($erros) > 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "Á campos invalidados no formulario!",
            "fields" => $erros));
            exit;
        }

        $user = new User();
        $user->name_user = $data->name;
        $user->email = $data->email;
        $user->password_user = $data->password;
        $user->save();

        if($user->fail()){
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("response" => $user->fail()->getMessage()));
            exit;
        }

        header("HTTP/1.1 200 CREATED");
        echo json_encode(array("response" => "Usuario inserido com sucesso!"));

    break;
    case "GET":
        header("HTTP/1.1 200 OK");
        $users = new User();
        if($users->find()->Count()>0){
            $return = array();
            foreach($users->find()->fetch(true) as $user){
                //tratamento dos dados do banco
                array_push($return, $user->data());
            }
            echo json_encode(array("response" => $return));
        }else{
            echo json_encode(array("response" => "Nenhum usuario cadastrado!"));
        }
    break;
    case "PUT":
        $userId = filter_input(INPUT_GET,"id");
        
        if(!$userId){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "id não informado!"));
            exit;
        }
        $data = json_decode(file_get_contents("php://input"),false);
        if(!$data){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "nenhum dado informado!"));
            exit;
        }

        $erros = array();
        if(!Validations::validationString($data->name)){
            array_push($erros, "Nome inválido");
        }

        if(!Validations::validationInt($userId)){
            array_push($erros, "id informado é inválido!");
        }

        if(!Validations::validationEmail($data->email)){
            array_push($erros, "Email inválido");
        }
        
        if(!Validations::validationPassword($data->password)){
            array_push($erros,"Senha invalida!");
        }

        if(count($erros) > 0){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "Á campos invalidados no formulario!",
            "fields" => $erros));
            exit;
        }
       
        $user = (new User())->findById($userId);

        if(!$user){
            header("HTTP/1.1 200 OK");
            echo json_encode(array("response" => "Nenhum usuario foi Localizado!"));
            exit;
        }

        $user->name_user = $data->name;
        $user->email = $data->email;
        $user->password_user = $data->password;
        $user->save();
    
        
        if($user->fail()){
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("response" => $user->fail()->getMessage()));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response" => "Usuario atualizado com sucesso!"));
        
    break;
    case "DELETE" :
        $userId = filter_input(INPUT_GET,"id");
        if(!$userId){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "id não informado!"));
            exit;
        }

        if(!Validations::validationInt($userId)){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "id inválido!",
            "fields" => $erros));
            exit;
        }

        $user = (new User())->findById($userId);
        if(!$user){
            header("HTTP/1.1 200 OK");
            echo json_encode(array("response" => "Nenhum usuario foi Localizado!"));
            exit;
        }

        $user->destroy();
        if($user->fail()){
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("response" => $user->fail()->getMessage()));
            exit;
        }

        header("HTTP/1.1 200 OK");
        echo json_encode(array("response" => "Usuario removido com sucesso!"));
        
    break;
    default:
        header("HTTP/1.1 401 Unauthorized");
        echo json_encode(array("response" => "Método não encontrado"));
    break;
}*/
