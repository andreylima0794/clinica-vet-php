<?php

namespace App\Core;

use PDO;

/**
 * Classe base dos repositórios: guarda a conexão PDO e implementa o que é
 * igual para todas as tabelas. Cada repositório filho informa a tabela e
 * como transformar uma linha do banco na sua entidade.
 */
abstract class Repository
{
    protected PDO $db;

    /** Nome da tabela no banco. */
    protected string $tabela;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Converte uma linha retornada pelo PDO na entidade do repositório. */
    abstract protected function paraEntidade(array $linha): object;

    public function find(int $id): ?object
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->tabela} WHERE id = ?");
        $stmt->execute([$id]);
        $linha = $stmt->fetch();

        return $linha ? $this->paraEntidade($linha) : null;
    }

    protected function paraEntidades(array $linhas): array
    {
        return array_map(fn(array $linha) => $this->paraEntidade($linha), $linhas);
    }
}
