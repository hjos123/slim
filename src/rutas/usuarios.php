<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/users', function(Request $request, Response $response){
    try {
      $db = new database();
      $result = $db->getConection()->query("SELECT id, name, app, username, status FROM users");  
      return $response->withJson($result->fetchAll(PDO::FETCH_OBJ), 200);
      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

$app->get('/users/{id}', function(Request $request, Response $response){
    $id_usuario = $request->getAttribute("id");
    try {
      $db = new database();
      $result = $db->getConection()->query("SELECT id, name, app, username, status FROM users WHERE id=".$id_usuario);

      if ( $result->rowCount() > 0 )
        return $response->withJson($result->fetchAll(PDO::FETCH_OBJ), 200);
      else
        return $response->withJson(['error' => "No existen datos"], 400);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

$app->post('/users/save', function(Request $request, Response $response){
    $nombre = $request->getParam("name");
    $apellido = $request->getParam("app");
    $username = $request->getParam("username");
    $userpassword = $request->getParam("userpassword");
    $userpassword = password_hash($userpassword, PASSWORD_DEFAULT, [15]);

    $sql = "INSERT INTO users(name,app,username,userpassword) VALUES(
      '$nombre','$apellido','$username','$userpassword');";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);

      $result->bindParam(':name' , $nombre);
      $result->bindParam(':app' , $apellido);
      $result->bindParam(':username' , $username);
      $result->bindParam(':userpassword' , $userpassword);
      $result->execute();
      return $response->withJson(['message' => "Datos almacenados"], 201);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['error' => "Favor de validar los datos / Usuario duplicado"], 400);
    }
});

$app->put('/users/edit', function(Request $request, Response $response){
    $id = $request->getParam("id");
    $nombre = $request->getParam("name");
    $apellido = $request->getParam("app");
    $status = $request->getParam("status");

    $sql = "UPDATE users SET name='$nombre', app='$apellido', status=$status WHERE id=$id";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);
      $result->execute();
      $result = $db = null;
      
	  return $response->withJson(['message' => "Dato almacenado"], 201);
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

$app->put('/users/resetpassword', function(Request $request, Response $response){
    $id = $request->getParam("id");
	$userpassword = password_hash("12345", PASSWORD_DEFAULT, [15]);
    
    $sql = "UPDATE users SET userpassword='$userpassword' WHERE id=$id";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);
      $result->execute();
      $result = $db = null;
      
	  return $response->withJson(['message' => "Dato almacenado"], 201);
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});

$app->delete('/users/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute("id");

    $sql = "DELETE FROM users WHERE id=$id";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);
      $result->execute();

      if ($result->rowCount() > 0)
        return $response->withJson(['message' => "Dato eliminado"], 200);
      else
        return $response->withJson(['error' => "No proceso tu peticion."], 400);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['error' => $e->getMessage()], 400);
    }
});
