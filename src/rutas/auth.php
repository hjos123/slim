<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;

$app->post('/auth/login', function(Request $request, Response $response){
  $username = $request->getParam("username");
  $userpassword = $request->getParam("userpassword");
    try {
      $db = new database();
      $result = $db->getConection()->query("SELECT * FROM users WHERE username='$username' limit 1;");

      if ( $result->rowCount() > 0 )
      {
        $data = $result->fetchAll(PDO::FETCH_OBJ);
        if ( password_verify($userpassword, $data[0]->userpassword) )
        {
          $settings = $this->get('settings');
          $token = JWT::encode(['id' => $data[0]->id, 'username' => $data[0]->username], $settings['secret'], "HS256");
          return $response->withJson(["token" => $token, "user" => $data[0]], 200);
        }
        else
          return $response->withJson(['error' => "Error de contraseña"], 400);
      }
      else
        return $response->withJson(['error' => "No existe usuario."], 400);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});
