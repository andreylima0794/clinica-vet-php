<?php require __DIR__ . '/../layout/header.php'; ?>
<h1><?= $tutor ? 'Editar Tutor' : 'Novo Tutor' ?></h1>
<form method="post" action="<?= $tutor ? '/tutores/editar' : '/tutores/novo' ?>">
    <?php if ($tutor): ?>
        <input type="hidden" name="id" value="<?= $tutor['id'] ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['nome'] ?? $tutor['nome'] ?? '') ?>">
        <?php if (isset($erros['nome'])): ?><div class="invalid-feedback"><?= $erros['nome'] ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label">CPF</label>
        <input type="text" name="cpf" class="form-control <?= isset($erros['cpf']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['cpf'] ?? $tutor['cpf'] ?? '') ?>">
        <?php if (isset($erros['cpf'])): ?><div class="invalid-feedback"><?= $erros['cpf'] ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($old['telefone'] ?? $tutor['telefone'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($old['endereco'] ?? $tutor['endereco'] ?? '') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>