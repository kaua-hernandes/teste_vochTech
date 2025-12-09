## Teste VochTech

<p align="center"><a href="#"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<p align="center"><a href="#"><img src="https://img.shields.io/badge/Laravel-Framework-red" alt="Laravel"></a> <a href="#"><img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP"></a> <a href="#"><img src="https://img.shields.io/badge/MySQL-Database-orange" alt="MySQL"></a> <a href="#"><img src="https://img.shields.io/badge/Livewire-Livewire-brightgreen" alt="Livewire"></a> <a href="#"><img src="https://img.shields.io/badge/License-MIT-lightgrey" alt="License"></a></p>

Este projeto é um sistema de cadastro de **Colaboradores**, **Unidades**, **Bandeiras** e **Grupos Econômicos**, desenvolvido como teste prático para uma vaga.

---

<h2>Apresentação do Projeto</h2>
Link do vídeo apresentando projeto: (https://drive.google.com/file/d/1K9BXuuGJPO1DBDYC3YoPgB0wjoOLTnc2/view?usp=sharing)



### Tecnologias e Funcionalidades

O sistema foi construído utilizando as seguintes tecnologias:

* **Laravel 10** e **PHP 8.2**
* **MySQL**
* **Livewire** (para interatividade reativa, com modais para CRUD)
* **Laravel Sail** (para ambiente de desenvolvimento Dockerizado)
* **Queue** (para processamento assíncrono de exportações)
* **Mailpit** (para captura e visualização de emails de teste)
* **spatie/laravel-activitylog** (para auditoria)

#### Principais Recursos:

* CRUD completo (Criar, Editar, Excluir) para todas as entidades via **Modals Livewire**.
* Busca em **tempo real** e **Autocomplete** em campos relacionados.
* Exportação de colaboradores para **Excel** processada em *background* via **Queue**.
* Captura de emails de teste pelo **Mailpit** na porta `8025`.
* **Seeders** incluídos para popular o banco de dados com dados de teste.
* Validação de campos com mensagens de erro.

---

### Primeiros Passos

O projeto utiliza **Laravel Sail** e **Docker** para gerenciamento do ambiente.

#### 1. Instalação e Configuração

Siga os passos abaixo para configurar o projeto:

```bash
# 1. Clone o repositório e entre na pasta do projeto
git clone https://github.com/kaua-hernandes/teste_vochTech.git
cd teste_vochTech

# 2. Suba os containers Docker do Laravel Sail em background
./vendor/bin/sail up -d

# 3. Instale as dependências PHP (dentro do container)
./vendor/bin/sail composer install

# 4. Gere a chave do aplicativo
./vendor/bin/sail artisan key:generate

# 5. Crie o link para o armazenamento público
./vendor/bin/sail artisan storage:link

# 6. Instale as dependências JS e compile os assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# 7. Rode as migrations e seeders para criar e popular o banco de dados
./vendor/bin/sail artisan migrate --seed

# 8. Rode o worker da queue (em um terminal separado) para processar exportações em background
./vendor/bin/sail artisan queue:work

# Acesso à Aplicação
Aplicação Principal: http://localhost
Visualização de Emails (Mailpit): http://localhost:8025

Fluxo de Uso Recomendado
Acesse http://localhost.

Crie as entidades na seguinte ordem: Grupos Econômicos, Bandeiras e Unidades.

Crie Colaboradores, selecionando a unidade correspondente.

Utilize a busca em tempo real e os modais para gerenciar os dados.

Para testar a exportação, use o modal de exportação de Colaboradores (os arquivos são processados em background pelo worker da queue).

Observação: Os seeders já fornecem dados de teste para todas as entidades, permitindo que você comece a explorar o sistema imediatamente.
