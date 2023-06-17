<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page{

    /**
     * Método responsável por retornar o conteudo da nossa About
     * @return string
     */
    public static function getAbout(){
        //Organização
        $obOrganization = new Organization;


        //VIEW DA About
        $content = View::render(
            'pages/about',
            [
                'name' => $obOrganization->name,
                'description' => $obOrganization->description,
                'site' => $obOrganization->site
            ]
        );

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('About',$content);
    }

}
