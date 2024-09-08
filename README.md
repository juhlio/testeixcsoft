
# Sistema de Pagamento Simplificado

Esta aplicação Laravel é um sistema para gerenciar pagamentos entre clientes e lojistas. Permite o cadastro de Pessoas Físicas e Pessoas Jurídicas. E a transfência de saldo.

## Requisitos

- PHP >= 8.0
- Laravel >= 11.x
- Composer
- Banco de Dados: MySQL
- Servidor Web: Apache ou Nginx






## Instalação

 git clone https://github.com/juhlio/testeixcsoft.git

```bash
  cd testeixcsoft
```
### Instalando as dependências  
```  
  composer install
``` 
```  
  npm install
``` 
### Configuração do ambiente
Copie o arquivo .env.example para um novo arquivo .env e configure suas variáveis de ambiente:

```
cp .env.example .env
```
Edite o arquivo ```.env``` para configurar a conexão com o banco de dados e outras configurações necessárias.

### Gerando a chave da aplicação

Gere a chave de aplicação do Laravel:
```
php artisan key:generate
```
### Executando Migrations e Seeders

Execute as migrations e seeders para configurar o banco de dados:
```
php artisan migrate --seed
```

#### Logins de teste
joao.silva@example.com | senha123
empresateste@example.com | senha123
## Estrutura
### Estrutura da Aplicação
Diretórios Principais
- app/: Contém o código da aplicação, incluindo Controllers, Models, e Services.
- resources/views/: Contém os arquivos Blade para as views da aplicação.
- routes/: Contém os arquivos de rotas.
- public/: Contém os arquivos públicos, como imagens e scripts.
- config/: Contém os arquivos de configuração da aplicação.
- database/: Contém migrations, seeders e o banco de dados.

### Funcionalidades
#### Registro de usuários
- Criar Usuário: Permite registrar um novo usuario.
- Todo novo usuário inicia com R$ 1000,00 de saldo. 
- É possivel cadastrar Pessoas Físicas ou Pessoas Jurídicas.
- Não é possivel cadastrar mais de um usuário com o mesmo documento (CPF ou CNPJ).
- Não é possível cadastrar mais de um usuário com o mesmo e-mail.

#### Transferir Dinheiro
- Permite transferir dinheiro entre usuários.
- Apenas pessoas físicas podem enviar.
- Qualquer usuário pode receber dinheiro.
- Não é possivel enviar um valor maior que o saldo disponível do usuário.

 
### Estrutura das Rotas

### Rotas de Autenticação

- **`/register`**: Cadastrar um novo usuário.
- **`/login`**: Autenticar um usuário.


#### Rotas de Navegação (Acessadas apenas por usuários logados)


- **`GET /dashboard`**: Painel do usuário com informações de saldo, transferências enviadas e transferências recebidas.

- **`GET /transfer`**: Formulário para realizar uma tranferência. São solicitados o documento do recebedor e valor a ser enviado.

Documentos teste para envio: 
123.456.789-00 (CPF)
12.345.678/0001-99 (CNPJ)

- **`POST /transfer`**: Processa a tranferência. 


## Controllers

### `RegisterController`
Gerencia o cadastro de novos usuários.

- **`validator`**: Função que valida as informações enviadas pelo formulário de registro. Verica se o e-mail e documento são validos e inéditos no sistema.

- **`isValidCpfOrCnpj`**: Verifica se o CPF ou CNPJ enviado é válido.

- **`create`**: Registra o novo usuário e adiciona R$ 1000,00 de saldo. 

### `DashboardController`

Gerencia a exibição do Painel do Cliente. 

- **`index`**: Chama a view com o painel do usuário.

### `TransactionController`

- **`consultExternalService`**: Consulta API externa para confirmar se a transação pode ser realizada. Caso o id conste no Endereço externo a transação é autorizada.

- **`sendNotification`**: Envia a confirmação do envio da transação aos usuários envolvidos na tranferência. É usada uma fila de processamento e uma API externa para esse processo.

- **`transfer`**: Função responsável pelo processo de tranferência. Verifica se o tipo de usuário é autorizado a enviar. Verifica também o saldo disponivel de quem vai enviar. Aciona a consulta externa, atualiza as informações no banco de dados e o envio das notificações.
## Autores

- [@Julio](https://www.github.com/juhlio)

