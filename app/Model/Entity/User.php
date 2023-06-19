<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{

    /**
     * Id do depoimento
     * @var int $id
     */
    public $id;

    /**
     * Nome do usuário que fez o depoiemnto
     * @var String nome
     */
    public $nome;

    /**
     * Nome do usuário que fez o depoiemnto
     * @var String email
     */
    public $email;

    /**
     * Nome do usuário que fez o depoiemnto
     * @var String senha
     */
    public $senha;

    /**
     * Método responsável por cadastrar a instacia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){

        //INSERE USUARIO NO BANCO DE DADOS
        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);

        //RETORNA SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar a instacia atual no banco de dados
     * @return boolean
     */
    public function atualizar(){

        //ATUALIZA usuario NO BANCO DE DADOS
        return (new Database('usuarios'))->update('id = '.$this->id,[
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
    }

    /**
     * Método responsável por excluir um usuario do banco de dados
     * @return boolean
     */
    public function excluir(){

        //EXCLUIR USUARIO DO BANCO DE DADOS
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }

    /**
     * Método responsável por retornar um usuario com base no seu ID
     * @param integer $id
     * 
     * @return User
     */
    public static function getUserById($id){
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }
        
    /**
     * Método responsável por retornar usuário pelo seu email
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar Depoimentos
     * @param String $where
     * @param String $order
     * @param String $limit
     * @param string $field
     * 
     * @return PDOStatement
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);
    }

}