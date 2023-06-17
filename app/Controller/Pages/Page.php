<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

    /**
     * Método responsável por renderizar o topo da page
     * @return string
     */
    private static function getHeader(){
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o footer da page
     * @return string
     */
    private static function getFooter(){
        return View::render('pages/footer');
    }

    /**
     * Responsável por renderiza a paginação
     * @param Request $request
     * @param Pagination $obPagination
     * 
     * @return string
     */
    public static function getPagination($request,$obPagination) {
        //PÁGINAS
        $pages = $obPagination->getPages();//ERRO AQUI, RETORNA VAZIO

        //VERIFICA A QUANTIDADE DE PÁGINAS
        if ( count($pages) < 1 ) {
            return '';
        }

        //LINKS
        $links = '';

        //OBTER A URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GETS
        $queryParams = $request->getQueryParams();

        //RENDERIZA LINKS
        foreach($pages as $page){
            //ALTERA A PÁGINA
            $queryParams['page'] = $page['page'];

            //LINK
            $link = $url.'?'.http_build_query($queryParams);

            // RENDERIZA VIEW
            $links .= View::render('pages/pagination/link',[
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA A BOX DA PAGINAÇÃO
        return View::render('pages/pagination/box',[
            'links' => $links
        ]);
    }

    /**
     * Método responsável por retornar o conteudo da nossa Home
     * @return string
     */
    public static function getPage($title,$content){
        return View::render('pages/page',[
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }

}
