<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Tutores</h1>
    <a href="/tutores/novo" class="btn btn-primary">Novo Tutor</a>
</div>
<table class="table table-striped">
    <thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($tutores as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['nome']) ?></td>
                <td><?= htmlspecialchars($t['cpf']) ?></td>
                <td><?= htmlspecialchars($t['telefone']) ?></td>
                <td class="text-end">
                    <a href="/tutores/editar?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>