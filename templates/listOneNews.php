<ul class="nav flex-column ">
    <!-- Проходим циклом по результату запроса из базы путем вызова функции -->
        <li class="nav-item mb-3 text-start border border-info border-2 rounded p-2" >
            ID:
            <?= $news['id'] ?>
            <br>
            Заголовок:
            <?= $news['title'] ?>
            <br>
            Текст:
            <?= $news['content'] ?>
            <br>
            Дата и время публикации:
            <?= $news['created_at'] ?>
        </li>
</ul>