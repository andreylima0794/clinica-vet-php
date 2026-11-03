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
        if (strlen($numeros) !== 11 || preg_match('/^(\d)\1{10}$/', $numeros)) {
            $this->erros[$campo] = $mensagem;
            return $this;
        }

        for ($posicao = 9; $posicao <= 10; $posicao++) {
            $soma = 0;
            for ($indice = 0; $indice < $posicao; $indice++) {
                $soma += (int) $numeros[$indice] * (($posicao + 1) - $indice);
            }
            $digito = (($soma * 10) % 11) % 10;
            if ($digito !== (int) $numeros[$posicao]) {
                $this->erros[$campo] = $mensagem;
                break;
            }
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