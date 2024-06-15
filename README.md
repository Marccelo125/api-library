# 📚[Library API](https://github.com/Marccelo125/api-library) 📦

> [!IMPORTANT]
> Este projeto é um projeto de estudo e aplicação de uma API e o desenvolvimento de um sistema de gestão de livros.</br>

> [!NOTE]
>O desenvolvimento deste projeto será contínuo e paralelo ao meu aprendizado no curso da [Growdev](https://www.growdev.com.br) patrocinado pela [Sicredi Pioneira](https://sicredipioneira.com.br). Estou aprendendo então caso queira dar um `Fork`, sinta-se a vontade para contribuir.

## Índice
1. [Sobre Laravel](#sobre-laravel)
2. [Instalação](#instalação)
3. [Configuração](#configuração)
4. [Roteamento](#roteamento)
5. [Contêiner de injeção de dependência](#contêiner-de-injeção-de-dependência)
6. [Vários back-ends para armazenamento de sessão e cache](#armazenamento-de-sessão-e-cache)
7. [Sobre o projeto](#sobre-o-projeto)
8. [Contribuir](#contribuir)
9. [Sobre o autor](#sobre-o-autor)

# Sobre o projeto
> Este projeto visa o sistema de uma biblioteca onde podemos cadastrar usuários, livros e empréstimos. Este projeto foi desenvolvido com o objetivo de mostrar como lidar com APIs e o desenvolvimento de um sistema de gestão de livros.

![api-library](https://github.com/Marccelo125/api-library/assets/127633664/07c4f733-c071-4489-acae-b571c01b83db)

# Sobre Laravel

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Laravel é um framework de aplicativo web com sintaxe expressiva e elegante. Acreditamos que o desenvolvimento deve ser uma experiência agradável e criativa para ser verdadeiramente satisfatória. O Laravel simplifica tarefas comuns usadas em muitos projetos web, como:

- [Motor de roteamento simples e rápido](#roteamento)
- [Contêiner de injeção de dependência poderoso](#contêiner-de-injeção-de-dependência)
- [Vários back-ends para armazenamento de sessão e cache](#armazenamento-de-sessão-e-cache)

# Instalação

Para instalar o Laravel, siga as instruções no [guia de instalação oficial](https://laravel.com/docs/installation).

Lembre-se de ter o Composer, Apache e PHP instalados em sua máquina.

Comandos:

```bash
composer update
php artisan migrate
php artisan serve
```

# Configuração

A configuração do Laravel é feita através do arquivo `.env`. Você pode encontrar mais informações sobre como configurar o seu ambiente no [guia de configuração oficial](https://laravel.com/docs/configuration).

Para rodar o servidor mysql localmente, recomendo usar o Xampp, Docker ou o Laragon.:

Exemplo de .env local:
```bash
DB_CONNECTION=mysql # tipo
DB_HOST=127.0.0.1 # endereço
DB_PORT=3306 # porta, pode varias de ambiente para ambiente
DB_DATABASE=library # nome da base de dados
DB_USERNAME=root # nome do usuário
DB_PASSWORD= # senha do usuário
```

## Roteamento

O Laravel possui um motor de roteamento simples e rápido. Você pode definir rotas usando o arquivo `routes/web.php` ou `routes/api.php` para rotas web ou API, respectivamente. Para mais informações sobre como definir rotas, consulte o [guia de roteamento oficial](https://laravel.com/docs/routing)
