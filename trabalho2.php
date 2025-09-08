<?php
session_start();

$perguntas = [
    [
        "pergunta" => "Qual clã pertence Shikamaru?", 
        "opcoes" => ["Uzumaki", "Nara", "Senju"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Quem é o Sensei de Shikamaru?",
        "opcoes" => ["Kakashi", "Asuma", "Gai"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual Jutsu Shikamaru domina?",
        "opcoes" => ["Controle das Sombras", "Jutsu Bola de Fogo", "Clone de Água"],
        "resposta" => 0
    ],
    [
        "pergunta" => "Quem é o pai de Skikamaru?",
        "opcoes" => ["Minato", "Shikaku", "Shino"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual time pertence Skikamaru?",
        "opcoes" => ["Time 10", "Time 7", "Time 8"],
        "resposta" => 0
    ],
    [
        "pergunta" => "Quem Skikamaru se casa?",
        "opcoes" => ["Tenten", "Temari", "Hinata"],
        "resposta" => 1
    ],
    [
        "pergunta" => "Qual membro da Akatsuki Shikamaru derrota?",
        "opcoes" => ["Hidan", "Kakuzu", "Deidara"],
        "resposta" => 0
    ]
];

if (isset($_GET['acao']) && $_GET['acao'] === 'resetar') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['iniciado'])) {
    if (isset($_GET['acao']) && $_GET['acao'] === 'iniciar') {
        $_SESSION['iniciado'] = true;
        $_SESSION['indice'] = 0;
        $_SESSION['pontuacao'] = 0;
        unset($_SESSION['feedback']);
        header("Location: index.php");
        exit();
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Mini Quiz Backend</title>
            <style>
body { 
    font-family: Arial, sans-serif; 
    background: #0F4D0F; 
    text-align: center; 
    margin: 0; 
}
.container { 
    background: #b3a1a1; 
    width: 60%; 
    max-width: 800px;
    margin: 100px auto; 
    padding: 40px; 
    border-radius: 10px; 
    box-shadow: 0 0 10px #ccc; 
    display: flex;
    flex-direction: column;
    gap: 20px; 
    align-items: center; 
}

h1 { 
    color: #008000; 
}
.botao { 
    display: inline-block; 
    padding: 12px 20px; 
    border: none; 
    background: #0F4D0F; 
    color: #fff; 
    font-size: 18px; 
    border-radius: 5px; 
    text-decoration: none; 
    cursor: pointer; 
    transition: background 0.3s;
}
.botao:hover { 
    background: #2980b9; 
}
            </style>
        </head>
        <body>
        <div class="container">
            <h1>Bem vindo ao Quiz sobre o Shikamaru Nara!</h1>
            <p>Clique no botão abaixo para começar!</p>
            <a href="index.php?acao=iniciar" class="botao">Iniciar Quiz</a>
        </div>
        </body>
        </html>
        <?php
        exit();
    }
}

if (!isset($_SESSION['indice'])) $_SESSION['indice'] = 0;
if (!isset($_SESSION['pontuacao'])) $_SESSION['pontuacao'] = 0;

$indice = $_SESSION['indice'];
$totalPerguntas = count($perguntas);

if (isset($_GET['acao']) && $_GET['acao'] === 'proxima') {
    if (isset($_SESSION['feedback'])) {
        $_SESSION['indice']++;
        unset($_SESSION['feedback']); 
    }
    header("Location: index.php");
    exit();
}

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

$feedback = $_SESSION['feedback'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Mini Quiz Backend</title>
    <style>
        body { font-family: Arial, sans-serif; background-image: url(https://i0.wp.com/borutoexplorer.com.br/wp-content/uploads/2020/09/%F0%9D%90%A7%F0%9D%90%9A%F0%9D%90%AB%F0%9D%90%AE%F0%9D%90%AD%F0%9D%90%A8-%F0%9D%90%A9%F0%9D%90%AB%F0%9D%90%9E%F0%9D%90%9F%F0%9D%90%9E%F0%9D%90%AB%F0%9D%90%9E%F0%9D%90%A7%F0%9D%90%9C%F0%9D%90%A3%F0%9D%90%9E-%E2%98%BE-%E2%98%91-Kiedy-jest-pijany.jpg?resize=720%2C404&ssl=1 ); background-size: cover; text-align:center; margin:0; padding:0; display: flex; justify-content: center; align-items: center; height: 100vh;}
        .container { background:#0F4D0F; width:60%; margin:50px auto; padding:20px; border-radius:10px;}
        h2 { color:#008000; }
        .botao, button { display:inline-block; margin-top:15px; padding:10px 15px; border:none; background:#008000; color:#fff; border-radius:5px; text-decoration:none; cursor:pointer; }
        .botao:hover, button:hover { background:#008000; }
        .feedback { margin-top:15px; font-weight:bold; }
        .opcao { display:block; margin:8px 0; }
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