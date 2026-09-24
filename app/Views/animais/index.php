<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Animais</h1>
    <a href="/animais/novo" class="btn btn-primary">Novo Animal</a>
</div>
<table class="table table-striped">
    <thead><tr><th>Nome</th><th>Espécie</th><th>Tutor</th><th></th></tr></thead>
    <tbody>
        <?php foreach ($animais as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['nome']) ?></td>
                <td><?= htmlspecialchars($a['especie']) ?></td>
                <td><?= htmlspecialchars($a['tutor_nome']) ?></td>
                <td class="text-end">
                    <a href="/animais/editar?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>