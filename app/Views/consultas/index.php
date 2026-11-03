<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Consultas</h1>
    <a href="/consultas/novo" class="btn btn-primary">Nova Consulta</a>
</div>

<form method="get" action="/consultas" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <?php foreach (\App\Entities\Consulta::STATUS as $s): ?>
                <option value="<?= $s ?>" <?= $filtros['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="veterinario_id" class="form-select">
            <option value="">Todos os veterinários</option>
            <?php foreach ($veterinarios as $v): ?>
                <option value="<?= $v['id'] ?>" <?= (string)$filtros['veterinario_id'] === (string)$v['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="data_inicio" class="form-control" value="<?= htmlspecialchars($filtros['data_inicio']) ?>">
    </div>
    <div class="col-md-2">
        <input type="date" name="data_fim" class="form-control" value="<?= htmlspecialchars($filtros['data_fim']) ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
    </div>
</form>

<table class="table table-striped">
    <thead><tr><th>Data/Hora</th><th>Animal</th><th>Veterinário</th><th>Status</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($consultas as $c): ?>
            <tr>
                <td><?= $c->dataHora->format('d/m/Y H:i') ?></td>
                <td><?= htmlspecialchars($c->animalNome) ?></td>
                <td><?= htmlspecialchars($c->veterinarioNome) ?></td>
                <td><?= ucfirst($c->status) ?></td>
                <td class="text-end">
                    <a href="/consultas/editar/<?= $c->id ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$consultas): ?>
            <tr><td colspan="5" class="text-center text-muted">Nenhuma consulta encontrada.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>