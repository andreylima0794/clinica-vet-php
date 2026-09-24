<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Consultas</h1>
    <a href="/consultas/novo" class="btn btn-primary">Nova Consulta</a>
</div>

<form method="get" action="/consultas" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">Todos os status</option>
            <?php foreach (['agendada', 'realizada', 'cancelada'] as $s): ?>
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
                <td><?= htmlspecialchars($c['data_hora']) ?></td>
                <td><?= htmlspecialchars($c['animal_nome']) ?></td>
                <td><?= htmlspecialchars($c['veterinario_nome']) ?></td>
                <td><?= htmlspecialchars($c['status']) ?></td>
                <td class="text-end">
                    <a href="/consultas/editar?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>