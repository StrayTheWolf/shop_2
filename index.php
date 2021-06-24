<?php

/*
//Создать файл со структурой БД +
//Наполнить таблицу какими то (тестовыми) данными +
/////Сделать умный роутинг
/////Добавить страницы pages +
/////Добавить страницы news +
/////Добавить вывод всех новостей +
/////Переделать pages с буфером -
 */

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = trim($url, '/');

$partUrl = explode('/', $url);

if ($partUrl[0] == 'pages') {
    $pdo = new PDO('mysql:dbname=shop;host=127.0.0.1', 'root', 'Werewolf1989*');
    //подготавливаем запрос
    $prepared = $pdo->prepare('SELECT id, title, content FROM shop.pages WHERE id = ?');
    //связываем цифру из пути как параметр для запроса
    $prepared->bindParam(1, $partUrl[1], PDO::PARAM_INT);
    //выполняем запрос
    $prepared->execute();
    $page = $prepared->fetch(PDO::FETCH_ASSOC);

    // проверка 404 если нет id в базе со страницами
    if (!$page) // если в page ничего не пришло, значит в базе нет такого ID знчит 404
    {
        include 'templates/404.php';
        include 'templates/layout.php';
        exit;
    }

    $title = $page['title'];
    $content = $page['content'];
    include 'templates/layout.php';
}

if ($partUrl[0] == 'news' && $partUrl[1] !== null) {
    $pdo = new PDO('mysql:dbname=shop;host=127.0.0.1', 'root', 'Werewolf1989*');
    $prepared = $pdo->prepare('SELECT id, title, content, created_at FROM shop.news WHERE id = ?');
    //связываем цифру из пути как параметр для запроса
    $prepared->bindParam(1, $partUrl[1], PDO::PARAM_INT);
    //выполняем запрос
    $prepared->execute();
    $news = $prepared->fetch(PDO::FETCH_ASSOC);

    if (!$news) {
        include 'templates/404.php';
        include 'templates/layout.php';
        exit;
    }

    //включаем буфер
    ob_start();
    include 'templates/listOneNews.php';
    // собираем темлейт со списком новостей
    $content = ob_get_contents();
    ob_clean();
    $title = 'Новости';
    //инклудим основной шаблон в котором в переменную content  передаем содержимое буфера с новостями
    include 'templates/layout.php';
    exit;
}

//вывод всех новостей если путь /news/* - пустота
if ($partUrl[0] == 'news' && $partUrl[1] == '') {
    $pdo = new PDO('mysql:dbname=shop;host=127.0.0.1', 'root', 'Werewolf1989*');
    //подготавливаем запрос из базы
    $prepared = $pdo->prepare('SELECT id, title, content, created_at FROM shop.news GROUP BY id');
    //выполняем запрос
    $prepared->execute();
    $news = $prepared->fetchAll(PDO::FETCH_ASSOC);

    //вывод темлейт со списком новостей где из переменной newsAll foreach делает поиск по массиву и подставляет данные

    //включаем буфер
    ob_start();
    include 'templates/listAllNews.php';
    // собираем темлейт со списком новостей
    $content = ob_get_contents();
    ob_clean();
    $title = 'Все Новости';
    //инклудим основной шаблон в котором в переменную content  передаем ссожержимое буфера с нвостями
    include 'templates/layout.php';

    //проверка 404 если нет id в базе со страницами
    if (!$news) {
        include 'templates/404.php';
        include 'templates/layout.php';
        exit;
    }
}

/*
Задание: Простой интернет магазин
Требования использвоать bootstrap или tailwind для оформления. Не использовать свои js или css.

Часть 1) Релизовать пользовательскую часть - простые страницы. В магазине должно быть 6 простых страниц содержимое которых берётся из базы данных.
Страницы:
    О нас
    Доставка
    Способы оплаты
    Контакты
    Вакансии

Страницы выглядят просто заголовко и ниже текст страницы.
Страницы должны открываться по адресам /pages/{id} - где id это уникальный идентификатор страницы из базы данных

Часть 2) Реализовать администраторскую часть - где будет возможность редактировать страницы. Это часть доступна только после входа.
Должна быть таблица ползователей в которой обязательно должно быть булево поле is_admin которое говорит является ли пользователь администратором.
Должна быть форма входа где можно войти в администраторскую часть. Форма входа по email и паролю.
Если пользователь в таблице есть с тнужным емейлом и паролем и он администратор то разрешать вход в противном случае писать что нет прав.
После входа открывается административный раздел - где в меню должно быть 2 ссылки.
- Пользователи
- Страницы

При попдании на каждую вы выдите таблицу которая выводит записи. Количество записей на 1 странице 15. И есть пагинация в которой можно листать сьтраницы.
В каждой строке помимо данных должна быть последним столбцом кнопка редактировать и удалить. Удалить - удаляет запись и возвращает к таблице. Редактирвоать открывает форму редактирования.
Над таблицей выводить кнопку добавить. Которая ведёт на форму добавления новой записи. По добавления пользователь должен возврщаться на страницу с таблицей.
Редактировать и создать это почти одинаковая страница с той разницей что редактировать сразу заполнены все поля. А создать форма пустая.

Часть 3) В даминистративную часть добавляются 2 раздела с товарами и заказами.
Где также можно создовать и менять записи. Товар имеет следующие поля: Название, Картинка, Описание, Цена.
Заказ имеет поля - товар, имя клиента, телефон клиента, сумма заказа.

На пользовательской части добавить страницу с товарами. Товары показываются в виде карточек - картинка, название, цена, описание - кнопка купить.
По нажатию на кнопку купить пользователь попадает в корзину, в которой показан выбранный товар и форма для заполнения личныйх данных. Имя, телефон.
После заполнения отправляется заказ в таблицу.
 */