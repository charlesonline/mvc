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
     * Método responsável por retornar usuário pelo seu email
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }
}