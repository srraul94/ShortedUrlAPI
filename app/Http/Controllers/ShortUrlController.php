<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ShortUrlController extends Controller
{
    public function shorting(Request $request){


        // Validamos que el campo 'url' esté presente y sea una URL válida.
        $validator = Validator::make(['url' => $request->input('url')], [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [ 'error' => 'URL no válida.'],
                404, [], JSON_UNESCAPED_UNICODE);
        }


        //Obtenemos el URL original desde el cuerpo de la solicitud.
        $originalUrl = $request->input('url');


        //Enviamos una solicitud GET a la API de TinyURL
        $response = Http::get('https://tinyurl.com/api-create.php',[
            'url' => $originalUrl
        ]);

        //Verificamos que la solicitud se haya realizado correctamente y que se haya recibido la url acortada
        if ($response->ok() and !empty($response->body())){
            $shortUrl = $response->body();

            //Devolvemos la URL acortada como JSON.
            return response()->json([
                'url' => $shortUrl
            ]);
        } else {
            //Si ocurrio un error, devolvemos la respuesta de error
            return response()->json(
                [ 'error' => 'Ocurrió un error al acortar la URL.'],
                500, [], JSON_UNESCAPED_UNICODE);
        }

    }

}
