<?php
error_reporting(E_ALL);

#region //Определяем страницу и заголовки страниц
if (!empty($_GET["pageType"])) {
    $typeOfPage = $_GET["pageType"];

    if ($typeOfPage == "1") {
        $head = "Главная";
        $fieldsetClass = "main-container-fieldset__start";
    } else
        if ($typeOfPage == "2") {
            $head = "Книги";
            $fieldsetClass = "main-container-fieldset-info";
        }
}
else
{
    $head = "Главная";
    $fieldsetClass = "main-container-fieldset__start";
}
#endregion

#region //Проверка массива $_POST и присваивание значений вставкам
if (isset($_POST['name']) || isset($_POST['isbn']) || isset($_POST['author'])) {
    $name = $_POST['name'];
    $isbn = $_POST['isbn'];
    $author = $_POST['author'];
}
else {
    $name = null;
    $isbn = null;
    $author = null;
}
#endregion

#region //Запрос к БД, его обработка и подготовка
include 'connection.php';

$sql = "SELECT * FROM books WHERE name LIKE '%$name%' AND isbn LIKE '%$isbn%' AND author LIKE '%$author%'";
$statement = $pdo->prepare($sql);
$statement->execute();


#endregion
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">
    <title>Книги</title>
</head>
<body>
<header class="header-container">
    <div class="header-container__h1">
        <h1 class="header-container__h1__text"><?php echo $head; ?></h1>
<a class="header-container-link" href="index.php?pageType=1">Главная</a>
<a class="header-container-link" href="index.php?pageType=2">Книги</a>
</div>
</header>
<div class="main-container">
    <?php if ($head === "Книги") {?>
        <fieldset class="main-container-fieldset-info">
            <form method="POST" action="index.php?pageType=2">
                <input type="text" name="name" placeholder=" Название книги" value="<?= $name; ?>" class="main-container-fieldset-info__input">
                <input type="text" name="author" placeholder=" Автор книги" value="<?= $author; ?>" class="main-container-fieldset-info__input">
                <input type="text" name="isbn" placeholder=" ISBN" value="<?= $isbn; ?>" class="main-container-fieldset-info__input">
                <input type="submit" value="Поиск" class="main-container-fieldset-info__input input-button">
            </form>
        </fieldset>
    <?php } ?>
    <?php if ((isset($_POST['name']) || isset($_POST['isbn']) || isset($_POST['author'])) && ($head === "Книги")) { ?>
        <form method="POST" action="index.php?pageType=2">
            <p class="p_button-container"><input type="submit" value="Сбросить фильтр" class="main-container-fieldset-info__input input-button center-button"></p>
        </form>
    <?php } ?>
    <fieldset class="<?php echo $fieldsetClass?>">
        <?php if ($head === "Книги") { ?>
            <table class="main-container-table">
                <tr class="table-row">
                    <td class="table-cell table-header">Название</td>
                    <td class="table-cell table-header">Автор</td>
                    <td class="table-cell table-header">Год выпуска</td>
                    <td class="table-cell table-header">Жанр</td>
                    <td class="table-cell table-header">ISBN</td>
                </tr>
                <?php foreach ($statement as $value) { ?>
                    <tr class="table-row">
                        <td class="table-cell"><?= htmlspecialchars($value['name'], ENT_QUOTES); ?></td>
                        <td class="table-cell"><?= htmlspecialchars($value['author'], ENT_QUOTES); ?></td>
                        <td class="table-cell"><?= htmlspecialchars($value['year'], ENT_QUOTES); ?></td>
                        <td class="table-cell"><?= htmlspecialchars($value['genre'], ENT_QUOTES); ?></td>
                        <td class="table-cell"><?= htmlspecialchars($value['isbn'], ENT_QUOTES); ?></td>
                    </tr>
                <?php } ?>
            </table>

        <?php } else { ?>
            <h1 class="main-container-fieldset__start__text-high">В книги!</h1>
            <p class="main-container-fieldset__start__pic-low"><a href="index.php?pageType=2"><img class="main-container-fieldset__start__pic-arrow" src="image/arrow.png"></a></p>
        <?php } ?>
    </fieldset>
</div>
</body>
</html>