# ATM-Test

Projeto de APIs que simulam operações básicas de um caixa eletrônico.

## Instalação

> Antes de prosseguir com a instalação certifique-se de possuir instalados em sua máquina ***composer***, ***docker*** e ***docker-compose***


Execute o clone do projeto:

> git clone https://github.com/dcatein/AtmTest.git

*Ou*

> git clone git@github.com:dcatein/AtmTest.git


Navegue até a pasta do projeto, e execute o seguinte comando para dar permissão de execução ao script de instalação:

> sudo chmod +x run.sh


Execute o script de instalação:

> ./run.sh


## Utilização 

### Customers

API destinada ao controle dos clientes.

- URL

`/api/customers`

- Methods

` POST | GET | PUT | DELETE`
 
   
- URL Params:

    - Required:
    
       ` id=[bigint] - ID do cliente - Obrigatório nos métodos GET, PUT e DELETE`

            
- Body Params:

    `Devem ser enviados em formato JSON e somente nos métodos POST e PUT.`

    **name**=[string] - Nome do cliente

    **date_of_birth**=[date] - Data de nascimento do cliente

    **cpf**=[integer] - CPF do cliente

    - Exemplo:


        {
            "name": "Giovanna",
            "date_of_birth": "1997-03-21",
            "cpf": "17464716778"
        }


### Account

API destinada ao controle e operações das contas.

    
- URL

`/api/accounts`

- Methods

` POST | GET | PUT | DELETE`

- URL Params:

    - Required:
    
       ` id=[bigint] - ID da conta - Obrigatório nos métodos GET, PUT e DELETE`
       
       
- Body Params:
            
    `Devem ser enviados em formato JSON e somente nos métodos POST e PUT.`
        
    **customer_id**=[bigint] - ID do usuário

    **type**=[integer] - Tipo da conta. 0 - poupança; 1 - corrente.

    **balance**=[integer] - Saldo inicial da conta.
        
    - Exemplo:
        
        {
            "customer_id" : 24,
            "type" : 0,
            "balance" : 200
        }
        
        
### Operations

APIs destinadas as operações de saque e depósito

#### Deposit

API destinada ao depósito em conta

- URL

`/api/operation/deposit`

- Methods

` POST `

- URL Params:

`Não possui.`


- Body Params:

    `Devem ser enviados em formato JSON`
    
    **account_id**=[bigint] - Conta de destino da operação.
    
    **value**=[integer] - Valor da operação
    
    - Exemplo:
    
    {
        "account_id" : 3,
        "value" : 550
    }
    
- Response:
    
    - Sucesso:
    
    **CODE:** 200
    **CONTENT:** - Retorna uma mensagem de sucesso. Ex.:  "Operação realizada com sucesso"
    
    - Erro:
    
    **CODE:** 400
    **CONTENT:** - Retorna uma mensagem com o motivo da falha da operação. Ex.:  "O valor precisa ser igual ou maior que um."
    
    
#### Withdraw

API destinada ao saque na conta

- URL

`/api/operation/withdraw`

- Methods

` POST `

- URL Params:

`Não possui.`


- Body Params:

    `Devem ser enviados em formato JSON`
    
    **account_id**=[bigint] - Conta de destino da operação.
    
    **value**=[integer] - Valor da operação
    
    - Exemplo:
    
    {
        "account_id" : 3,
        "value" : 550
    }
    
- Response:
    
    - Sucesso:
    
    **CODE:** 200
    **CONTENT:** - Retorna um array com os tipos e quantidade de notas selecionadas para operação. Ex.:  {"100": 1, "50": 1}
    
    - Erro:
    
    **CODE:** 400
    **CONTENT:** - Retorna uma mensagem com o motivo da falha da operação. Ex.: "Saldo insuficiente."
    
    
    
