# Asset Management System (UFRRJ)

## Project with Laravel 11 + Docker + Telescope + Debugar + AdminLTE3 + DataTables server side + Spatie ACL

### Resources

-   Basic user controller
-   Visitors log
-   Departments controller
-   Groups controller
-   Material controller

### Usage

-   `cp .env.example .env`
-   Edit .env parameters
-   `alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'`
-   `sail composer install`
-   `sail artisan key:generate`
-   `SAILartisan jwt:secret`
-   `sail artisan storage:link`
-   `sail artisan migrate --seed`
-   `npm install && npm run dev`
-   `sail stop`

-   `docker-compose exec laravel.test bash`

#### Programmer login

-   user: <programador@base.com>
-   pass: 12345678

#### Pending tasks

-   Colocar a opção de selecionar o inventário para o usuário comum e na página do material aparecer a seleção de material com uma default
-   Fazer o cálculo por setores igual ao de grupo
-   Aba Material, incluir material por setor igual usuário comum
-   Criar Aba Material por setor (menos para usuário), escolher inventário, gerar relatório na tela
-   Aba Materiais ativos, do lado esquerdo do SIADS incluir opção de marcar X ao lado de vários itens. Ao lado da opção Operações em lote incluir botão Alterar em Lote. Vai abrir nova página com todos os itens marcados anteriormente para edição em ordem numérica de SIADS. O que fizer no primeiro item e apertar botão OK vai ser repetido nos demais. No final das alterações deve ter botão Salvar Alterações.
-   Na tabela de materiais ativos, pesquisar por SIADS (ao invés de RM)
-   No gráfico, quando clicar em alguma barra então mostrar relação de material daquele ano.
-   Fazer relatório por grupos, com todos os materiais pertencentes ao grupo, similar ao de setor
