<?php require __DIR__ . '/../layout/header.php'; ?>
<h1><?= $animal ? 'Editar Animal' : 'Novo Animal' ?></h1>
<form method="post" action="<?= $animal ? '/animais/editar' : '/animais/novo' ?>">
    <?php if ($animal): ?>
        <input type="hidden" name="id" value="<?= $animal['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Tutor</label>
        <select name="tutor_id" class="form-select <?= isset($erros['tutor_id']) ? 'is-invalid' : '' ?>">
            <option value="">Selecione...</option>
            <?php foreach ($tutores as $t): ?>
                <option value="<?= $t['id'] ?>"
                    <?= (int)($old['tutor_id'] ?? $animal['tutor_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erros['tutor_id'])): ?><div class="invalid-feedback"><?= $erros['tutor_id'] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['nome'] ?? $animal['nome'] ?? '') ?>">
        <?php if (isset($erros['nome'])): ?><div class="invalid-feedback"><?= $erros['nome'] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Espécie</label>
        <input type="text" name="especie" class="form-control <?= isset($erros['especie']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['especie'] ?? $animal['especie'] ?? '') ?>">
        <?php if (isset($erros['especie'])): ?><div class="invalid-feedback"><?= $erros['especie'] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Raça</label>
        <input type="text" name="raca" class="form-control" value="<?= htmlspecialchars($old['raca'] ?? $animal['raca'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Data de nascimento</label>
        <input type="date" name="data_nascimento" class="form-control <?= isset($erros['data_nascimento']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['data_nascimento'] ?? $animal['data_nascimento'] ?? '') ?>">
        <?php if (isset($erros['data_nascimento'])): ?><div class="invalid-feedback"><?= $erros['data_nascimento'] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Peso (kg)</label>
        <input type="number" step="0.01" name="peso" class="form-control" value="<?= htmlspecialchars($old['peso'] ?? $animal['peso'] ?? '') ?>">
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>