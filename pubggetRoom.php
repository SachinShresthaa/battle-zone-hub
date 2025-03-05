<?php
include 'connection.php';
include "headerwithprofile.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's registered PUBG tournament
$query = "SELECT t.name AS tournament_name, t.date, t.time, t.thumbnail, r.room_id, r.room_password, r.description
          FROM pubg_team_registration reg
          INNER JOIN tournaments t ON reg.tournament_id = t.id
          INNER JOIN room_details r ON t.category = r.category
          WHERE reg.user_id = ? AND t.category = 'pubg'
          ORDER BY t.date DESC LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// PUBG specific colors
$primaryColor = '#f1c40f';    // Yellow
$secondaryColor = '#e67e22';  // Orange
$accentColor = '#d35400';     // Dark Orange
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUBG Tournament Room Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: <?php echo $primaryColor; ?>;
            --secondary-color: <?php echo $secondaryColor; ?>;
            --accent-color: <?php echo $accentColor; ?>;
            --background-dark: #121212;
            --card-bg: #1e1e1e;
            --text-light: #ffffff;
            --text-faded: #b3b3b3;
        }
        
        body {
            background-color: var(--background-dark);
            color: var(--text-light);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: radial-gradient(circle at 10% 20%, rgba(0, 0, 0, 0.8) 0%, var(--background-dark) 90%);
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .room-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .room-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }
        
        .card-header {
            background-color: rgba(0, 0, 0, 0.3);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header h2 {
            color: var(--primary-color);
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .tournament-name {
            font-size: 1.3rem;
            color: var(--text-light);
            margin: 0;
            padding: 20px 30px;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .room-details {
            padding: 25px 30px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
        }
        
        .detail-row:last-child {
            margin-bottom: 0;
        }
        
        .detail-label {
            flex: 0 0 140px;
            color: var(--text-faded);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-value {
            flex: 1;
            font-size: 1.1rem;
            font-weight: 500;
            padding-left: 15px;
            position: relative;
        }
        
        .credential-value {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .copy-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 1rem;
            padding: 5px;
            transition: all 0.2s ease;
        }
        
        .copy-btn:hover {
            color: var(--text-light);
            transform: scale(1.1);
        }
        
        .description-box {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text-faded);
            border-left: 3px solid var(--primary-color);
        }
        
        .card-footer {
            padding: 20px 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: center;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }
        
        .no-room {
            text-align: center;
            padding: 60px 30px;
        }
        
        .no-room i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            opacity: 0.7;
        }
        
        .no-room p {
            font-size: 1.2rem;
            color: var(--text-faded);
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
                margin: 20px auto;
            }
            
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
                margin-bottom: 15px;
            }
            
            .detail-label {
                flex: initial;
                margin-bottom: 5px;
            }
            
            .detail-value {
                padding-left: 0;
                width: 100%;
            }
            
            .card-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="room-card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-gamepad" style="color: var(--primary-color);"></i>
                    PUBG Tournament Room
                </h2>
                <div class="date-time">
                    <?php if ($row): ?>
                        <i class="far fa-calendar-alt" style="color: var(--primary-color); margin-right: 5px;"></i>
                        <?php echo date('d M Y', strtotime($row['date'])); ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($row): ?>
                <div class="tournament-name">
                    <i class="fas fa-trophy" style="color: var(--primary-color); margin-right: 10px;"></i>
                    <?php echo htmlspecialchars($row['tournament_name']); ?>
                </div>
                
                <div class="room-details">
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="far fa-clock" style="color: var(--primary-color);"></i> Time
                        </div>
                        <div class="detail-value">
                            <?php echo htmlspecialchars($row['time']); ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-fingerprint" style="color: var(--primary-color);"></i> Room ID
                        </div>
                        <div class="detail-value">
                            <div class="credential-value">
                                <?php echo htmlspecialchars($row['room_id']); ?>
                                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['room_id']); ?>')" title="Copy to clipboard">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-key" style="color: var(--primary-color);"></i> Password
                        </div>
                        <div class="detail-value">
                            <div class="credential-value">
                                <?php echo htmlspecialchars($row['room_password']); ?>
                                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($row['room_password']); ?>')" title="Copy to clipboard">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">
                            <i class="fas fa-info-circle" style="color: var(--primary-color);"></i> Description
                        </div>
                        <div class="detail-value">
                            <div class="description-box">
                                <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <a href="viewDetails.php" class="btn">
                        <i class="fas fa-list-ul"></i> View Tournament Details
                    </a>
                </div>
            <?php else: ?>
                <div class="no-room">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>No room details available for this tournament.</p>
                    <a href="tournaments.php" class="btn">
                        <i class="fas fa-search"></i> Browse Tournaments
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            
            // Show feedback
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.color = '#2ecc71';
            
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.color = '';
            }, 1500);
        }
    </script>
</body>
</html>

<?php
$stmt->close();
?>