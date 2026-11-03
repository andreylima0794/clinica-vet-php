<?php require __DIR__ . '/../layout/header.php'; ?>
<h1><?= $tutor ? 'Editar Tutor' : 'Novo Tutor' ?></h1>
<form method="post" action="<?= $tutor ? '/tutores/editar' : '/tutores/novo' ?>">
    <?php if ($tutor): ?>
        <input type="hidden" name="id" value="<?= $tutor['id'] ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($old['nome'] ?? $tutor['nome'] ?? '') ?>" maxlength="150" required>
        <?php if (isset($erros['nome'])): ?><div class="invalid-feedback"><?= $erros['nome'] ?></div><?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CPF</label>
            <input type="text" name="cpf" class="form-control <?= isset($erros['cpf']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['cpf'] ?? $tutor['cpf'] ?? '') ?>" maxlength="14" placeholder="000.000.000-00" required>
            <?php if (isset($erros['cpf'])): ?><div class="invalid-feedback"><?= $erros['cpf'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Telefone</label>
            <input type="tel" name="telefone" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['telefone'] ?? $tutor['telefone'] ?? '') ?>" maxlength="15" placeholder="(00) 00000-0000">
            <?php if (isset($erros['telefone'])): ?><div class="invalid-feedback"><?= $erros['telefone'] ?></div><?php endif; ?>
        </div>
    </div>

    <h2 class="h5 mt-2">Endereço</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control <?= isset($erros['cep']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['cep'] ?? $tutor['cep'] ?? '') ?>" maxlength="9" placeholder="00000-000" required>
            <?php if (isset($erros['cep'])): ?><div class="invalid-feedback"><?= $erros['cep'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Logradouro</label>
            <input type="text" name="logradouro" class="form-control <?= isset($erros['logradouro']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['logradouro'] ?? $tutor['logradouro'] ?? $tutor['endereco'] ?? '') ?>" maxlength="150" required>
            <?php if (isset($erros['logradouro'])): ?><div class="invalid-feedback"><?= $erros['logradouro'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Número</label>
            <input type="text" name="numero" class="form-control <?= isset($erros['numero']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['numero'] ?? $tutor['numero'] ?? '') ?>" maxlength="20" required>
            <?php if (isset($erros['numero'])): ?><div class="invalid-feedback"><?= $erros['numero'] ?></div><?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Complemento</label>
            <input type="text" name="complemento" class="form-control" value="<?= htmlspecialchars($old['complemento'] ?? $tutor['complemento'] ?? '') ?>" maxlength="100">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Bairro</label>
            <input type="text" name="bairro" class="form-control <?= isset($erros['bairro']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['bairro'] ?? $tutor['bairro'] ?? '') ?>" maxlength="100" required>
            <?php if (isset($erros['bairro'])): ?><div class="invalid-feedback"><?= $erros['bairro'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control <?= isset($erros['cidade']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['cidade'] ?? $tutor['cidade'] ?? '') ?>" maxlength="100" required>
            <?php if (isset($erros['cidade'])): ?><div class="invalid-feedback"><?= $erros['cidade'] ?></div><?php endif; ?>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">UF</label>
            <input type="text" name="estado" class="form-control <?= isset($erros['estado']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['estado'] ?? $tutor['estado'] ?? '') ?>" maxlength="2" placeholder="PR" required>
            <?php if (isset($erros['estado'])): ?><div class="invalid-feedback"><?= $erros['estado'] ?></div><?php endif; ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>