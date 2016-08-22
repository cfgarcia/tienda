<?php

use Coppel\RAC\Modules\IModule;
use Phalcon\Mvc\Micro\Collection;
use Katzgrau\KLogger\Logger;

class Module implements IModule {

  public function __construct() {

  }

  public function registerLoader($loader) {
  	$loader->registerNamespaces(array('Coppel\Tiendas\Controllers' => __DIR__.'/controllers/',
      'Coppel\Tiendas\Models' => __DIR__.'/models/'
      ), true);
  }

  public function getCollections() {
  	$collection = new Collection();
    $servicios = new Collection();
    $collection->setPrefix('/api')
    ->setHandler('\Coppel\Tiendas\Controllers\TiendasController')
    ->setLazy(true);

    $servicios->setPrefix('/api')
    ->setHandler('\Coppel\Tiendas\Controllers\ServiciosController.php')
    ->setLazy(true);

    $collection->get('/ejemplo','holaMundo');
    $collection->get('/ejemplo/nombre','regresarNombre');
    
    $collection->get('/tiendas/{numTienda}','consultarTiendasPorID');
    $collection->get('/tiendas','consultarTiendas');
    $collection->post('/tiendas','agregarTienda');
    $collection->delete('/tiendas','borrarTienda');
    $collection->put('/tiendas','actualizarTienda');
    $collection->get('/mantenimiento/paises/{pais}/estados/{estado}/ciudades/{ciudad}/colonias/{colonia}','consultarSOA');
    $collection->get('/zonasCalles/nombreCiudad/{cNombre}/flag/{iflag}/limit/{iLimit}/numCiudad/{iNumCiudad}/offset/{iOffset}/ip_cartera/{ipCartera}','consultarZonasCalles');

    $servicios->setPrefix('/api')
    ->setHandler('\Coppel\Tiendas\Controllers\ServiciosController')
    ->setLazy(true);
    $servicios->get('/reportes','exportarPDF');
    $servicios->get('/centrosservicios','consutarCentrosServicio');
    $servicios->get('/correos','enviarCorreo');
    //$collection->verbo('/ruta','metodo');
   

    return [$collection,$servicios];
  }

  public function registerServices() {
  	
    $di = Phalcon\DI::getDefault();
    
    $di->set('conexion', function() use ($di) { 
      $config = $di->get('config');
      $host = $config->db->host;
      $dbname = $config->db->dbname;
      return new \PDO("mysql:host=$host;dbname=$dbname",
         $config->db->username,
         $config->db->password,
         array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    });

    $di->set('cartera', function() use ($di) { 
      $config = $di->get('config');
      $host = $config->cartera->host;
      $dbname = $config->cartera->dbname;
      return new \PDO("pgsql:host=$host;dbname=$dbname",
         $config->cartera->username,
         $config->cartera->password
        );
    });

    $di->set('logger', function() use ($di) {
      return new Logger('logs');
    });

  }
}