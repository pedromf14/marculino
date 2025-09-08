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
