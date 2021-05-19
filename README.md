# Sistema de ordenamento e soluções tecnológicas #
Versão web do sistema que realiza o gerênciamento das licenças públicas de ambulantes, uso de solo, foodtrucks, mercados e feiras públicas.

## 1 - Estrutura do projeto ##
* O projeto foi desenvolvido utilizando Docker e os pacotes de terceiros de php oferecidos pela coffeecode e gerenciados pelo composer php;
* Foi utilizado o padrão de projeto MVC e a contrução de API's dispostas no arquivo index.php;
* As configurações globais do projeto estão contidas em wwww/source/Config.php;
* O arquivo task.php deve ser utilizado como tarefa cron do servidor, ele que consultará as aplicações de pagamentos e processos externos ao Orditi;
* Os controladores estão contidos em www/source/app/;
* As classes estão contidas em www/source/models;
* As views estão contidas em www/themes.

## 2 - Instalalção das dependências ##
* Para iniciar o projeto, é necessário possuir o Docker instalado em sua máquina e então rodar o comando 'docker-compose -f "docker-compose.yml" up --build',
para que assim possam ser instaladas todas as imagens e assim montando todo o ambiente do sistema;
* Com o Docker instalado, utilize o compando 'docker ps' para visualizar os containers ativos, copie o identificador do container php e então rode o comando
'docker exec idDoConteiner composer install';
* Após o docker ter sido instalado, acesse a url 'http://localhost:8080' e crie um banco de dados chamado orditi e importe nele o arquivo database.sql contido
na pasta www/database.sql.

## 3 - Inicialização do projeto ##
* Com todas as dependências instaladas, utilize o comando 'docker-compose -f "docker-compose.yml" up' e então acesse no seu navegador a url 'httṕ://localhost:81'
