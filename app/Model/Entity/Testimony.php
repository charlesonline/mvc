<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Testimony{
    /**
     * Id do depoimento
     * @var int
     */
    public $id;

    /**
     * Nome do usuário que fez o depoiemnto
     * @var String
     */
    public $nome;

    /**
     * A mensagem do depoimento
     * @var String
     */
    public $mensagem;

    /**
     * a DEata de publicação do depoiemento
     * @var String
     */
    public $data;

    /**
     * Método responsável por cadastrar a instacia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //DEFINE A DATA
        $this->data = date('Y-m-d H:i:s');

        //INSERE DEPOIMENTO NO BANCO DE DADOS
        $this->id = (new Database('depoimentos'))->insert([
            'nome' => $this->nome,
            'mensagem' => $this->mensagem,
            'data' => $this->data
        ]);

        //RETORNA SUCESSO
        return true;
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
    public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields);
    }
}