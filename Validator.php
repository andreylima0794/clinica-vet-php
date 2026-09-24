<?php

namespace App\Core;

class Validator
{
    private array $erros = [];

    public function obrigatorio(string $campo, mixed $valor, string $mensagem): self
    {
        if (trim((string) $valor) === '') {
            $this->erros[$campo] = $mensagem;
        }
        return $this;
    }

    public function cpf(string $campo, string $valor, string $mensagem): self
    {
        $numeros = preg_replace('/\D/', '', $valor);
        if (strlen($numeros) !== 11) {
            $this->erros[$campo] = $mensagem;
        }
        return $this;
    }

    public function temErro(): bool
    {
        return count($this->erros) > 0;
    }

    public function getErros(): array
    {
        return $this->erros;
    }
}