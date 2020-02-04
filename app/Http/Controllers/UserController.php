<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Hashsing\BcryptHasher;
use App\Http\Helper\ResponseBuilder;
use Laravel\Lumen\Routing\Controller as BaseController;



class UserController extends BaseController {

#Iniciar sesión   
   public function login(Request $request){
      $username = $request->username;
      $password = $request->password; 

      $user = User::where('username',$username)->first(); 
      if(!empty($user)){
         if($this->django_password_verify($password,$user->password)){
            $status = true; 
            $info =  "User is correct";
         }
         else {
            $status = false; 
            $info =  "User is not correct";
         }
      } else {
         $status = false; 
         $info =  "User is not correct";
      }
      return ResponseBuilder::result($status, $info);
   }

#Desencriptar la contraseña
   public function django_password_verify(string $password, string $djangoHash): bool{
      $pieces = explode ('$', $djangoHash);
      if (count($pieces) !== 4){
         throw new Exception("Illegal hash format");
      }
      list($header,$iter,$salt,$hash) = $pieces;
      //Get the Hash algorithm used:
      if (preg_match('#^pbkdf2_([a-z0-9A-Z]+)$#',$header, $m)){
         $algo = $m[1];
      } else {
         throw new Exception("Bad header (%s)", $header);         
      }
      if (!in_array($algo, hash_algos())){
         throw new Exception(sprintf("Illegal hash algorithm (%s)", $algo));         
      }


      //hash_pbkdf2 = Genera una derivación de la clave PBKDF2 de una contraseña proporcionada.
      //algo = Nombre del algoritmo hash seleccionado (esto es: "md5","sha256","haval160,4",etc).
      //salt = Es un valor para la derivación. Este valor debería ser generado aleatoriamente.
      //iterations = El número de iteraciones internas para realizar la derivación.

      $calc = hash_pbkdf2(
         $algo,
         $password,
         $salt,
         (int) $iter,
         32,
         true
      );     
      return hash_equals($calc, base64_decode($hash));
   }


#Cerrar sesión
   public function logout(Request $request){
         
   }

}
