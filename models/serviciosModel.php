<?php
namespace Coppel\Tiendas\Models;
use Httpful\Request;
class ServiciosModel
{
	public function consutarCentrosServicio() {
		$di = \Phalcon\DI::getDefault();
		$urlCentros = $di->get("config")->centroServicio;
		$urlCentros .= "/api/centrosservicio";
		$response = Request::get($urlCentros)->send()->body;
		return $response;
	}
}