<?php

namespace App\Controller\Admin;

use App\Utils\View;

class Page{

    /**
     * Módulo disponiveis no painel
     * @var array
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link' => URL.'/admin'
        ],
        'testimonies' => [
            'label' => 'Depoimentos',
            'link' => URL.'/admin/testimonies'
        ],
        'users' => [
            'label' => 'Usuários',
            'link' => URL.'/admin/users'
        ],
    ];

    /**
     * Método responsável por retornar o conteudo (VIEW) da estrutura genérica da página do painel
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title,$content){
        return View::render('admin/page',[
            'title' => $title,
            'content' => $content
        ]);
    }

    /**
     * Renderiza a view do menu do painel
     * @param string $currentModule
     * 
     * @return string
     */
    private static function getMenu($currentModule){

        //LINKS DO MENU
        $links = '';

        //ITERA OS MÓDULOS
        foreach (self::$modules as $hash => $modules) {
            $links .= View::render('admin/menu/link',[
                'label' => $modules['label'],
                'link' => $modules['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }

        //RETORNA A RENDRIZAÇÃO DO MENU
        return View::render('admin/menu/box',[
            'links' => $links
        ]);
    }

    /**
     * mÉTODO RESPONSÁVEL POR RENDERIZAR A VIEW DO PAINEL COM CONTEÚDOS DINAMICOS
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * 
     * @return string
     */
    public static function getPanel($title,$content,$currentModule){

        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel',[
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        //RETIORNA A PÁGINA RENDERIZADA
        return self::getPage($title,$contentPanel);
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
            $links .= View::render('admin/pagination/link',[
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA A BOX DA PAGINAÇÃO
        return View::render('admin/pagination/box',[
            'links' => $links
        ]);
    }
}