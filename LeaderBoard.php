<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esports Leaderboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .leaderboard-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .leaderboard-header {
            padding: 20px;
            background: #1a1a2e;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .game-filters {
            display: flex;
            gap: 10px;
        }

        .game-filter {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        .game-filter.active {
            background: #4a90e2;
        }

        .game-filter:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leaderboard-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #444;
            border-bottom: 2px solid #eee;
        }

        .leaderboard-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #eee;
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .player-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eee;
        }

        .player-details {
            display: flex;
            flex-direction: column;
        }

        .player-name {
            font-weight: 600;
            color: #333;
        }

        .player-game {
            font-size: 14px;
            color: #666;
        }

        .rank {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .top-rank {
            color: #ffd700;
        }

        .rank-change {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rank-up {
            color: #2ecc71;
        }

        .rank-down {
            color: #e74c3c;
        }

        .win-streak {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #ff6b6b;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .game-filters {
                display: none;
            }
            
            .leaderboard-table th:nth-child(3),
            .leaderboard-table td:nth-child(3),
            .leaderboard-table th:nth-child(5),
            .leaderboard-table td:nth-child(5) {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="leaderboard-container">
        <div class="leaderboard-header">
            <div class="title">
                Pro Players Leaderboard
            </div>
        </div>
        
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Team</th>
                    <th>Score</th>
                    
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="rank top-rank">
                            #1
                            <span class="rank-change rank-up">↑</span>
                        </div>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar"></div>
                        </div>
                    </td>
                    <td>Team Liquid</td>
                    <td>2,850</td>
                    <td>
                      
                    </td>
                </tr>
                <!-- More rows following the same pattern -->
                <tr>
                    <td>
                        <div class="rank top-rank">
                            #2
                            <span class="rank-change rank-down">↓</span>
                        </div>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar"></div>
                        </div>
                    </td>
                    <td>Cloud9</td>
                    <td>2,780</td>
                    <td>
                   
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>