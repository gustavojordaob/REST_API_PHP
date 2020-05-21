<?php
use Source\Models\Validations;
use Source\Models\User;

require_once __DIR__ . '../../config.php';
require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

class UserService
{
    private $token = token;
    
    function __construct()
    {
        isset($_SESSION["logado"]) ? $_SESSION["logado"] : $_SESSION["logado"] = false;
    }

    public function getAll()
    {
        $this->authenticatedToken();

        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        $users = new User();
        if($users->find()->Count()>0){
            $return = array();
            foreach($users->find()->fetch(true) as $user){
                //tratamento dos dados do banco
                array_push($return, $user->data());
            }
            header("HTTP/1.1 200 OK");
            echo json_encode(array("response" => $return));
        }else{
            echo json_encode(array("response" => "Nenhum usuario cadastrado!"));
        }
    }

    public function getById($userId)
    {
        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        
        $this->authenticatedToken();
        $return = array();

        $user = (new User())->findById($userId);
        if(!$user){
            header("HTTP/1.1 200 OK");
            echo json_encode(array("response" => "Nenhum usuario foi Localizado!"));
            exit;
        }

        array_push($return, $user->data());
        echo json_encode(array("response" => $return));
        header("HTTP/1.1 200 OK");
        exit;
    }

    public function create()
    {
        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        $this->authenticatedToken();

        $data = json_decode(file_get_contents("php://input"));

        $name = $data->name;
        $email = $data->email;

        $user = (new User())->find("email = '$email' OR name_user = '$name'")->fetch();

        if($user){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "Usuario ja cadastrado na base de dados"));
            exit;
        }
        
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
    }

    public function update($userId)
    {
        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        $this->authenticatedToken();

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
        if(!Validations::validationString(isset($data->name) ? $data->name : '')){
            array_push($erros, "Nome inválido");
        }

        if(!Validations::validationInt($userId)){
            array_push($erros, "id informado é inválido!");
        }

        if(!Validations::validationEmail(isset($data->email) ? $data->email : '')){
            array_push($erros, "Email inválido");
        }
        
        if(!Validations::validationPassword(isset($data->password) ? $data->password : '')){
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
    }

    public function delete($userId)
    {
        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        $this->authenticatedToken();

        
        if(!$userId){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "id não informado!"));
            exit;
        }

        if(!Validations::validationInt($userId)){
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(array("response" => "id inválido!"));
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
    }

    public function InsertDrink($userId){
        if(!$_SESSION["logado"]){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, você precisa esta logado!"));
            exit;
        }
        $this->authenticatedToken();

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
        if(!Validations::validationInt($userId)){
            array_push($erros, "id informado é inválido!");
        }

        if(!Validations::validationInt($data->drink_ml)){
            array_push($erros, "id informado é inválido!");
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

        $user->drink_counter += 1;
        $user->drink_ml += $data->drink_ml;
        $user->save();
    
        
        if($user->fail()){
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(array("response" => $user->fail()->getMessage()));
            exit;
        }

        header("HTTP/1.1 201 CREATED");
        echo json_encode(array("response" => "Usuario atualizado com sucesso!"));
    }

    public function authenicatedLogin(){
        $data = json_decode(file_get_contents("php://input"),false);
        $email = $data->email;
        $password = $data->password;

        $user = (new User())->find("email = '$email' AND password_user = '$password'")->fetch();
        $_SESSION["logado"] = true;
    
        if(!$user){
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Não autorizado, email ou senha incorreto!"));
            exit;
        }

        header('HTTP/1.0 200 OK');
        echo json_encode(array("response" => "Usuario logado com sucesso!"));


    }

    public function authenticatedToken(){
        $validated = $this->token == (isset($_SERVER["HTTP_TOKEN"]) ? $_SERVER["HTTP_TOKEN"] : '') ? TRUE : FALSE;

        if (!$validated) {
            header('HTTP/1.0 401 Unauthorized');
            echo json_encode(array("response" => "Token inválido!"));
            exit;
        }
    
    }
    public function logout(){
        $_SESSION["logado"] = false;
        header('HTTP/1.0 200 OK');
        echo json_encode(array("response" => "Logout!"));
        exit;
    }
}