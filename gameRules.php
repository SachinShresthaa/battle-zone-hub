<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournament Rules</title>
    <style>
        .rule-section {
            border-bottom: 1px solid #eee;
        }
        .rule-title {
            font-size:22px;
            color: #d4d2d2;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
            font-weight:200px;
        }

        .rule-content {
            font-size:18px;
            max-height: 0;
            overflow: hidden;
            color:#ff6b6b;
            transition: max-height 0.3s ease-out;
            background-color:#182635;
            padding: 0; 
            border-top: 1px solid #eee; 
        }

        .rule-content.active {
            max-height: 1000px;
            transition: max-height 0.5s ease-in;
        }

        .rule-points {
            padding: 20px;
        }

        .rule-points ul {
            padding-left: 20px;
        }

        .rule-points li {
            margin: 10px 0;
            line-height: 1.6;
        }

        .sub-section {
            margin-top: 10px;
            padding-left: 20px;
        }

        .highlight {
            color: #d32f2f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
    
        <div class="rules-container">
            <div class="rule-section">
                <div class="rule-title">Basic Requirements</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li>Team size: 4 players</li>
                            <li>Valid game ID required</li>
                            <li>Only registered players are allowed</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rule-section">
                <div class="rule-title">Tournament Format</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li> Best of 4 matches</li>
                            <li>Points are cumulative across all matches</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rule-section">
                <div class="rule-title">Scoring System</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li>Position Points:
                                <div class="sub-section">
                                    - 1st Place: 15 points<br>
                                    - 2nd Place: 12 points<br>
                                    - 3rd Place: 10 points<br>
                                    - 4th Place: 8 points<br>
                                    - 5th Place: 6 points<br>
                                    - 6th-7th: 4 points<br>
                                    - 8th-12th: 2 points<br>
                                    - 13th-16th: 1 point
                                </div>
                            </li>
                            <li>Kill Points: 1 point per elimination</li>
                            <li>Total Score = Position Points + Kill Points</li>
                            <li>Man of the match = highest kill player</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rule-section">
                <div class="rule-title">Prohibited Actions</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li class="highlight">Use of hacks, cheats, or modified clients</li>
                            <li class="highlight">Teaming with other squads</li>
                            <li class="highlight">Use of emulators or external devices</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rule-section">
                <div class="rule-title">Match Day Rules</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li>Check-in required 30 minutes before match</li>
                            <li>Maximum wait time: 10 minutes</li>
                            <li>All players must be in lobby 5 minutes before start</li>
                            <li>Only registered players and account</li>
                            <li>Room ID & password will be shared 10 minutes before match</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="rule-section">
                <div class="rule-title">Penalties</div>
                <div class="rule-content">
                    <div class="rule-points">
                        <ul>
                            <li>First violation: Warning</li>
                            <li>Second violation: Match disqualification</li>
                            <li>Third violation: Tournament disqualification</li>
                            <li>Cheating: Immediate disqualification and permanent ban</li>
                            <li>Late arrival: Point deductions or disqualification</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.rule-title').forEach(title => {
            title.addEventListener('click', () => {
                title.classList.toggle('active');
                const content = title.nextElementSibling;
                content.classList.toggle('active');
            });
        });
    </script>
</body>
</html>