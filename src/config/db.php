<?php
/**
 * Clase de base de datos
 */
class database
{
  private $dbhost="localhost";
  private $dbuser="root";
  private $dbpass="";
  private $dbname="api";

  function getConection()
  {
    $dbconection = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass);
    $dbconection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbconection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //$dbconection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $dbconection;
  }
}
