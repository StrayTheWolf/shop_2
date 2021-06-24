<ul class="nav flex-column ">
    <!-- Проходим циклом по результату запроса из базы путем вызова функции -->
    <?php foreach ($news as $item): ?>
        <br>
        <li class="nav-item mb-3 text-start border border-info border-2 rounded p-2" >
            ID:
            <?= $item['id'] ?>
            <br>
            Заголовок:
            <?= $item['title'] ?>
            <br>
            Текст:
            <?= $item['content'] ?>
            <br>
            Дата и время публикации:
            <?= $item['created_at'] ?>
        </li>
    <?php endforeach; ?>
</ul>