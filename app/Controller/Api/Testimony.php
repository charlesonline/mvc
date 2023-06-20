<?php

namespace App\Controller\Api;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api {

    /**
     * Método responsável por obter a renderização de itens de depoimentos para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * 
     * @return String
     */
    private static function getTestimonyItems($request,&$obPagination){
        //DEPOIMENTOS
        $itens = [];

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal,$paginaAtual,3);

        //RESULTADOS DA PÁGINA
        $results = EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while ( $obTestimony = $results->fetchObject(EntityTestimony::class) ) {
            $itens[] = [
                'id' => (int)$obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => $obTestimony->data
            ];
        }

        //RESTORNA OS DEPOIEMNTOS
        return $itens;
    }

    /**
     * Método resposnável por retornar os detalhes dos depoimentos
     * @param Request $request
     * 
     * @return array
     */
    public static function getTestimonies($request){
        return [
            'depoimentos' => self::getTestimonyItems($request,$obPagination),
            'paginacao' => parent::getPagination($request,$obPagination)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um depoimento
     * @param Request $request
     * @param integer $id
     * 
     * @return array
     */
    public static function getTestimony($request,$id){
        //VALIDA O ID DO DEPOIMENTO
        if ( !is_numeric($id) ) {
            throw new \Exception('O id: '.$id.', não é válido',400);
        }

        //BUSCA DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);
        // echo '<pre>';
        // print_r($obTestimony);
        // echo '</pre>'; exit;

        //VALIDA SE O DEPOIMENTO EXISTE
        if( !$obTestimony instanceof EntityTestimony ){
            throw new \Exception('O depoimento '.$id.' não foi encontrado',404);
        }

        //RETORNA OS DETALHES DO DEPOIMENTO
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por cadastrar um novo depoimento
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setNewTestimony($request){

        //POST VARS
        $postVars = $request->getPostVars();

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if( !isset($postVars['nome']) || !isset($postVars['mensagem']) ){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios" ,400);
        }

        //NOVO DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        //RETORNA OS DETALHES DO DEPOIMENTO CADASTRADO
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por atualizar um depoimento
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setEditTestimony($request,$id){

        //POST VARS
        $postVars = $request->getPostVars();

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if( !isset($postVars['nome']) || !isset($postVars['mensagem']) ){
            throw new \Exception("Os campos 'nome' e 'mensagem' são obrigatórios" ,400);
        }

        //BUSCA O DEPOIMENTO NO BANCO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if ( !$obTestimony instanceof EntityTestimony ) {
            throw new \Exception('O depoimento '.$id.' não foi encontrado',404);
        }

        //ATUALIZA O DEPOIMENTO
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->atualizar();

        //RETORNA OS DETALHES DO DEPOIMENTO ATUALIZADO
        return [
            'id' => (int)$obTestimony->id,
            'nome' => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'data' => $obTestimony->data
        ];
    }

    /**
     * Método responsável por excluir um depoimento
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setDeleteTestimony($request,$id){

        //BUSCA O DEPOIMENTO NO BANCO
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if ( !$obTestimony instanceof EntityTestimony ) {
            throw new \Exception('O depoimento '.$id.' não foi encontrado',404);
        }

        //EXCLUI O DEPOIMENTO
        $obTestimony->excluir();

        //RETORNA SUCESSO
        return [
            'success' => true
        ];
    }
}
