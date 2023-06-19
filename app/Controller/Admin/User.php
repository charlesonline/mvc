<?php

namespace App\Controller\Admin;

use App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page {

    /**
     * Método responsável por obter a renderização de itens de usuarios para a pagina
     * @param Request $request
     * @param Pagination $obPagination
     * 
     * @return String
     */
    private static function getUserItems($request,&$obPagination){
        //USUARIOS
        $itens = '';

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
            $itens .= View::render('admin/modules/users/item',[
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ]);
        }

        //RESTORNA OS USUARIOS
        return $itens;
    }

    /**
     * Método responsável por renderizar a view de listagem de usuarios
     * @param Request $request
     * 
     * @return string
     */
    public static function getUsers($request){
        //CARREGAR O CONTEUDO DA HOME
        $content = View::render('admin/modules/users/index',[
            'itens' => self::getUserItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Usuarios > CSF',$content,'users');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuarios
     * @param Request $request
     * 
     * @return string
     */
    public static function getNewUser($request){
        //CARREGAR O CONTEUDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form',[
            'title' => 'Cadastrar usuario',
            'nome' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar usuario > CSF',$content,'users');
    }

    /**
     * Método responsável por cadastrar um usuario no banco
     * @param Request $request
     * 
     * @return string
     */
    public static function setNewUser($request){
        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //VALIDA O EMAIL DO USUARIO
        $obUser = EntityUser::getUserByEmail($email);
        if ($obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        //NOVA INSTANCIA DE USUARIO
        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser->cadastrar();

        //REDIRECIONA O USUÁRI
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * 
     * @return string
     */
    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request->getQueryParams();

        //STATUS
        if ( !isset($queryParams['status']) ) {
            return '';
        }

        //MENSAGEM DE STATUS
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Usuario criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuario atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuario excluido com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail já esta sendo utilizado!');
                break;
            default:
                return '';
                break;
        }
        // echo '<pre>';
        // print_r($queryParams);
        // echo '</pre>'; exit;
    }

    /**
     * Método responsável por retornar o formulário de edição de um novo usuario
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function getEditUser($request,$id){
        //OBTEM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);


        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            $request->getRouter()->redirect('/admin/users');
        }

        //CARREGAR O CONTEUDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form',[
            'title' => 'Editar usuario',
            'nome' => $obUser->nome,
            'email' => $obUser->email,
            'status' => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar usuario > CSF',$content,'users');
    }

    /**
     * Método responsável por gravar a atualização de um usuario
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function setEditUser($request,$id){
        //OBTEM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            $request->getRouter()->redirect('/admin/users');
        }

        //POST VARS
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //VALIDA O EMAIL DO USUARIO
        $obUserEmail = EntityUser::getUserByEmail($email);
        if ($obUserEmail instanceof EntityUser && $obUserEmail->id != $id) {
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        //ATUALIZA A INSTANCIA
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha,PASSWORD_DEFAULT);
        $obUser->atualizar();

        //REDIRECIONA O USUÁRI
        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um novo usuario
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function getDeleteUser($request,$id){
        //OBTEM O DEPOIMENTO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        // echo '<pre>';
        // print_r($obTestimony);
        // echo '</pre>'; exit;

        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            $request->getRouter()->redirect('/admin/users');
        }

        // echo '<pre>';
        // print_r($obTestimony);
        // echo '</pre>'; exit;

        //CARREGAR O CONTEUDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/delete',[
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir usuario > CSF',$content,'users');
    }

    /**
     * Método responsável por EXCLUIR o usuario
     * @param Request $request
     * @param integer $id
     * 
     * @return string
     */
    public static function SetDeleteUser($request,$id){
        //OBTEM O USUARIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);
        // echo '<pre>';
        // print_r($obTestimony);
        // echo '</pre>'; exit;

        //VALIDA A INSTANCIA
        if ( !$obUser instanceof EntityUser ) {
            $request->getRouter()->redirect('/admin/users');
        }

        //EXCLUIR O USUARIO
        $obUser->excluir();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }

}