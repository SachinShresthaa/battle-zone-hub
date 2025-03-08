<?php
include 'connection.php'; // Make sure the connection path is correct

// Fetch the latest tournament details
$query = "SELECT prize_1st, prize_2nd FROM tournaments ORDER BY date DESC, time DESC LIMIT 1";
$result = $conn->query($query);

$prize1st = "N/A";
$prize2nd = "N/A";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $prize1st = $row['prize_1st'];
    $prize2nd = $row['prize_2nd'];
}
?>
<style>
    .price-container {
    display: flex;
    justify-content: space-around;
    gap: 20px;
    padding: 20px;
    background:  #243b55;
    border-radius: 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
    flex-wrap: wrap;
}

.price-item {
    flex: 1;
    min-width: 250px;
    max-width: 300px;
    text-align: center;
    padding: 20px;
    border-radius: 20px;
    background:  #182635;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.7);
}


.price-item h3 {
    font-size: 2.5em;
    margin-bottom: 10px;
    color:#d4d2d2;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
}

.price-item p {
    font-size: 2em;
    color: #4ecdc4;
    font-weight: bold;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
}

@keyframes shine {
    0% {
        top: -100%;
        left: -100%;
    }
    100% {
        top: 100%;
        left: 100%;
    }
}

    </Style>
<div class="content-container">
    <div class="price-container">
        <div class="price-item">
            <h3>1st Place</h3>
            <p>Rs<?php echo htmlspecialchars($prize1st); ?></p>
        </div>
        <div class="price-item">
            <h3>2nd Place</h3>
            <p>Rs<?php echo htmlspecialchars($prize2nd); ?></p>
        </div>
        <div class="price-item">
            <h3>Man of the Match</h3>
            <p>Rs1000.00</p>
        </div>
    </div>
</div>

