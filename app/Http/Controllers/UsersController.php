<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Str;
use  Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function Prueba(Request $request)
    {
        return response()->json('prueba si jala', 201);
    }

    public function usuario(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
       if ($user->save())
        {
            $datos = array(
                'email'=> $user->email,
                'name'=> $user->name
            );
            Mail::send('registro', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", "Jair Alejandro")->subject('Regsitro correctamente');
            });
        return response()->json(["Usuario registrado correctamente", $user], 201);
        }
        return  response()->json("Error al registrar usuario", 400);
    }

    /**public function Delete(Request $request)
    {
        $ruta = "eliminar usuarios";
        $request->validate([
            'email' => 'required'
            ]);
            if ($request->user()->tokenCan('admin'))
            { 
                $ruta = DB::table('users')
                ->select('users.image')
                ->where('users.email', '=', $request->email)->get();
                Storage::delete($ruta);
                $usuario = DB::table('users')
                ->where('users.email', '=', $request->email)->delete();
                $id = DB::table('users')
                ->select('users.id')
                ->where('users.email', '=', $request->email)->get();
                $comentarios = DB::table('comments')
                ->where('comments.user_id', '=', $id)->delete();
                $tokens = DB::table('personal_access_tokens')
                ->where('personal_access_tokens.name', '=', $request->email)->delete();
                if ($usuario)
                {
                    return response()->json('Usuario eliminado correctamente', 201);
                }
                return response()->json('Datos erroneos', 400);
            }
            $datos = array(
                'name'=> "Jair Alejandro",
                'basura'=> $request->user()->name,
                'basura2'=> $request->user()->email,
                'ruta'=> $ruta
            );
            Mail::send('alert_message', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", $datos['name'])->subject('Alerta');
            });    
            return response()->json('Usted no tiene los permisos para realizar esta accion', 401);
    }*/
    public function LogIn(Request $request)
    {
            $user = User::where('email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) 
            {
                return response()->json('Datos erroneos', 400);
            }
            $datos = array(
                'email'=> $request->email
            );
            Mail::send('login', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", "Jair Alejandro")->subject('Login');
            });
            $token = $user->createToken($request->email, [$user->role])->plainTextToken;
            return response()->json(["token" => $token], 201);
    }
    /**public function UpdateRole(Request $request)
    {
        $ruta = "actualizar rol";
        $request->validate([
            'id' => 'required',
            'role' => 'required'
            ]);
            if ($request->user()->tokenCan('admin'))
            { 
                DB::table('users') ->where('id', $request->id)
                ->update(['role' => $request->role]);
                DB::table('personal_access_tokens') ->where('tokenable_id', $request->id)
                ->update(['abilities' => $request->role]);
                $mostrar = DB::Select('SELECT users.name, users.email, users.role 
                FROM users
                WHERE users.id ='.$request->id);
                if ($mostrar){
                    return response()->json(['Rol de usuario actualizado, para que los permisos se actualizen elimine el token', $mostrar], 201);
                }
                return response()->json('Datos erroneos', 400);
            }
            $datos = array(
                'name'=> "Jair Alejandro",
                'basura'=> $request->user()->name,
                'basura2'=> $request->user()->email,
                'ruta'=> $ruta
            );
            Mail::send('alert_message', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", $datos['name'])->subject('Alerta');
            });
            return response()->json('Usted no tiene los permisos para realizar esta accion', 401);
    }*/
    /**public function LogOut(Request $request)
    {
        $user = DB::table('personal_access_tokens')->where('personal_access_tokens.name', '=', $request->user()->email);
        if($user) 
        {
             $tokens = DB::table('personal_access_tokens')
            ->where('personal_access_tokens.name', '=', $request->email)->delete();
            return response()->json('LogOut hehco de manera correcta', 200);
        }
        return response()->json('Datos erroneos', 400);
    }*/
}
