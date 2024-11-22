<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
</head>

<body>
    <div class="container mt-4">
        <h2>Contacto</h2>

        <form method="POST" action="#">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Asunto</label>
                <select name="asunto" class="form-control" required>
                    <option value="">Seleccione un asunto</option>
                    <option value="Consulta general">Consulta general</option>
                    <option value="Devolución">Devolución</option>
                    <option value="Queja">Queja</option>
                    <option value="Sugerencia">Sugerencia</option>
                </select>
            </div>

            <div class="form-group">
                <label>Mensaje</label>
                <textarea name="mensaje" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
        </form>
    </div>
</body>

</html>