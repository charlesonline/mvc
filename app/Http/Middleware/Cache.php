<?php

namespace App\Http\Middleware;

use App\Utils\Cache\File as CacheFile;

class Cache {

    /**
     * Método responsável por verificar se a request atual pode ser cacheada
     * @param Request $request
     * 
     * @return bollean
     */
    private function isCacheable($request){
        //VALIDA O TEMPO DE CACHE
        if(getenv('CACHE_TIME') < 1) {
            return false;
        }

        //VALIDA O METODO DA REQUISIÇÃO
        if ( $request->getHttpMethod() != 'GET' ) {
            return false;
        }

        //VALIDA O HEADER DE CACHE (SÓ VALIDO ISSO SE EU QUERO QUE O CLIENTE MANIPULE O CACHE)
        // $headers = $request->getHeaders();
        // if ( isset($headers['Cache-Control']) && $headers['Cache-Control'] == 'no-cache' ) {
        //     return false;
        // }

        //CACHEAVEL
        return true;
    }

    /**
     * Método responsável por retornar o hash do cache
     * @param Request $request
     * 
     * @return string
     */
    private function getHash($request){
        //URI DA ROTA
        $uri = $request->getRouter()->getUri();

        //QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        //REMOVE AS BARRAS E RETORNA A HASH
        return rtrim('route-'.preg_replace('/[^0-9a-zA-Z]/','-',ltrim($uri,'/')),'-');
    }

    /**
     * Método resposnsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle($request,$next){
        //VERIFICA SE A REQUEST ATUAL É CACHEAVEL
        if (!$this->isCacheable($request)) return $next($request);

        //RASH DO CACHE
        $hash = $this->getHash($request);

        //RETORNA O CACHE
        return CacheFile::getCache($hash,getenv('CACHE_TIME'),function() use($request,$next){
            return $next($request);
        });
    }

}