<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>TechMada RH — Gestion des congés CI4</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/font/bootstrap-icons.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/bootstrap/bootstrap-icons/font/bootstrap-icons.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/fonts.css') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">
</head>

<body>

    <div class="auth-page geo-bg">
        <div class="auth-split">

            <!-- Panneau gauche -->
            <div class="auth-left">
                <div>
                    <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
                    <p class="auth-left-text" style="margin-top:2rem">
                        <strong>Bienvenue sur votre espace RH.</strong>
                        Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
                    </p>
                </div>
                <div class="auth-roles">
                    <div style="font-size:.65rem;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.25);margin-bottom:4px">Comptes de démonstration</div>
                    <div class="role-pill">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <div class="role-pill-name">RH</div>
                            <div class="role-pill-cred">sophie.martin@example.com · hash_pwd_1</div>
                        </div>
                    </div>
                    <div class="role-pill">
                        <i class="bi bi-person-check"></i>
                        <div>
                            <div class="role-pill-name">Admin</div>
                            <div class="role-pill-cred">pierre.bernard@example.com · hash_pwd_4</div>
                        </div>
                    </div>
                    <div class="role-pill">
                        <i class="bi bi-person"></i>
                        <div>
                            <div class="role-pill-name">Employé</div>
                            <div class="role-pill-cred">jean.dupont@example.com · hash_pwd_2</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panneau droit -->
            <div class="auth-right">
                <p class="auth-title">Connexion</p>
                <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

                <!-- Flashdata CI4 — erreur -->
                <?php if (session()->has('error')): ?>
                    <div class="flash flash-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Message succès -->
                <?php if (session()->has('success')): ?>
                    <div class="flash flash-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= session('success') ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= base_url('/login') ?>">
                    <?= csrf_field() ?>

                    <div class="f-group">
                        <label class="f-label">Adresse email</label>
                        <input type="email" class="f-input" name="email" placeholder="exemple@mail.com" value="sophie.martin@example.com" required />
                    </div>

                    <div class="f-group">
                        <label class="f-label">Mot de passe</label>
                        <div class="f-input-wrapper">
                            <input type="password" class="f-input f-input-pwd" name="password" id="password" placeholder="••••••••" required value="hash_pwd_1" />
                            <span class="f-eye" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggle-icon"></i>
                            </span>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <div class="f-error"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn-primary" style="margin-top:.5rem">
                        Se connecter <i class="bi bi-arrow-right-short"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggle-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
</body>

</html>