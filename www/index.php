<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ЗАЯВКА</title>
    <link rel="shortcut icon" href="/assets/images/home.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
</head>

<body>
    <script src="event.js"></script>
    <div class="container">
        <h1 class="title">
            ЗАЯВКА
        </h1>
    </div>
    <div class="container-md">
        <form action="send.php" method="POST" onsubmit="calculateTime()">
            <div class="mb-3">
                <label for="inputName" class="form-label">Имя</label>
                <input type="text" class="form-control" id="inputName" name="name" required="true">
            </div>
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="email" aria-describedby="emailHelp" required="true">
            </div>
            <div class="mb-3">
                <label for="inputPhone" class="form-label">Телефон</label>
                <input type="tel" class="form-control" id="inputPhone" name="phone" required="true">
            </div>
            <div class="mb-3">
                <label for="inputPrice" class="form-label">Цена</label>
                <input type="" class="form-control" id="inputPrice" name="price" required="true">
            </div>
            <input id="input30Second" name="30Second" style="display: none;">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</body>

</html>