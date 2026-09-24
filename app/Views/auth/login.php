<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <h1 class="mb-4">Login</h1>
        <form method="post" action="/login">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>