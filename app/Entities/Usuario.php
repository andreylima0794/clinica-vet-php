<?php

namespace App\Entities;

class Usuario
{
    public const PERFIS = ['admin', 'atendente', 'veterinario'];

    public function __construct(
        public string $nome,
        public string $email,
        public string $perfil,
        public string $senha = '',
        public bool $ativo = true,
        public ?int $id = null,
    ) {
    }

    public static function fromArray(array $dados): self
    {
        $ativo = $dados['ativo'] ?? 1;

        return new self(
            nome: trim((string) ($dados['nome'] ?? '')),
            email: trim((string) ($dados['email'] ?? '')),
            perfil: (string) ($dados['perfil'] ?? ''),
            senha: (string) ($dados['senha'] ?? ''),
            ativo: (bool) (int) $ativo,
            id: isset($dados['id']) ? (int) $dados['id'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha,
            'perfil' => $this->perfil,
            'ativo' => $this->ativo ? 1 : 0,
        ];
    }
}
