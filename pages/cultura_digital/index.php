<?php
session_start();

$SENHA_PRIVADA = 'cultura2026';

// Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['cultura_digital_auth']);
    header('Location: index.php');
    exit;
}

// Verifica login
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha_acesso'])) {
    if ($_POST['senha_acesso'] === $SENHA_PRIVADA) {
        $_SESSION['cultura_digital_auth'] = true;
        header('Location: index.php');
        exit;
    } else {
        $erro = 'Senha incorreta!';
    }
}

$is_auth = !empty($_SESSION['cultura_digital_auth']);

$dataFile = __DIR__ . '/data.json';
$activities = json_decode(file_get_contents($dataFile), true);

$driveLink = "https://drive.google.com/drive/folders/1dbMg1Z_KVbIDujy2RF3NC6R4aTVlXpO6?usp=sharing";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#05060a" />
    <title>Projeto EJA — Caminhos para o Ensino Superior e Educação Profissional</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;800&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <link rel="stylesheet" href="../../assets/css/tokens.css" />
    <link rel="stylesheet" href="../../assets/css/base.css" />
    <link rel="stylesheet" href="../../assets/css/layout.css" />
    <link rel="stylesheet" href="../../assets/css/components.css" />
    <link rel="stylesheet" href="../../assets/css/animations.css" />

    <style>
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        .activity-card {
            background: #111420;
            border: 1px solid #222940;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        .activity-card:hover {
            transform: translateY(-5px);
            border-color: #00f2fe;
            box-shadow: 0 8px 24px rgba(0, 242, 254, 0.15);
        }
        .activity-card h3 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            color: #ffffff;
            margin: 0;
            border-bottom: 1px solid #1f2740;
            padding-bottom: 0.6rem;
        }
        .info-row {
            font-size: 0.9rem;
            color: #c0caf5;
        }
        .info-label {
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
            color: #7aa2f7;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05rem;
        }
        .info-value {
            color: #00f2fe;
            font-weight: 600;
        }
        .obs-text {
            font-style: italic;
            font-size: 0.85rem;
            background: #181d30;
            color: #e0af68;
            padding: 0.6rem;
            border-radius: 0.4rem;
            border-left: 3px solid #00f2fe;
            margin-top: 0.3rem;
            white-space: pre-wrap;
        }
        .assignment-form {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            margin-top: auto;
            padding: 1.2rem;
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.05) 0%, rgba(79, 172, 254, 0.05) 100%);
            border: 1px solid #2a3755;
            border-radius: 0.8rem;
        }
        .form-row {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }
        .form-group {
            width: 100%;
        }
        .form-group input, .assignment-form textarea {
            width: 100%;
            background: #090c15;
            border: 1px solid #2e3859;
            border-radius: 0.5rem;
            padding: 0.7rem 0.9rem;
            color: #ffffff;
            font-size: 0.85rem;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input::placeholder, .assignment-form textarea::placeholder {
            color: #617196;
        }
        .form-group input:focus, .assignment-form textarea:focus {
            border-color: #00f2fe;
            background: #0d1220;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 242, 254, 0.2);
        }
        .assignment-form textarea {
            resize: vertical;
            min-height: 80px;
        }
        .assignment-form button {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #05060a;
            border: none;
            border-radius: 0.5rem;
            padding: 0.9rem;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.03rem;
            transition: opacity 0.2s, transform 0.1s;
            width: 100%;
        }
        .assignment-form button:hover {
            opacity: 0.95;
            transform: scale(1.01);
        }
        .assignment-form button:active {
            transform: scale(0.98);
        }
        .project-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .login-box {
            max-width: 400px;
            margin: 4rem auto;
            background: var(--surface-2);
            padding: 2.5rem;
            border-radius: 1rem;
            border: 1px solid var(--border-subtle);
            text-align: center;
        }
        .login-box input {
            width: 100%;
            padding: 0.8rem;
            margin: 1rem 0;
            background: var(--surface-3);
            border: 1px solid var(--border-subtle);
            border-radius: 0.5rem;
            color: white;
        }
        .login-box button {
            width: 100%;
            padding: 0.8rem;
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
        }
        .site-header {
            background: #05060a;
            padding: 1rem 0;
        }
        .nav-list {
            display: flex;
            justify-content: center;
            gap: 2rem;
            list-style: none;
            padding: 0;
        }
        .nav-list a {
            color: #c0caf5;
            text-decoration: none;
            transition: color 0.2s;
        }
        .nav-list a:hover {
            color: #00f2fe;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
        }
        .brand-mark {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00f2fe;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <canvas id="bg-canvas" aria-hidden="true"></canvas>
    <div class="page-veil" aria-hidden="true"></div>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="../../index.html">
                <span class="brand-mark">∑</span>
                <span class="brand-name">HEDIGAR</span>
            </a>
            <nav class="site-nav">
                <ul class="nav-list">
                    <li><a href="../../index.html">Início</a></li>
                    <li><a href="../../index.php">Turmas</a></li>
                    <?php if ($is_auth): ?>
                        <li><a href="?logout=1" style="color: #ff6b6b;"><i class="fa-solid fa-right-from-bracket"></i> Sair</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container" style="padding-top: 8rem; padding-bottom: 6rem;">
        <div class="project-header reveal" data-reveal>
            <p class="section-kicker">EJA - Barão de Tramandaí</p>
            <h1 class="hero-title" style="font-size: 2rem; line-height: 1.2;">CAMINHOS PARA O ENSINO SUPERIOR E A EDUCAÇÃO PROFISSIONAL</h1>
            <p class="hero-subtitle">Espaço Integrado de Colaboração Interdisciplinar e Atividades Metodológicas (2026/2)</p>
        </div>

        <?php if (!$is_auth): ?>
            <!-- Form de senha de acesso -->
            <div class="login-box reveal" data-reveal>
                <i class="fa-solid fa-lock" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
                <h2>Área Restrita</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Digite a senha simples do projeto para visualizar e editar.</p>
                
                <?php if ($erro): ?>
                    <p style="color: #ff6b6b; font-size: 0.9rem; margin-top: 0.5rem;"><?= htmlspecialchars($erro) ?></p>
                <?php endif; ?>

                <form method="POST">
                    <input type="password" name="senha_acesso" placeholder="Senha de acesso" required autofocus>
                    <button type="submit">Entrar no Espaço</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Conteúdo Protegido -->
            <div class="activity-grid">
                <?php foreach ($activities as $act): ?>
                <article class="activity-card reveal" data-reveal>
                    <h3><?= htmlspecialchars($act['activity']) ?></h3>
                    
                    <div class="info-row">
                        <span class="info-label">Professor(a)</span>
                        <span class="info-value"><?= $act['professor'] ? htmlspecialchars($act['professor']) : '<span style="font-style: italic; color: var(--text-muted);">Aguardando...</span>' ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Data de Aplicação</span>
                        <span class="info-value"><?= $act['applied_at'] ? htmlspecialchars($act['applied_at']) : '--/--/----' ?></span>
                    </div>

                    <?php if (!empty($act['observations'])): ?>
                    <div class="info-row">
                        <span class="info-label">Observações</span>
                        <div class="obs-text"><?= nl2br(htmlspecialchars($act['observations'])) ?></div>
                    </div>
                    <?php endif; ?>

                    <form class="assignment-form" action="update.php" method="POST">
                        <input type="hidden" name="id" value="<?= $act['id'] ?>">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="professor" placeholder="Seu nome" value="<?= htmlspecialchars($act['professor']) ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" name="applied_at" placeholder="Data (ex: 02/10)" value="<?= htmlspecialchars($act['applied_at']) ?>">
                            </div>
                        </div>
                        <textarea name="observations" placeholder="Observações..." rows="2"><?= htmlspecialchars($act['observations']) ?></textarea>
                        <button type="submit">Salvar Alterações</button>
                    </form>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="drive-box reveal" data-reveal>
                <i class="fa-brands fa-google-drive"></i>
                <h2>Envio de Arquivos</h2>
                <p>Clique no botão abaixo para acessar a pasta compartilhada no Google Drive e enviar seus materiais.</p>
                <a href="<?= $driveLink ?>" target="_blank" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Acessar Google Drive
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© Hedigar - Projeto Cultura Digital EJA 2026</p>
        </div>
    </footer>

    <script src="../../assets/js/background.js" defer></script>
    <script src="../../assets/js/animations.js" defer></script>
    <script src="../../assets/js/main.js" defer></script>
</body>
</html>