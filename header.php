<?php $usuarioLogado = \App\Core\Auth::user(); ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Vet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/usuarios">Clínica Vet</a>
            <?php if ($usuarioLogado): ?>
                <div class="navbar-nav me-auto">
                    <a class="nav-link text-white" href="/usuarios">Usuários</a>
                    <a class="nav-link text-white" href="/tutores">Tutores</a>
                    <a class="nav-link text-white" href="/animais">Animais</a>
                    <a class="nav-link text-white" href="/consultas">Consultas</a>
                </div>
                <div class="d-flex align-items-center text-light">
                    <span class="me-3"><?= htmlspecialchars($usuarioLogado['nome']) ?>
                        (<?= htmlspecialchars($usuarioLogado['perfil']) ?>)</span>
                    <form method="post" action="/logout" class="m-0">
                        <button class="btn btn-outline-light btn-sm" type="submit">Sair</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <div class="container">
        <?php foreach ($_SESSION['flash'] ?? [] as $flash): ?>
            <div class="alert alert-<?= $flash['tipo'] === 'erro' ? 'danger' : 'success' ?>">
                <?= htmlspecialchars($flash['mensagem']) ?>
            </div>
        <?php endforeach;
        unset($_SESSION['flash']); ?>