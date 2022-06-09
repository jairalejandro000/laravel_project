<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\files;
use App\User;
use Illuminate\Http\File;
use Illuminate\Support\Str;

use  Illuminate\Support\Facades\ Storage;

class FilesController extends Controller
{
    public function Insert(Request $request)
    {
        $path = Storage::disk('local')->putFile('files/', $request->file('file'));
        $file = new files();
        $file->name = $request->name;
        $file->id_user = $request->user()->id;
        $file->file = $path;
        if ($file->save())
        {
            $datos = array(
                'name'=> $request->user()->name,
                'name_arch' => $file->name
             );
            Mail::send('archivopost', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", "Jair Alejandro")->subject('Subida de archivo');
            });
            return response()->json(['Archivo guardado correctamente'], 200);
        }
        return response()->json('Usuario equivocado, revise los datos', 400);
    }
    /**public function Show(Request $request)
    {
        $mostrar = files::firstWhere("name", $request->name);
        if($mostrar)
        {
            return response()->json(['Archivo:', $mostrar], 201);
            $datos = array(
                'name'=> $request->user()->name,
                'name_arch'=> $request->name
            );
            Mail::send('archivoget', $datos, function($message) use ($datos) {
                $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                $message->to("jairalejandro32@outlook.com", "Jair Alejandro")->subject('Descarga');
            });
        }
        return response()->json('Usted no esta autorizado', 401);
    }*/
    public function Download(Request $request)
    {
        $consulta = files::where("name", $request->name)->first();
        if($consulta)
        {
            $arch = $consulta->file;
            if($arch != null)
            {
                $contents = Storage::disk('local')->get($arch);
                $datos = array(
                    'name_arch' => $request->name,
                    //'name' => $request->user()->name 
                 );
                Mail::send('download', $datos, function($message) use ($datos) {
                    $message->from('19170162@uttcampus.edu.mx', 'JAIR ALEJANDRO MARTINEZ CARRILLO');
                    $message->to("jairalejandro32@outlook.com", "Jair Alejandro")->subject('Descarga de archivo');
                });
                return response()->json(['content'=>$contents,'file'=>$arch], 200);
            }
            return response()->json('Error al acceder al archivo', 401);
        }
        return response()->json('Datos erroneos', 401);
    }
}