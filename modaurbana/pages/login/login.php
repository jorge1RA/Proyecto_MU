<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>

<body>
    <div class="container">
        <br>
        <h2>Iniciar Sesión</h2>

        <form method="POST" action="#">
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Ingresar</button>
        </form>
    </div>
</body>

</html>