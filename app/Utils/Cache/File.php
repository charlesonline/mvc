<?php

namespace App\Utils\Cache;

class File {

    /**
     * Método responsável por retornar o caminho até o arquivo de chache
     * @param string $hash
     * 
     * @return string
     */
    private static function getFilePath($hash){
        //DIRETÓRIO DE CACHE
        $dir = getenv('CACHE_DIR');

        //VERIFICA A EXISTENCIA DO DIRETÓRIO
        if ( !file_exists($dir) ) {
            mkdir($dir,0755,true);
        }

        //RETORNA O CAMINHO ATÉ O ARQUIVO
        return $dir.DIRECTORY_SEPARATOR.$hash;
    }

    /**
     * Método responsável por guardar informações no cache
     * @param string $hash
     * @param mixed $content
     * 
     * @return bollean
     */
    private static function storageCache($hash,$content){
        //SEREALIZA O RETORNO
        $serialize = serialize($content);

        //OBTEM O CAMINHO ATÉ O ARQUIVO DE CACHE
        $cacheFile = self::getFilePath($hash);

        //GRAVA OS DADOS NO CACHE
        return file_put_contents($cacheFile,$serialize);
    }


    /**
     * Método responsável por retornar o conteudo gravado no cache
     * @param string $hash
     * @param integer $expiration
     * 
     * @return mixed
     */
    private static function getContentCache($hash,$expiration){
        //OBTEM O CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        //VERIFICA A EXISTENCIA DO ARQUIVO
        if ( !file_exists($cacheFile) ) {
            return false;
        }

        //VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filectime($cacheFile);
        // $createTime = filemtime($cacheFile);
        $diffTime = time() - $createTime;
        if ($diffTime > $expiration) {
            return false;
        }

        //RETORNA O DADO REAL
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);

    }

    /**
     * Método responsável por obter uma infrormação do chache
     * @param string $hash
     * @param integer $expiration
     * @param Closure $function
     * 
     * @return mixed
     */
    public static function getCache($hash,$expiration,$function){
        //VERIFICA O CONTEUDO GRAVADO
        if ($content = self::getContentCache($hash,$expiration)) {
            return $content;
        }
        
        //EXECUÇÃO DA FUNCÇÃO
        $content = $function();
        
        //GRAVA O RETORNO NO CACHE
        self::storageCache($hash,$content);

        //RETORNA O CONTEUDO
        return $content;

    }
}