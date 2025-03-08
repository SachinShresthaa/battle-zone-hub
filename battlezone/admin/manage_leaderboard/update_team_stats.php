<?php
include "../connection.php";

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$teamName = $data['teamName'];
$game = $data['game'];
$kills = intval($data['kills']);
$points = intval($data['points']);

// Choose the appropriate table based on game
if ($game === 'PUBG') {
    $table = "pubg_team_registration";
} elseif ($game === 'FreeFire') {
    $table = "ff_team_registration";
} else {
    echo json_encode(["success" => false, "message" => "Invalid game type"]);
    exit;
}

// Prepare and execute the update query
$stmt = $conn->prepare("UPDATE $table SET kills = ?, points = ? WHERE team_name = ?");
$stmt->bind_param("iis", $kills, $points, $teamName);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
