<?php
require_once './models/Hortaliza.php';
require_once './interfaces/IApiUsable.php';

use \App\Models\Hortaliza as Hortaliza;

class HortalizaController implements IApiUsable
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    
    
    $archivos = $request->getUploadedFiles();
    $destino = "./FotosHortalizas/";
    $nombreAnterior = $archivos['foto']->getClientFilename();
    $extension = explode(".", $nombreAnterior);
    $extension = array_reverse($extension);
    $pathFoto = $destino . $nombre . "." . $extension[0];
    $archivos['foto']->moveTo($pathFoto);


    $hortaliza = new Hortaliza();
    $hortaliza->precio = $parametros['precio'];
    $hortaliza->nombre = $nombre;
    $hortaliza->tipo = $parametros['tipo'];
    $hortaliza->foto = $pathFoto;
    $hortaliza->save();


    $payload = json_encode(array("mensaje" => "Hortaliza creada con exito"));

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $id = $args['id'];

    // Buscamos por primary key
    $hortaliza = Hortaliza::find($id);

    // Buscamos por attr usuario
    // $usuario = Usuario::where('usuario', $usr)->first();

    $payload = json_encode($hortaliza);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Hortaliza::all();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerTipo($request, $response, $args)
  {
    $tipo = $args["tipo"];

    $lista = Hortaliza::where('tipo', '=', $tipo)->get();

    $payload = json_encode($lista);

    $response->getBody()->write($payload);
    // $response->getBody()->write($tipo);

    return $response->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usrModificado = $parametros['usuario'];
    $usuarioId = $args['id'];

    // Conseguimos el objeto
    $usr = Usuario::where('id', '=', $usuarioId)->first();

    // Si existe
    if ($usr !== null) {
      // Seteamos un nuevo usuario
      $usr->usuario = $usrModificado;
      // Guardamos en base de datos
      $usr->save();
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];

    // Buscamos
    $hortaliza = Hortaliza::find($id);

    // Borramos
    $hortaliza->delete();

    $payload = json_encode(array("mensaje" => "Hortaliza borrada con exito"));

    $response->getBody()->write($payload);

    return $response->withHeader('Content-Type', 'application/json');
  }
}
