<?php
	$I = new FunctionalTester($scenario);
	$I->wantTo("consultar todas las tiendas de la base de datos.");
	$I->sendGET("/tiendas");
	$I->seeResponseCodeIs(200, "Ver si la respuesta fue existosa.");
	$I->seeResponseJsonMatchesJsonPath("$.data.respuesta","verificar que se haya rescado informacion");
	$I->seeResponseIsJson();

	/*$obj = new stdClass();
	$obj ->nomTienda ="Zapata";
	$obj->opcEstatus = 1;
	$I->wantTo("insertar una tienda");
	$I->sendPOST("/tiendas",json_encode($obj));
	$I->seeResponseCodeIs(200,"ver si el insertar fue exitoso");
	$I->seeResponseIsJson();*/

	$objAct = new stdClass();
	$objAct->iduTienda = 5;
	$objAct ->nomTienda ="test";
	$objAct->opcEstatus = 1;
	$I->wantTo("actuliazar tienda");
	$I->sendPUT("/tiendas",json_encode($objAct));
	$I->seeResponseCodeIs(200,"ver si el Actulizado fue exitoso");
	$I->seeResponseIsJson();
	$I->seeResponseJsonMatchesJsonPath("$.data.respuesta","verificar que se haya rescado informacion");
	$temp = json_decode($I->grabResponse());
	$I->assertEquals(true,$temp->data->respuesta,"si la respuesta es true");

	/*$objDel = new stdClass();
	$objDel->iduTienda = 8;
	$I->wantTo("eliminar tienda");
	$I->sendDELETE("/tiendas",json_encode($objDel));
	$I->seeResponseCodeIs(200,"ver si el borrado fue exitoso");
	$I->seeResponseIsJson();
	$I->seeResponseJsonMatchesJsonPath("$.data.respuesta","verificar que se haya rescado informacion");
	$temp = json_decode($I->grabResponse());
	$I->assertEquals(true,$temp->data->respuesta,"si la respuesta es true");*/

	