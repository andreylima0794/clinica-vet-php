# Clínica Vet — Sistema de Gestão de Clínica Veterinária

Trabalho 1 da disciplina de PHP — UTFPR.

## Integrantes
- Andrey Felipe de Lima — RA 1554760
- Davi — RA (preencher)

## Descrição
Aplicação web em PHP 8+ (sem framework) para gestão de uma clínica veterinária:
cadastro de tutores, animais, consultas e usuários do sistema, com autenticação
por sessão e arquitetura MVC.

## Tecnologias
- PHP 8+
- MySQL / MariaDB
- Bootstrap 5 (via CDN)
- PDO com prepared statements

## Estrutura do projeto
```
public/         index.php (front controller), css/, js/
app/Controllers/
app/Models/
app/Views/
app/Core/       Router, Database, Controller base, Validator, Auth
config/         config.php (não versionado) baseado em config.example.php
database/       schema.sql, seed.sql
docs/           INSTALACAO.md
```

## Atividades de cada integrante

| Integrante | Atividades desenvolvidas |
|---|---|
| Andrey | Estrutura MVC (Router, Database, Controller base), autenticação com sessão, CRUD de usuários, controle de perfis, tratamento de erros e documentação de instalação. |
| Davi | Validator, layout Bootstrap, CRUD de tutores, CRUD de animais, agendamento de consultas, filtros de listagem e mensagens de feedback. |

## Particularidades / bugs conhecidos
<!-- Atualizar conforme o desenvolvimento avança -->

## Instalação
Veja [docs/INSTALACAO.md](docs/INSTALACAO.md).
