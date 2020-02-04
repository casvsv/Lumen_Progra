<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Cuenta;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;



class CuentaController extends BaseController
{
#Listar todos los clientes
    public function index(Request $request){
   		$cuentas = Cuenta::all();
   		return response()->json($cuentas, 200);
	}


#Listar cliente por cedula
	public function getCuenta(Request $request, $numero){
   		if ($request->isjson()){
   			$cuentas = Cuenta::where('numero', $numero)->get();
   			#if (!$cliente->isEmpty()){
   			if(empty($cuentas)){
    			$status = false;
				$info = 'Data is not listed successfully';
    		}
			else{
				$status = true;
				$info = 'Data is listed successfully';   			
			}
		return ResponseBuilder::result($status, $info, $cuentas);
   		}
   		else{
   				$status = false;
				$info = 'Unauthorized';
   		return ResponseBuilder::result($status, $info);
   		}
	}
}
