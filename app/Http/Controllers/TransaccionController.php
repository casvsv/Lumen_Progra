<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Cuenta;
use App\Transaccion;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;



class TransaccionController extends BaseController {
#Listar todos los clientes
    public function realizarTransaccion(Request $request){
   		if($request->json()){
   			$cuenta=Cuenta::where('numero',$request->numero)->first();
   			$transaccion=new Transaccion();
   			if ($cuenta != null){
   				$transaccion->fecha=$request->fecha;
   				$transaccion->tipo=$request->tipo;
   				$transaccion->valor=$request->valor;
   					if ($transaccion->tipo=='deposito'){   						
   						$cuenta->saldo=$cuenta->saldo+$transaccion->valor;
   						$cuenta->save();
   					}
   					else{
   						if ($transaccion->tipo=='retiro'){   							
   							if ($request->valor<=$cuenta->saldo){
   							$cuenta->saldo=$cuenta->saldo-$transaccion->valor;
   							$cuenta->save();
   							}
   							else{
   							$status = false;
   							$info = 'No cuenta con esa cantidad de dinero';
   							return ResponseBuilder::result($status, $info);
   							}
   						}
   						else{
   							$status = false;
   							$info = 'La transaccion no es valida';
   							return ResponseBuilder::result($status, $info);
   						}
   					}	
   					$transaccion->responsable='Yo';
   					$transaccion->descripcion=$request->descripcion;
   					$transaccion->cuenta_id=$cuenta->cuenta_id;
   					$transaccion->save();
   					$status = true;
   					$info = 'Transaccion realizada correctamente';
   					return ResponseBuilder::result($status, $info);
   			}
   			else{
   				$status = false;
				$info = 'La cuenta no existe';
   				return ResponseBuilder::result($status, $info);
   			}
   		}
   		else{
   			$status = false;
			$info = 'No autorizado';
   			return ResponseBuilder::result($status, $info);
   		}
	}

}
