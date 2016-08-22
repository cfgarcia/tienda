<?php

namespace Coppel\Tiendas\Models;
use Phalcon\Mvc\Model as Modelo;

class TiendasModel extends Modelo {

  public function holaMundo() {
    $response = null;
    
    $di = \Phalcon\DI::getDefault();
    $db = $di->get('cartera');
    $statement = $db->prepare("SELECT 'hola mundo!' AS saludo, now() AS actual;");
    $statement->execute();
    while ($entry = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $resultSet = new \stdClass();
      $resultSet->saludo = $entry["saludo"];
      $resultSet->actual = $entry["actual"];
      $response = $resultSet;
      $resultSet = null;
    }

    return $response;
  }

  public function consultarTiendas() {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT idu_tienda,nom_tienda,opc_estatus,fec_actuliazada FROM cat_tiendas;");
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);

    return $response;
  }

  public function consultarTiendasPorID($numTienda) {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("SELECT idu_tienda,nom_tienda,opc_estatus,fec_actuliazada FROM cat_tiendas where idu_tienda = ?;");
    $statement -> bindParam(1,$numTienda,\PDO::PARAM_INT);
    $statement -> execute();
    $response = $statement -> fetchAll(\PDO::FETCH_ASSOC);
    return $response;
  }

  public function agregarTienda($tienda) {
    $response =array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("insert into cat_tiendas(nom_tienda,opc_estatus) values(:nomTienda,:opcEstatus);");
    $statement -> bindParam(':nomTienda',$tienda->nomTienda,\PDO::PARAM_INT);
    $statement -> bindParam(':opcEstatus',$tienda->opcEstatus,\PDO::PARAM_INT);
    $response = $statement -> execute();
    return $response;
  }

  public function borrarTienda ($iduTienda) {
    $response = array();
    $di = \Phalcon\DI::getDefault();
    $db = $di->get("conexion");
    $statement = $db -> prepare("DELETE FROM cat_tiendas where idu_tienda = ?");
    $statement -> bindParam(1,$iduTienda->iduTienda,\PDO::PARAM_INT);
    return $statement->execute();
  }

  public function actualizarTienda($tienda) {
    $response = array();
    $di = \Phalcon\DI::getDefault();
    $db = $di ->get("conexion");
    $statement = $db -> prepare("UPDATE cat_tiendas set nom_tienda=:nomTienda,opc_estatus=:opcEstatus where idu_tienda = :iduTienda;");
    $statement -> bindParam(':nomTienda',$tienda->nomTienda);
    $statement -> bindParam(':opcEstatus',$tienda->opcEstatus,\PDO::PARAM_INT);
    $statement -> bindParam(':iduTienda',$tienda->iduTienda,\PDO::PARAM_INT);
    return $statement->execute();
  }

  public function consultarSOA($pais,$estado,$ciudad,$colonia){
    $response = null;
    $di = \Phalcon\DI::getDefault();
    $wsdl = $di->get("config")->wsMantenimiento;

    $soapClient = new \SoapClient($wsdl);
    $response = $soapClient -> CodigoPostal(array("pais"=>$pais,"estado"=>$estado,"ciudad"=>$ciudad,"colonia"=>$colonia));
    return $response;
  }

  public function consultarZonasCalles($cNombre,$iflag,$iLimit,$iNumCiudad,$iOffset,$ipCartera) {
    $respueta = null;
    $di = \Phalcon\DI::getDefault();
    $wsdl = $di -> get("config")->wsZonasCalles;
    $soapClient = new \SoapClient($wsdl);
    $response = $soapClient->consultarCiudades(array("cnombreciudadconsultar"=>$cNombre,"iflaglike"=>$iflag,"ilimit"=>$iLimit,"inumciudadconsultar"=>$iNumCiudad,"ioffset"=>$iOffset,"ip_cartera"=>$ipCartera));
    return $response; 
  }
}