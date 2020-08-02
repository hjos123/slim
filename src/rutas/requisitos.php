<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/requisitos', function(Request $request, Response $response){
    try {
      $db = new database();
      $result = $db->getConection()->query("SELECT * FROM requisitos");

      if ( $result->rowCount() > 0 )
        return $response->withJson($result->fetchAll(PDO::FETCH_OBJ), 200);
      else
        return $response->withJson(['status' => false, 'message' => "No existen datos"], 400);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['message' => $e->getMessage()], 400);
    }
});

$app->get('/requisitos/{id}', function(Request $request, Response $response){
    $id_requisito = $request->getAttribute("id");
    try {
      $db = new database();
      $result = $db->getConection()->query("SELECT * FROM requisitos WHERE id=".$id_requisito);

      if ( $result->rowCount() > 0 )
        return $response->withJson($result->fetchAll(PDO::FETCH_OBJ), 200);
      else
        return $response->withJson(['message' => "No existen datos",'code' => 202], 202);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['message' => $e->getMessage(), 'code' => 202], 202);
    }
});

$app->post('/requisitos/save', function(Request $request, Response $response){
    $descripcion = $request->getParam("descripcion");

    $sql = "INSERT INTO requisitos(descripcion) VALUES(
      '$descripcion');";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);

      $result->bindParam(':descripcion' , $descripcion);
      $result->execute();
      return $response->withJson(['message' => "Datos almacenados", "code" => 201], 201);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['message' => "Favor de validar los datos","errno" => $e->getMessage(), "code" => 202], 202);
    }
});

$app->put('/requisitos/edit', function(Request $request, Response $response){
    $id = $request->getParam("id");
    $descripcion = $request->getParam("descripcion");

    $sql = "UPDATE requisitos SET descripcion='$descripcion' WHERE id=$id";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);
      $result->execute();
      $result = $db = null;
      
	  return $response->withJson(['message' => 'Dato almacenado','code' => 201], 201);
    } catch (Exception $e) {
      return $response->withJson(['message' => $e->getMessage(), "code" => 202], 202);
    }
});

$app->delete('/requisitos/delete/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute("id");

    $sql = "DELETE FROM requisitos WHERE id=$id";
    try {
      $db = new database();
      $result = $db->getConection()->prepare($sql);
      $result->execute();

      if ($result->rowCount() > 0)
        return $response->withJson(['message' => "Dato eliminado",'code' => 200], 200);
      else
        return $response->withJson(['message' => "No proceso tu peticion.",'code' => 202], 202);

      $result = $db = null;
    } catch (Exception $e) {
      return $response->withJson(['message' => $e->getMessage(), "code" => 202], 202);
    }
});
