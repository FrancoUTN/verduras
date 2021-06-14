<?php

class Verificadora
{
    // public static function Verificar($request, $response, $args)
    public static function Verificar($request, $response)
    {
        $lista = \App\Models\Usuario::all();
        
        $parametros = $request->getParsedBody();

        foreach ($lista as $usuario)
        {
            if ($usuario->mail == $parametros["mail"])
            {
                if ($usuario->sexo == $parametros["sexo"])
                {
                    if ($usuario->clave == $parametros["clave"])
                    {
                        $response->getBody()->write($usuario->tipo);

                        return $response->withStatus(200);
                    }
                }
            }
        }

        return $response->withStatus(401);
        // return $response->withHeader('Content-Type', 'application/json');
    }
}