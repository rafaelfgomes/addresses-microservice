#!/bin/bash

LINE="============================================="
clear
echo -e "$LINE"
echo "          Script de inicialização"
echo -e "$LINE"

echo -e "\nCriando os containers (se não existirem) e subindo a aplicação com o Docker Compose..."
docker-compose up -d

if [ $? -eq 1 ]; then

    echo -e "\nErro na criação dos containers, removendo criados e finalizando script..."
    docker-compose down
    docker container prune -f
    docker image prune -f

    sleep 2

    exit 1

fi

echo -e "\nContainers criados, configurando a aplicação...\n"

sleep 2

if [ ! -f .env ]; then

    read -p "Digite o nome da aplicação: " appName

    appName="\""$appName"\""

    sleep 2

    echo -e "\nEscolha o ambiente"
    read -p "1. local | 2. produção: " choice

    case $choice in
        1)
            appEnv="local"
            ;;
        2)
            appEnv="production"
            ;;
        *)
            echo -e "Opção inválida, finalizando script..."
            sleep 2
            exit 1
            ;;
    esac

    sleep 2

    appKey=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

    echo -e "\nChave da aplicação gerada: $appKey\n"

    if [ $appEnv = "local" ]; then 

        read -p "Ativar o debug? [s/n]: " debug

        case $debug in
            S)
                appDebug=true
                ;;
            s)
                appDebug=true
                ;;
            N)
                appEnv=false
                ;;
            n)
                appEnv=false
                ;;
            *)
                echo -e "Opção inválida, finalizando script..."
                sleep 2
                exit 1
                ;;
        esac

    else

        echo -e "Configurado para produção, debug será desativado..."
        appEnv=false
    
    fi

    sleep 2

    echo -e "\n"
    read -p "Digite a url da aplicação: " appUrl
    appUrl="http://"$appUrl

    sleep 2

    echo -e "\n"
    read -p "Digite a porta da aplicação: " appPort

    sleep 2

    echo -e "\nSetando o timezone da aplicação..."
    appTz=$(curl https://ipapi.co/timezone)
    echo -e "\nTimezone: $appTz"

    sleep 2

    echo -e "\nCriando hashes para o microserviço..."
    appSecret1=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
    appSecret2=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)

    sleep 2

    echo -e "\nConfigurando o banco de dados MongoDB..."

    dbConnection="mongodb"
    dbUrl="mongodb"
    dbPort="27017"
    dbCollection="addresses"

    echo -e "\n"
    read -p "Digite o nome do usuário do banco MongoDB: " dbUser

    echo -e "\n"
    read -p "Digite a senha do usuário do banco MongoDB: " dbPass

    sleep 2

    echo -e "\nCriando e configurando o arquivo .env..."
    cp .env.example .env

    # Configuração do app
    sed -i "s+APP_NAME=+APP_NAME=$appName+g" .env
    sed -i "s+APP_ENV=+APP_ENV=$appEnv+g" .env
    sed -i "s+APP_KEY=+APP_KEY=$appKey+g" .env
    sed -i "s+APP_DEBUG=+APP_DEBUG=$appDebug+g" .env
    sed -i "s+APP_URL=+APP_URL=$appUrl:$appPort+g" .env
    sed -i "s+APP_TIMEZONE=+APP_TIMEZONE=$appTz+g" .env
    sed -i "s+ACCEPTED_SECRETS=+ACCEPTED_SECRETS=$appSecret1,$appSecret2+g" .env

    # Configuração do banco
    sed -i "s+DB_CONNECTION=+DB_CONNECTION=$dbConnection+g" .env
    sed -i "s+DB_HOST=+DB_HOST=$dbUrl+g" .env
    sed -i "s+DB_PORT=+DB_PORT=$dbPort+g" .env
    sed -i "s+DB_DATABASE=+DB_DATABASE=$dbCollection+g" .env
    sed -i "s+DB_USERNAME=+DB_USERNAME=$dbUser+g" .env
    sed -i "s+DB_PASSWORD=+DB_PASSWORD=$dbPass+g" .env

    sleep 3

    if [ ! -d vendor ]; then

        echo -e "\nInstalando as dependências do composer..."
        docker exec -it app composer install

    else

        echo -e "\nPasta 'vendor' existe instalar as dependências mesmo assim?"
        read -p "1. Sim | 2. Não: " choiceDeps

        case $choiceDeps in
            1)
                echo -e "\nInstalando as dependências do composer..."
                docker exec -it app composer install
                ;;
            2)
                echo -e "\nPulando a instalação, caso precise instalar alguma dependência execute: docker exec -it app composer require <nome_da_dependência>"
                ;;
            *)
                echo -e "Opção inválida, excluindo arquivo .env e finalizando o script"
                rm .env
                sleep 2
                exit 1
                ;;
        esac

    fi

    sleep 2

    echo -e "\nCriando e populando as tabelas..."
    docker exec -it app php artisan migrate:fresh --seed

    sleep 2

    echo -e "Mapeando arquivo host e setando IP dos containers..."
    ipMongo=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' mongodb)
    ipApp=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' app)

    findHostMongo=$(grep -w mongodb /etc/hosts > /dev/null; echo $?)
    findHostApp=$(grep -w addresses-microservice /etc/hosts > /dev/null; echo $?)

    if [ $findHostMongo -eq 1 ]; then
        echo -e "$ipMongo   mongodb" | sudo tee -a /etc/hosts
    fi

     if [ $findHostApp -eq 1 ]; then
        echo -e "$ipApp   addresses-microservice" | sudo tee -a /etc/hosts
    fi

    sleep 2

    echo -e "\nScript finalizado, a aplicação está configurada no endereço $appUrl:$appPort..."

else

    echo -e "Arquivo .env já existe, para configurar a aplicação com novos valores altere manualmente ou apague o arquivo .env e execute novamente o script...\n"
    exit 1

fi
