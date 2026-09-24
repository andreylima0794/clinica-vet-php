<?php require __DIR__ . '/../layout/header.php'; ?>
<h1><?= $consulta ? 'Editar Consulta' : 'Nova Consulta' ?></h1>
<form method="post" action="<?= $consulta ? '/consultas/editar' : '/consultas/novo' ?>">
    <?php if ($consulta): ?>
        <input type="hidden" name="id" value="<?= $consulta['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Animal</label>
        <select name="animal_id" class="form-select <?= isset($erros['animal_id']) ? 'is-invalid' : '' ?>">
            <option value="">Selecione...</option>
            <?php foreach ($animais as $a): ?>
                <option value="<?= $a['id'] ?>"
                    <?= (int)($old['animal_id'] ?? $consulta['animal_id'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erros['animal_id'])): ?><div class="invalid-feedback"><?= $erros['animal_id'] ?></div><?php endif; ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Veterinário</label>
        <select name="veterinario_id" class="form-select <?= isset($erros['veterinario_id']) ? 'is-invalid' : '' ?>">
            <option value="">Selecione...</option>
            <?php foreach ($veterinarios as $v): ?>
                <option value="<?= $v['id'] ?>"
                    <?= (int)($old['veterinario_id'] ?? $consulta['veterinario_id'] ?? 0) === (int)$v['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erros['veterinario_id'])): ?><div class="invalid-feedback"><?= $erros['veterinario_id'] ?></div><?php endif; ?>
    </div>

    <?php
        $dataAtual = $consulta ? substr($consulta['data_hora'], 0, 10) : '';
        $horaAtual = $consulta ? substr($consulta['data_hora'], 11, 5) : '';
    ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="data" class="form-control <?= isset($erros['data']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['data'] ?? $dataAtual) ?>">
            <?php if (isset($erros['data'])): ?><div class="invalid-feedback"><?= $erros['data'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Horário</label>
            <input type="time" name="hora" class="form-control <?= isset($erros['hora']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['hora'] ?? $horaAtual) ?>">
            <?php if (isset($erros['hora'])): ?><div class="invalid-feedback"><?= $erros['hora'] ?></div><?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <?php foreach (['agendada', 'realizada', 'cancelada'] as $s): ?>
                <option value="<?= $s ?>" <?= ($old['status'] ?? $consulta['status'] ?? 'agendada') === $s ? 'selected' : '' ?>>
                    <?= ucfirst($s) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Observações</label>
        <textarea name="observacoes" class="form-control"><?= htmlspecialchars($old['observacoes'] ?? $consulta['observacoes'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>