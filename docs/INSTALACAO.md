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

2. Instale as dependências:
   ```bash
   composer install
   ```

3. Crie o banco de dados e importe o schema:
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p < database/seed.sql
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
- Senha: (definir após gerar o hash com `password_hash`)

<!-- TODO: atualizar credenciais reais e capturas de tela conforme o projeto avança -->
