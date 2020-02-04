<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Cuenta;
use App\Http\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;



class ClienteController extends BaseController
{
#Listar todos los clientes
    public function index(Request $request){
   		$clientes = Cliente::all();
   		return response()->json($clientes, 200);
	}
#Listar cliente por cedula
	public function getCliente(Request $request, $cedula){
   		if ($request->isjson()){
   			$cliente = Cliente::where('cedula', $cedula)->get();
   			#if (!$cliente->isEmpty()){
   			if(empty($cliente)){
    			$status = false;
				$info = 'Data is not listed successfully';
    		}
			else{
				$status = true;
				$info = 'Data is listed successfully';   			
			}
		return ResponseBuilder::result($status, $info, $cliente);
   		}
   		else{
   				$status = false;
				$info = 'Unauthorized';
   		return ResponseBuilder::result($status, $info);
   		}
	}

#Listar cliente por apellidos
   public function getClienteApellidos(Request $request, $apellido){
         if ($request->isjson()){
            $cliente = Cliente::where('apellidos', $apellido)->get();
            #if (!$cliente->isEmpty()){
            if(empty($cliente)){
            $status = false;
            $info = 'Data is not listed successfully';
         }
         else{
            $status = true;
            $info = 'Data is listed successfully';          
         }
      return ResponseBuilder::result($status, $info, $cliente);
         }
         else{
               $status = false;
            $info = 'Unauthorized';
         return ResponseBuilder::result($status, $info);
         }
   }

#Crear un cliente
	public function createCliente(Request $request){
   	$cliente = new Cliente();
   	$cuenta = new Cuenta();
   	$cliente->cedula = $request->cedula;
   	$cliente->nombres = $request->nombres;
   	$cliente->apellidos = $request->apellidos;
   	$cliente->genero = $request->genero;
   	$cliente->estado_civil = $request->estado_civil;
   	$cliente->fecha_nacimiento = $request->fecha_nacimiento;
   	$cliente->correo = $request->correo;
		$cliente->telefono = $request->telefono;
		$cliente->celular = $request->celular;
		$cliente->direccion = $request->direccion;		
   	$cliente->save();
   	#Generar número de cuenta
   	$Numero1=rand(9999999, 99999999);
		$Numero2=rand(9999999, 99999999);
		$Numero=$Numero1*$Numero2;
		#
   	$cuenta->numero = $Numero;
   	$cuenta->estado = '1';
   	$cuenta->fechaApertura = $request->fechaApertura;
   	$cuenta->tipoCuenta = $request->tipoCuenta;
   	$cuenta->saldo = $request->saldo;
   	$cuenta->cliente_id = $cliente->id;
   	$cuenta->save();
   		//return response()->json($cliente);
	}

#Modificar un cliente
   public function modifyCliente(Request $request, $cedula){
         $cliente = Cliente::where('cedula', $cedula)->first();
          if(empty($cliente)){
            $status = false;
            $info = 'Data is not in the list';
         }
         else{
            #Modificación de datos
            $cliente->nombres = $request->nombres;
            $cliente->apellidos = $request->apellidos;  
            $cliente->save();          
         }
   }

}
