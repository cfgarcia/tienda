<?php

namespace Coppel\Tiendas\Controllers;
use Coppel\RAC\Controllers\RESTController;
use Coppel\RAC\Exceptions\HTTPException;
use Coppel\Tiendas\Models as Modelos;

class TiendasController extends RESTController {

  private $logger;
  private $modelo;

  public function onConstruct() {
      $this->logger = \Phalcon\DI::getDefault()->get('logger');
      $this->modelo = new Modelos\TiendasModel();
  }

  public function holaMundo() {

      $response = null;
      try {
          $response = $this->modelo->holaMundo();
      } catch(\Exception $ex) {
          $mensaje = utf8_encode($ex->getMessage());
          $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
          throw new \Coppel\RAC\Exceptions\HTTPException(
              'No fue posible completar su solicitud, intente de nuevo por favor.',
              500,
              array(
                  'dev' => $mensaje,
                  'internalCode' => 'SIE1000',
                  'more' => 'Verificar conexión con la base de datos.'
              )
          );
              
      }
    
      return $this->respond(["response" => $response]);
  }

  public function regresarNombre(){
    //var_dump($this->request->getQuery("nombre"));
    return $this->respond(["nombre"=>"christian","centro"=>"diseno"]);
  }

  public function consultarTiendas() {
    //die(var_dump(\Phalcon::DI->getDefaul("config")));
    $response = null;
    try{
      $response = $this->modelo->consultarTiendas();
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
          $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
          throw new \Coppel\RAC\Exceptions\HTTPException(
              'No fue posible completar su solicitud, intente de nuevo por favor.',
              500,
              array(
                  'dev' => $mensaje,
                  'internalCode' => 'SIE1000',
                  'more' => 'Verificar conexión con la base de datos.'
              )
          );

    }
    return $this->respond(["respuesta"=>$response]);
  }
  public function consultarTiendasPorID($numTienda=null) {
    $response = null;
    try{
      $response = $this->modelo->consultarTiendasPorID($numTienda);
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
          $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
          throw new \Coppel\RAC\Exceptions\HTTPException(
              'No fue posible completar su solicitud, intente de nuevo por favor.',
              500,
              array(
                  'dev' => $mensaje,
                  'internalCode' => 'SIE1000',
                  'more' => 'Verificar conexión con la base de datos.'
              )
          );

    }
    if(empty($response)){
      throw new \Coppel\RAC\Exceptions\HTTPException(
              'La tienda Solicitada no Existe.',
              500,array()
      );
    }
    return $this->respond(["respuesta"=>$response]);
  }
  public function agregarTienda() {
    $response = false;
    $tienda = null;
    try{
      $tienda = $this->request->getJsonRawBody();
      $response = $this->modelo->agregarTienda($tienda);
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
      $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
      throw new \Coppel\RAC\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respond(["respuesta"=>$response]);
  }

  public function borrarTienda() {
    $respuesta = false;
    $iduTienda = null;
    try{
      $iduTienda = $this->request->getJsonRawBody();
      $respuesta = $this->modelo->borrarTienda($iduTienda);
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
      $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
      throw new \Coppel\RAC\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respond(["respuesta"=>$respuesta]);
  }

  public function actualizarTienda() {
    $respuesta = false;
    try{
      $tienda = $this->request->getJsonRawBody();
      $respuesta = $this->modelo->actualizarTienda($tienda);
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
      $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
      throw new \Coppel\RAC\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    if(!$respuesta){
      throw new \Coppel\RAC\Exceptions\HTTPException(
              'La tienda Solicitada no Existe.',
              500,array()
      );
    }
    return $this->respond(["respuesta"=>$respuesta]);
  }

  public function consultarSOA($pais,$estado,$ciudad,$colonia){
    $response = null;
    try{
      $response = $this->modelo->consultarSOA($pais,$estado,$ciudad,$colonia);
    } catch(\Exception $ex){
      $mensaje = utf8_encode($ex->getMessage());
      $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
      throw new \Coppel\RAC\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respond(["respuesta"=>$response]);
  }
  public function consultarZonasCalles($cNombre,$iflag,$iLimit,$iNumCiudad,$iOffset,$ipCartera) {
    $respuesta = null;
    try{
      $respuesta = $this->modelo->consultarZonasCalles($cNombre,$iflag,$iLimit,$iNumCiudad,$iOffset,$ipCartera);
    } catch(\Exception $ex) {
      $mensaje = utf8_encode($ex->getMessage());
      $this->logger->error("[".__METHOD__ ."]"."Se lanzó la excepción ".$mensaje);
      throw new \Coppel\RAC\Exceptions\HTTPException(
          'No fue posible completar su solicitud, intente de nuevo por favor.',
          500,
          array(
              'dev' => $mensaje,
              'internalCode' => 'SIE1000',
              'more' => 'Verificar conexión con la base de datos.'
          )
      );
    }
    return $this->respond(["respuesta"=>$respuesta]);
  }
}