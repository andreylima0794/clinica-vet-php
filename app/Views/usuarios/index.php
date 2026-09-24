<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Usuários</h1>
    <a href="/usuarios/novo" class="btn btn-primary">Novo usuário</a>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Perfil</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['perfil']) ?></td>
                <td><?= $u['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                <td class="text-end">
                    <a href="/usuarios/editar?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                    <form method="post" action="/usuarios/inativar" class="d-inline">
                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">
                            <?= $u['ativo'] ? 'Inativar' : 'Ativar' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>