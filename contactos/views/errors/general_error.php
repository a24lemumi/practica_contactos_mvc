<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['titulo']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
        .error-container { max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .error-code { font-size: 2em; font-weight: bold; color: #e74c3c; }
        .error-message { margin: 20px 0; color: #555; }
        .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code"><?= $data['codigo'] ?? 'Error' ?></div>
        <h2><?= htmlspecialchars($data['titulo']) ?></h2>
        <p class="error-message"><?= htmlspecialchars($data['mensaje']) ?></p>
        <a href="<?= BASE_URL ?>" class="btn">Volver al Inicio</a>
    </div>
</body>
</html>