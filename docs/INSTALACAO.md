# Instalação e Configuração

## Requisitos
- PHP 8.0 ou superior
- Extensão `pdo_mysql` habilitada
- MySQL ou MariaDB
- Composer

## Passo a passo

1. Clone o repositório:
   ```bash
   git clone https://github.com/andreylima0794/clinica-vet-php.git
   cd clinica-vet-php
   ```

2. Instale as dependências (inclui o pacote `monolog/monolog`, usado para registrar erros em arquivo):
   ```bash
   composer install
   ```

3. Crie o banco de dados e importe o schema:
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p < database/seed.sql
   ```
   Se o banco já existia antes da atualização do cadastro de tutores, execute também:
   ```bash
   mysql -u root -p < database/upgrade_tutores_endereco.sql
   ```

4. Copie o arquivo de configuração e edite com suas credenciais:
   ```bash
   cp config/config.example.php config/config.php
   ```

5. Suba o servidor embutido do PHP:
   ```bash
   php -S localhost:8000 -t public
   ```

6. Acesse `http://localhost:8000` no navegador.

## Usuário de teste
- E-mail: `admin@clinica.test`
- Senha: `admin123`

## Logs de erro
Exceções não tratadas são registradas em `storage/logs/app.log` (criado automaticamente
na primeira requisição que gerar erro). Essa pasta não é versionada no Git.

## URLs
Ao acessar `http://localhost:8000/`, o sistema abre a tela de login.
As rotas usam URLs transparentes, com o identificador no caminho e sem query
string, por exemplo:

- `/usuarios/editar/3`
- `/tutores/editar/5`
- `/animais/editar/5`
- `/consultas/editar/5`

As páginas de listagem ficam disponíveis em `/usuarios`, `/tutores`, `/animais`
e `/consultas`.