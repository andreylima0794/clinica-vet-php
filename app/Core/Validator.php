<?php

namespace App\Core;

use DateTimeImmutable;

/**
 * Regras de validação reutilizáveis. Cada regra registra no máximo um erro
 * por campo (o primeiro que falhar), e todas retornam $this para permitir
 * encadear as chamadas.
 */
class Validator
{
    private array $erros = [];

    public function obrigatorio(string $campo, mixed $valor, string $mensagem): self
    {
        if (trim((string) $valor) === '') {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    public function cpf(string $campo, string $valor, string $mensagem): self
    {
        $numeros = preg_replace('/\D/', '', $valor);
        if (strlen($numeros) !== 11 || preg_match('/^(\d)\1{10}$/', $numeros)) {
            $this->adicionarErro($campo, $mensagem);
            return $this;
        }

        for ($posicao = 9; $posicao <= 10; $posicao++) {
            $soma = 0;
            for ($indice = 0; $indice < $posicao; $indice++) {
                $soma += (int) $numeros[$indice] * (($posicao + 1) - $indice);
            }
            $digito = (($soma * 10) % 11) % 10;
            if ($digito !== (int) $numeros[$posicao]) {
                $this->adicionarErro($campo, $mensagem);
                break;
            }
        }
        return $this;
    }

    /** Telefone é opcional: só valida o formato quando foi preenchido. */
    public function telefone(string $campo, string $valor, string $mensagem): self
    {
        if ($valor !== '' && !preg_match('/^\(?\d{2}\)?\s?9?\d{4}-?\d{4}$/', $valor)) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    public function cep(string $campo, string $valor, string $mensagem): self
    {
        if (!preg_match('/^\d{5}-?\d{3}$/', $valor)) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    public function uf(string $campo, string $valor, string $mensagem): self
    {
        if (!preg_match('/^[A-Z]{2}$/', $valor)) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    /** Campo opcional: só valida quando foi preenchido. */
    public function data(string $campo, string $valor, string $mensagem, string $formato = 'Y-m-d'): self
    {
        if ($valor !== '' && self::criarData($valor, $formato) === null) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    /** Campo opcional: só valida quando foi preenchido. */
    public function numeroEntre(string $campo, string $valor, float $minimo, float $maximo, string $mensagem): self
    {
        if ($valor !== '' && (!is_numeric($valor) || (float) $valor < $minimo || (float) $valor > $maximo)) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    public function naLista(string $campo, mixed $valor, array $permitidos, string $mensagem): self
    {
        if (!in_array($valor, $permitidos, true)) {
            $this->adicionarErro($campo, $mensagem);
        }
        return $this;
    }

    /**
     * Usado pelas validações específicas de cada entidade para registrar
     * erros que dependem do banco ou de regra de negócio (ex: CPF duplicado).
     */
    public function adicionarErro(string $campo, string $mensagem): self
    {
        if (!isset($this->erros[$campo])) {
            $this->erros[$campo] = $mensagem;
        }
        return $this;
    }

    public function temErro(?string $campo = null): bool
    {
        if ($campo !== null) {
            return isset($this->erros[$campo]);
        }
        return count($this->erros) > 0;
    }

    public function getErros(): array
    {
        return $this->erros;
    }

    /** Remove espaços do início e do fim de todos os campos de texto do formulário. */
    public static function limpar(array $dados): array
    {
        return array_map(fn($valor) => is_string($valor) ? trim($valor) : $valor, $dados);
    }

    /**
     * Converte texto em data no formato informado. Retorna null se a data
     * não existir (ex: 31/02) ou não estiver no formato esperado.
     */
    public static function criarData(string $valor, string $formato): ?DateTimeImmutable
    {
        $data = DateTimeImmutable::createFromFormat('!' . $formato, $valor);
        $erros = DateTimeImmutable::getLastErrors();

        if ($data === false || ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))) {
            return null;
        }
        return $data;
    }
}
