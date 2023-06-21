<?php

namespace App\Controller\Api;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Api {

    /**
     * Método responsável por obter a renderização de itens de usuarios para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * 
     * @return String
     */
    private static function getUserItems($request,&$obPagination){
        //USUARIOS
        $itens = [];

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        //PAGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTANCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal,$paginaAtual,3);

        //RESULTADOS DA PÁGINA
        $results = EntityUser::getUsers(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while ( $obUser = $results->fetchObject(EntityUser::class) ) {
            $itens[] = [
                'id' => (int)$obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ];
        }

        //RESTORNA OS USUARIOS
        return $itens;
    }

    /**
     * Método resposnável por retornar os detalhes dos usuarios
     * @param Request $request
     * 
     * @return array
     */
    public static function getUsers($request){
        return [
            'usuarios' => self::getUserItems($request,$obPagination),
            'paginacao' => parent::getPagination($request,$obPagination)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um usuario
     * @param Request $request
     * @param integer $id
     * 
     * @return array
     */
    public static function getUser($request,$id){
        //VALIDA O ID DO USUARIO
        if ( !is_numeric($id) ) {
            throw new \Exception('O id: '.$id.', não é válido',400);
        }

        //BUSCA USUARIO
        $obUser = EntityUser::getUserById($id);

        //VALIDA SE O USUARIO EXISTE
        if( !$obUser instanceof EntityUser ){
            throw new \Exception('O usuario '.$id.' não foi encontrado',404);
        }

        //RETORNA OS DETALHES DO USUARIO
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuario
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setNewUser($request){

        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if( empty($nome) || empty($email) || empty($senha) ){
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios" ,400);
        }

        //VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);
        if ($obUser instanceof EntityUser) {
            throw new \Exception("O e-mail: '{$email}' já esta em uso" ,400);
        }

        //NOVO USUARIO
        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser->cadastrar();

        //RETORNA OS DETALHES DO USUARIO CADASTRADO
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por atualizar um usuarios
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setEditUser($request,$id){

        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if( empty($nome) || empty($email) || empty($senha) ){
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios" ,400);
        }

        //BUSCA O USUARIO NO BANCO
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            throw new \Exception('O usuario '.$id.' não foi encontrado',404);
        }

        //VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);
        if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $obUser->id) {
            throw new \Exception("O email: '{$email}' já está em uso" ,400);
        }

        //ATUALIZA O USUARIO
        $obUser->nome = $postVars['nome'];
        $obUser->email = $postVars['email'];
        $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser->atualizar();

        //RETORNA OS DETALHES DO USUARIO ATUALIZADO
        return [
            'id' => (int)$obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por excluir um USUARIO
     * @param Request $request
     * 
     * @return [type]
     */
    public static function setDeleteUser($request,$id){

        //BUSCA O USUARIO NO BANCO
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            throw new \Exception('O usuario '.$id.' não foi encontrado',404);
        }

        //IMPEDE A EXCLUSÃO DO PRÓRPIO CADASTRO
        if ( $obUser->id == $request->user->id ) {
            throw new \Exception('ONão é possiível excluir o cadastro atualmente conectado',400);
        }

        //EXCLUI O USUARIO
        $obUser->excluir();

        //RETORNA SUCESSO
        return [
            'success' => true
        ];
    }
}
