# Clínica Vet — Sistema de Gestão de Clínica Veterinária

Trabalho 2 da disciplina de PHP — UTFPR.

## Integrantes
- Andrey Felipe de Lima  RA 1554760
- Davi  RA 2663910

## Descrição
Aplicação web em PHP 8+ (sem framework) para gestão de uma clínica veterinária:
cadastro de tutores, animais, consultas e usuários do sistema, com autenticação
por sessão e arquitetura MVC.

## Tecnologias
- PHP 8+
- MySQL / MariaDB
- Bootstrap 5 (via CDN)
- PDO com prepared statements
- Composer com autoload PSR-4
- Monolog para registro de erros

## Estrutura do projeto
```
public/         index.php (front controller), css/, js/
app/Controllers/
app/Entities/   Usuario, Tutor, Animal, Consulta (objetos de domínio com propriedades tipadas)
app/Repositories/ acesso ao banco via PDO, retornando entidades
app/Validators/ validação de formulário de cada entidade
app/Views/
app/Core/       Router, Database, Controller base, Repository base, Validator, Auth
config/         config.php (não versionado) baseado em config.example.php
database/       schema.sql, seed.sql
docs/           INSTALACAO.md
```

## Atividades de cada integrante

| Integrante | Atividades desenvolvidas |
|---|---|
| Andrey | Estrutura MVC (Router, Database, Controller base), autenticação com sessão, CRUD de usuários, controle de perfis, tratamento de erros e documentação de instalação. |
| Davi | **T1:** Validator, layout Bootstrap, CRUD de tutores, CRUD de animais, agendamento de consultas, filtros de listagem e mensagens de feedback.<br>**T2:** entidades OOP de Tutor, Animal e Consulta; repositórios com classe base abstrata; validação extraída dos controllers para classes por entidade; URL transparente nas rotas de tutores, animais e consultas. |

## Instalação
Veja [docs/INSTALACAO.md](docs/INSTALACAO.md).

## Relatório técnico
Veja [docs/RELATORIO_T2.pdf](docs/RELATORIO_T2.pdf) para a descrição das mudanças,
dos arquivos novos e da verificação dos requisitos do Trabalho 2. A versão editável
da documentação está em [docs/RELATORIO_T2.html](docs/RELATORIO_T2.html).
