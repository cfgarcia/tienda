<?php
namespace Coppel\Tiendas\Controllers;
use Coppel\RAC\Controllers\RESTController;
use Coppel\RAC\Exceptions\HTTPException;
use Coppel\Tiendas\Models as Modelos;
use tcpdf\TCPDF;
class ServiciosController extends RESTController
{
	private $logger;
	private $modelo;

	public function onConstruct() {
	$this->logger = \Phalcon\DI::getDefault()->get('logger');
	$this->modelo = new Modelos\serviciosModel();
	$this->modeloTiendas = new Modelos\TiendasModel();
	}
	public function consutarCentrosServicio() {
		$response = null;
		try{
			$response = $this->modelo->consutarCentrosServicio();
		}catch(\Exception $ex){
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

	public function exportarPDF() {
		$datos= $this->modeloTiendas->consultarTiendas();
		
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
		$pdf ->SetPrintHeader(false);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->setFont('helvetica','',12);
		$pdf->AddPage('P','A4');
		$html = 
			<<<EOD
				<div>
					<table border="1">
						<thead style="background-color:yellow;">
							<tr>
								<th>id</th>
								<th>nombre tienda</th>
								<th>estatus</th>
								<th>fecha actulizada</th>
							</tr>
						</thead>	
						<tbody>

EOD;
		foreach ($datos as $key => $value) {
			$html .= "<tr><td>".$value["idu_tienda"]."</td><td>".$value["nom_tienda"]."</td><td>".$value["opc_estatus"]."</td><td>".$value["fec_actuliazada"]."</td></tr>";
		}
		$html .= "</tbody></table></div>";
		$pdf -> writeHTMLCell(0,0,'','',$html,0,1,0,true,'',true);
		$fileName = 'primerPDF.pdf';
		$filePath = __DIR__ ."/../descargas/$fileName";

		$pdf -> Output($filePath,'F');
		$ipServer = $_SERVER['SERVER_NAME'];
		$response ="http://".$ipServer."/cursophp/tiendas/descargas/".$fileName;
		return $this->respond(["nombreArchivo"=>$response]);				
	}

	public function enviarCorreo() {
		$mailSender=\Phalcon\DI::getDefault()->get("mail");
		$response =  $mailSender->sendMail(array("cfgarcia@coppel.com"),array(),"cfgarcia@coppel.com","Pndsp506897","prueba","cuerpo");
		return $this->respond(["envio"=>$response]);
	}
}