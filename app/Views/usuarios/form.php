<?php require __DIR__ . '/../layout/header.php'; ?>
<h1>
    <?= $usuario ? 'Editar usuário' : 'Novo usuário' ?>
</h1>
<form method="post" action="<?= $usuario ? '/usuarios/editar' : '/usuarios/novo' ?>">
    <?php if ($usuario): ?>
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control"
            value="<?= htmlspecialchars($old['nome'] ?? $usuario['nome'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control"
            value="<?= htmlspecialchars($old['email'] ?? $usuario['email'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Perfil</label>
        <select name="perfil" class="form-select">
            <?php foreach (['admin', 'atendente', 'veterinario'] as $perfil): ?>
                <option value="<?= $perfil ?>" <?= ($old['perfil'] ?? $usuario['perfil'] ?? '') === $perfil ? 'selected' : '' ?>>
                    <?= ucfirst($perfil) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!$usuario): ?>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirmar senha</label>
            <input type="password" name="senha_confirmacao" class="form-control" required minlength="6">
        </div>
    <?php endif; ?>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>