<?php
session_start();

$perguntas = [
    [
        "pergunta" => "Em que ano foi lançado o primeiro PlayStation?",
        "opcoes" => ["1990", "1994", "1996"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual empresa desenvolveu o PlayStation?",
        "opcoes" => ["Nintendo", "Sony", "Sega"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual é o nome do controle clássico do PlayStation?",
        "opcoes" => ["DualShock", "Pro Controller", "Joy-Con"],
        "resposta" => 0
    ],
    [
        "pergunta" => "Qual jogo foi o mais vendido do PlayStation 2?",
        "opcoes" => ["GTA: San Andreas", "Gran Turismo 3", "God of War II"],
        "resposta" => 0
    ],
    [
        "pergunta" => "Qual é o serviço de assinatura online da Sony?",
        "opcoes" => ["Xbox Live", "PlayStation Plus", "Game Pass"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual foi o primeiro console PlayStation a ter suporte a Blu-ray?",
        "opcoes" => ["PlayStation 2", "PlayStation 3", "PlayStation 4"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual desses jogos é exclusivo do PlayStation?",
        "opcoes" => ["Halo", "Uncharted", "The Legend of Zelda"],
        "resposta" => 1
    ]
];


// Reinicia o quiz
if (isset($_GET['acao']) && $_GET['acao'] === 'resetar') {
    session_destroy();
    header("Location: index.html");
    exit();
}

// Inicia o quiz
if (!isset($_SESSION['iniciado']) && isset($_GET['acao']) && $_GET['acao'] === 'iniciar') {
    $_SESSION['iniciado'] = true;
    $_SESSION['indice'] = 0;
    $_SESSION['pontuacao'] = 0;
}

// Lógica do quiz
$indice = $_SESSION['indice'] ?? 0;
$totalPerguntas = count($perguntas);
$feedback = $_SESSION['feedback'] ?? null;

// Resposta do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $indice < $totalPerguntas) {
    $respostaUsuario = $_POST['resposta'] ?? null;
    $respostaCorreta = $perguntas[$indice]['resposta'];

    if ($respostaUsuario !== null) {
        if ((int)$respostaUsuario === (int)$respostaCorreta) {
            $_SESSION['pontuacao']++;
            $_SESSION['feedback'] = "🤠 Acertou!";
        } else {
            $_SESSION['feedback'] = "🥴 Errou! A resposta correta era: " .
                $perguntas[$indice]['opcoes'][$respostaCorreta];
        }
    }
    header("Location: index.php");
    exit();
}

// Avançar
if (isset($_GET['acao']) && $_GET['acao'] === 'proxima') {
    $_SESSION['indice']++;
    unset($_SESSION['feedback']);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini Quiz Backend</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(hhttps://i.redd.it/y90ho6dp36t41.jpg);
            background-size: cover;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: rgba(15, 77, 15, 0.8);
            color: #fff;
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #ffb300;
        }
        .botao, button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            border: none;
            background: #008000;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .botao:hover, button:hover {
            background: #006400;
        }
        .feedback {
            margin-top: 15px;
            font-weight: bold;
            color: #ffb300;
        }
        .opcao {
            display: block;
            margin: 8px 0;
        }
    </style>
</head>
<body>
<div class="container">
<?php if ($indice >= $totalPerguntas): ?>
    <h2>QUIZ FINALIZADO!</h2>
    <p>Sua pontuação final foi: <strong><?= $_SESSION['pontuacao'] ?> / <?= $totalPerguntas ?></strong></p>
    <a href="index.php?acao=resetar" class="botao">Reiniciar Quiz</a>
<?php else: ?>
    <h2>Pergunta <?= $indice + 1 ?> de <?= $totalPerguntas ?></h2>
    <p><?= htmlspecialchars($perguntas[$indice]['pergunta'], ENT_QUOTES, 'UTF-8') ?></p>

    <?php if (!$feedback): ?>
        <form method="post">
            <?php foreach ($perguntas[$indice]['opcoes'] as $key => $opcao): ?>
                <label class="opcao">
                    <input type="radio" name="resposta" value="<?= $key ?>" required>
                    <?= htmlspecialchars($opcao, ENT_QUOTES, 'UTF-8') ?>
                </label>
            <?php endforeach; ?>
            <button type="submit">Responder</button>
        </form>
    <?php else: ?>
        <p class="feedback"><?= $feedback ?></p>
        <a href="index.php?acao=proxima" class="botao">Próxima Pergunta</a>
    <?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
