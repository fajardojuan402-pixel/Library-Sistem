<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Penalización</title>
</head>
<body>
    <h2>Estimado/a {{ $user->name }}</h2>
    <p>El libro <strong>"{{ $book->title }}"</strong> debía devolverse el día {{ $dueDate }}.</p>
    <p>Este préstamo se encuentra vencido. Se le aplicará una penalización.</p>
    <p>Por favor devuelva el libro lo antes posible para evitar más sanciones.</p>
    <hr>
    <small>Sistema de Biblioteca</small>
</body>
</html>
