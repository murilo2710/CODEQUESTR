<?php
// Configuração do cabeçalho para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Conexão com o banco de dados
$host = 'localhost'; // Endereço do banco de dados
$user = 'root'; // Usuário do banco
$password = ''; // Senha do banco
$database = 'db_codequest'; // Nome do banco

$conn = new mysqli($host, $user, $password, $database);

// Verifica se há erro na conexão
if ($conn->connect_error) {
    die(json_encode(['error' => 'Erro ao conectar com o banco de dados.']));
}

// Consulta para calcular a taxa de acerto geral
$queryGeneral = "
    SELECT 
        (SUM(acertos) / SUM(tentativas) * 100) AS taxa_acerto_geral
    FROM respostas
    WHERE tentativas > 0;
";

$resultGeneral = $conn->query($queryGeneral);
$taxaGeral = $resultGeneral->fetch_assoc()['taxa_acerto_geral'] ?? 0;

// Consulta para calcular as taxas de acerto por módulo
$queryModules = "
    SELECT 
        m.nome AS modulo,
        (SUM(r.acertos) / SUM(r.tentativas) * 100) AS taxa_acerto
    FROM respostas r
    JOIN modulos m ON r.modulo_id = m.id
    WHERE r.tentativas > 0
    GROUP BY m.id;
";

$resultModules = $conn->query($queryModules);

// Organiza os resultados em um array associativo
$taxasPorModulo = [];
while ($row = $resultModules->fetch_assoc()) {
    $taxasPorModulo[strtolower($row['modulo'])] = round($row['taxa_acerto'] ?? 0);
}

// Resposta JSON
$response = [
    'general' => round($taxaGeral), // Taxa de acerto geral
    'modules' => $taxasPorModulo   // Taxas por módulo
];



$resultGeneral = $conn->query($queryGeneral);

if (!$resultGeneral) {
    die(json_encode(['error' => 'Erro na consulta geral: ' . $conn->error]));
}



echo json_encode($response); // Retorna os dados em JSON

$conn->close(); // Fecha a conexão

