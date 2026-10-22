<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPlay - Jeopardy Presentation Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700;900&family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        :root {
            --body-bg: #0a0a1f;
            --arcade-pink: #ff006e;
            --arcade-cyan: #00d9ff;
            --arcade-purple: #a855f7;
            --arcade-yellow: #ffd60a;
            --text-color: #e2e8f0;
        }

        @keyframes scanline {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }

        @keyframes pixelPulse {
            0%, 100% { box-shadow: 0 0 10px var(--arcade-purple), 0 0 20px var(--arcade-purple); }
            50% { box-shadow: 0 0 20px var(--arcade-pink), 0 0 40px var(--arcade-pink); }
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: 
                repeating-linear-gradient(0deg, rgba(255, 0, 110, 0.03) 0px, transparent 2px, transparent 4px),
                linear-gradient(135deg, #0a0a1f 0%, #1a0033 50%, #0a0a1f 100%);
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
            padding: 24px 16px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--arcade-cyan), transparent);
            animation: scanline 8s linear infinite;
            opacity: 0.15;
            pointer-events: none;
            z-index: 1000;
        }

        .jeopardy-play-container {
            max-width: 98% !important;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .game-bar {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.15), rgba(236, 72, 153, 0.1));
            border: 2px solid;
            border-image: linear-gradient(135deg, var(--arcade-purple), var(--arcade-pink)) 1;
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
        }

        .game-bar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, transparent 70%);
            animation: pixelPulse 4s ease-in-out infinite;
            pointer-events: none;
        }

        .jeopardy-grid-scroll-wrapper {
            overflow-x: auto;
            width: 100%;
            padding-bottom: 12px;
        }

        /* Responsive Grid Styling */
        #jeopardy-grid-container {
            display: grid;
            grid-template-columns: repeat(var(--cols, 5), 1fr);
            gap: 16px;
            width: 100%;
            min-width: calc(var(--cols, 5) * 160px);
        }

        .jeopardy-category {
            background: linear-gradient(135deg, var(--arcade-purple), var(--arcade-pink));
            border: 3px solid rgba(255, 255, 255, 0.2);
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 0.75rem;
            font-weight: 400 !important;
            color: #ffffff !important;
            text-align: center;
            text-transform: uppercase;
            padding: 20px 10px;
            border-radius: 12px;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.3), 0 8px 20px rgba(168, 85, 247, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70px;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
            line-height: 1.4;
        }

        .jeopardy-cell {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: var(--arcade-yellow);
            border: 3px solid var(--arcade-purple);
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: 400;
            border-radius: 12px;
            min-height: 105px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.3), 0 8px 16px rgba(168, 85, 247, 0.2);
            width: 100%;
            position: relative;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.7);
        }

        .jeopardy-cell::before {
            content: '';
            position: absolute;
            inset: 4px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(255, 0, 110, 0.05), rgba(0, 217, 255, 0.05));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .jeopardy-cell:hover:not(:disabled) {
            transform: translateY(-4px);
            box-shadow: 0 8px 0 rgba(0, 0, 0, 0.3), 0 12px 24px rgba(255, 0, 110, 0.4);
            color: #ffffff;
            border-color: var(--arcade-pink);
        }

        .jeopardy-cell:hover:not(:disabled)::before {
            opacity: 1;
        }

        .jeopardy-cell:active:not(:disabled) {
            transform: translateY(2px);
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.3), 0 6px 12px rgba(255, 0, 110, 0.3);
        }

        .jeopardy-cell:disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .jeopardy-cell.answered-correct {
            background: linear-gradient(135deg, #00ff41, #00b81f) !important;
            color: #000 !important;
            border-color: #00ff41 !important;
            opacity: 1 !important;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.3), 0 8px 20px rgba(0, 255, 65, 0.5);
            text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.3);
        }

        .jeopardy-cell.answered-incorrect {
            background: linear-gradient(135deg, #ff0055, #cc0044) !important;
            color: #fff !important;
            border-color: #ff0055 !important;
            opacity: 1 !important;
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.3), 0 8px 20px rgba(255, 0, 85, 0.5);
        }

        .jeopardy-modal-content {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border: 2px solid;
            border-image: linear-gradient(135deg, var(--arcade-purple), var(--arcade-cyan)) 1;
        }

        #modal-question-text {
            font-size: 2.2rem !important;
            line-height: 1.35;
            font-family: 'Space Grotesk', sans-serif;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.3);
        }

        #modal-answer-text {
            font-size: 1.9rem !important;
            font-family: 'Space Grotesk', sans-serif;
        }

        .arcade-title {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            background: linear-gradient(90deg, var(--arcade-pink), var(--arcade-cyan), var(--arcade-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        .arcade-btn {
            font-family: 'Press Start 2P', 'Courier New', monospace;
            font-size: 0.6rem;
            padding: 12px 20px !important;
            border: 3px solid !important;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .arcade-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 rgba(0, 0, 0, 0.3);
        }

        .arcade-btn:active {
            transform: translateY(2px);
            box-shadow: 0 2px 0 rgba(0, 0, 0, 0.3);
        }

        .score-badge {
            background: linear-gradient(135deg, var(--arcade-purple), var(--arcade-pink)) !important;
            border: 3px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 4px 0 rgba(0, 0, 0, 0.3), 0 6px 16px rgba(168, 85, 247, 0.4);
            font-family: 'Press Start 2P', 'Courier New', monospace !important;
            font-size: 1rem !important;
            text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
        }

        .bg-indigo { background: var(--arcade-purple); }
        .bg-indigo-subtle { background: rgba(168, 85, 247, 0.12); color: #c7d2fe; }

        @media (max-width: 768px) {
            .jeopardy-category { font-size: 0.6rem; min-height: 60px; }
            .jeopardy-cell { font-size: 1.2rem; min-height: 80px; }
            #modal-question-text { font-size: 1.5rem !important; }
            #modal-answer-text { font-size: 1.3rem !important; }
            .arcade-title { font-size: 0.8rem; }
            .arcade-btn { font-size: 0.5rem; padding: 10px 16px !important; }
        }
    </style>
</head>
<body>

<div class="jeopardy-play-container">

    {{-- Distraction-Free Header Bar --}}
    <div class="card border-0 shadow-lg rounded-4 p-4 mb-4 game-bar">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-light rounded-3 d-flex align-items-center justify-content-center px-3 py-2.5 text-decoration-none fs-6 fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                <h1 class="h3 mb-0 fw-black text-white arcade-title">🎓 JEOPARDY</h1>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="badge rounded-4 score-badge px-4 py-3 fw-bold text-white shadow-sm">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>SCORE: <span id="session-score">0</span>
                </div>
                <button type="button" class="btn btn-outline-warning rounded-3 arcade-btn" onclick="resetJeopardyScores()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> RESET
                </button>
                <a href="{{ route('teacher.jeopardy.edit') }}" class="btn btn-info text-dark rounded-3 arcade-btn">
                    <i class="bi bi-pencil-square me-1"></i> EDIT
                </a>
            </div>
        </div>
    </div>

    {{-- The Fullscreen Game Board --}}
    <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.05);">
        <div class="jeopardy-grid-scroll-wrapper">
            <div id="jeopardy-grid-container">
                {{-- Dynamic CSS Grid of 1-10 Categories and 1-10 Rows --}}
            </div>
        </div>
    </div>

</div>

{{-- Clue Display Modal --}}
<div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content rounded-4 border-0 shadow-lg jeopardy-modal-content">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-2 fs-6 uppercase tracking-wide" id="modal-category">Category — $100</span>
                    <h5 class="modal-title fw-black text-white h2 mt-2" id="modal-question-title">Math Clue Problem</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5 px-4">
                <div class="p-4 mb-4 rounded-4" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06);">
                    <p class="fs-5 uppercase tracking-wider mb-3 fw-bold text-info" style="color: #38bdf8 !important;">Clue Prompt</p>
                    <div id="modal-image-wrapper" class="mb-4 d-none">
                        <img id="modal-question-image" src="" alt="Clue image" class="img-fluid rounded-4 shadow-sm border border-secondary" style="max-height: 320px; object-fit: contain;">
                    </div>
                    <p id="modal-question-text" class="fw-bold text-white font-grotesk px-2">Question Text</p>
                </div>

                <div class="collapse" id="answerCollapse">
                    <div class="p-4 mb-4 rounded-4" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25);">
                        <p class="text-success-emphasis fs-5 uppercase tracking-wider mb-2">Correct Answer</p>
                        <p id="modal-answer-text" class="fw-bold text-success">Answer Text</p>
                    </div>
                </div>

                <button type="button" id="btn-reveal-answer" class="btn btn-outline-info rounded-pill px-5 py-3 fw-bold fs-5 mt-2" onclick="toggleAnswerCollapse()">
                    <i class="bi bi-eye-fill me-1"></i> Reveal Correct Answer
                </button>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between p-4" style="background: rgba(0, 0, 0, 0.25);">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2.5 fs-5" data-bs-dismiss="modal">Close</button>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-danger rounded-3 px-5 py-2.5 fw-bold fs-5" onclick="markJeopardyCell(false)">
                        <i class="bi bi-x-circle-fill me-1"></i> Incorrect / No Points
                    </button>
                    <button type="button" class="btn btn-success rounded-3 px-5 py-2.5 fw-bold fs-5" onclick="markJeopardyCell(true)">
                        <i class="bi bi-check-circle-fill me-1"></i> Correct / Add Points
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ── DEFAULT 10x10 JEOPARDY DATA ──
    const defaultJeopardyData = {
        categories: ["Addition", "Subtraction", "Multiplication", "Division", "Algebra", "Geometry", "Fractions", "Decimals", "Word Problems", "Measurement"],
        board: {
            "Addition": {
                "100": { "q": "What is 8 + 7?", "a": "15" },
                "200": { "q": "What is 15 + 18?", "a": "33" },
                "300": { "q": "What is 34 + 27?", "a": "61" },
                "400": { "q": "What is 56 + 39?", "a": "95" },
                "500": { "q": "What is 125 + 87?", "a": "212" },
                "600": { "q": "What is 340 + 290?", "a": "630" },
                "700": { "q": "What is 450 + 550?", "a": "1000" },
                "800": { "q": "What is 789 + 121?", "a": "910" },
                "900": { "q": "What is 1,234 + 5,678?", "a": "6,912" },
                "1000": { "q": "What is 9,999 + 9,999?", "a": "19,998" }
            },
            "Subtraction": {
                "100": { "q": "What is 12 - 5?", "a": "7" },
                "200": { "q": "What is 35 - 17?", "a": "18" },
                "300": { "q": "What is 62 - 28?", "a": "34" },
                "400": { "q": "What is 90 - 43?", "a": "47" },
                "500": { "q": "What is 143 - 76?", "a": "67" },
                "600": { "q": "What is 500 - 185?", "a": "315" },
                "700": { "q": "What is 820 - 460?", "a": "360" },
                "800": { "q": "What is 1000 - 345?", "a": "655" },
                "900": { "q": "What is 4,321 - 1,234?", "a": "3,087" },
                "1000": { "q": "What is 10,000 - 9,999?", "a": "1" }
            },
            "Multiplication": {
                "100": { "q": "What is 6 x 4?", "a": "24" },
                "200": { "q": "What is 8 x 7?", "a": "56" },
                "300": { "q": "What is 12 x 6?", "a": "72" },
                "400": { "q": "What is 14 x 5?", "a": "70" },
                "500": { "q": "What is 15 x 9?", "a": "135" },
                "600": { "q": "What is 20 x 30?", "a": "600" },
                "700": { "q": "What is 25 x 4?", "a": "100" },
                "800": { "q": "What is 12 x 12?", "a": "144" },
                "900": { "q": "What is 11 x 90?", "a": "990" },
                "1000": { "q": "What is 50 x 20?", "a": "1000" }
            },
            "Division": {
                "100": { "q": "What is 16 / 4?", "a": "4" },
                "200": { "q": "What is 42 / 6?", "a": "7" },
                "300": { "q": "What is 72 / 8?", "a": "9" },
                "400": { "q": "What is 96 / 12?", "a": "8" },
                "500": { "q": "What is 108 / 9?", "a": "12" },
                "600": { "q": "What is 150 / 5?", "a": "30" },
                "700": { "q": "What is 240 / 6?", "a": "40" },
                "800": { "q": "What is 360 / 12?", "a": "30" },
                "900": { "q": "What is 720 / 90?", "a": "8" },
                "1000": { "q": "What is 1000 / 250?", "a": "4" }
            },
            "Algebra": {
                "100": { "q": "Find x: x + 5 = 12", "a": "7" },
                "200": { "q": "Find y: 3y = 18", "a": "6" },
                "300": { "q": "Find a: 2a - 4 = 10", "a": "7" },
                "400": { "q": "Find n: n / 4 = 9", "a": "36" },
                "500": { "q": "Find x: 5x + 3 = 28", "a": "5" },
                "600": { "q": "Find x: 2x - 8 = x + 2", "a": "10" },
                "700": { "q": "Find m: 6m / 2 = 18", "a": "6" },
                "800": { "q": "Find k: 4(k - 2) = 24", "a": "8" },
                "900": { "q": "Find x: x^2 = 81 (positive root)", "a": "9" },
                "1000": { "q": "Find z: z/3 + 7 = 15", "a": "24" }
            },
            "Geometry": {
                "100": { "q": "How many sides does a hexagon have?", "a": "6" },
                "200": { "q": "What is the perimeter of a square with a 5cm side?", "a": "20 cm" },
                "300": { "q": "What do you call an angle that is exactly 90 degrees?", "a": "Right angle" },
                "400": { "q": "Find the area of a rectangle with length 8cm and width 6cm.", "a": "48 sq cm" },
                "500": { "q": "What is the sum of the inner angles of a triangle?", "a": "180 degrees" },
                "600": { "q": "How many degrees are in a full circle?", "a": "360" },
                "700": { "q": "Find the area of a right triangle with base 10cm and height 6cm.", "a": "30 sq cm" },
                "800": { "q": "What do you call a triangle with all three sides equal?", "a": "Equilateral" },
                "900": { "q": "How many vertices does a pentagonal prism have?", "a": "10" },
                "1000": { "q": "If radius of circle is 7, find circumference (use pi = 22/7).", "a": "44" }
            },
            "Fractions": {
                "100": { "q": "What is 1/4 + 2/4?", "a": "3/4" },
                "200": { "q": "Simplify the fraction 8/12.", "a": "2/3" },
                "300": { "q": "Which is larger: 2/3 or 3/5?", "a": "2/3" },
                "400": { "q": "What is 1/2 of 50?", "a": "25" },
                "500": { "q": "What is 3/4 - 1/8?", "a": "5/8" },
                "600": { "q": "What is 2/5 of 100?", "a": "40" },
                "700": { "q": "Convert 13/4 into a mixed number.", "a": "3 1/4" },
                "800": { "q": "What is 2/3 x 3/4?", "a": "1/2" },
                "900": { "q": "What is 1/2 divided by 3?", "a": "1/6" },
                "1000": { "q": "What is the sum of 1/2, 1/3, and 1/6?", "a": "1" }
            },
            "Decimals": {
                "100": { "q": "What is 0.5 + 0.3?", "a": "0.8" },
                "200": { "q": "Which is larger: 0.09 or 0.1?", "a": "0.1" },
                "300": { "q": "What is 1.5 x 2?", "a": "3" },
                "400": { "q": "Convert 3/10 to a decimal.", "a": "0.3" },
                "500": { "q": "What is 4.5 - 1.25?", "a": "3.25" },
                "600": { "q": "What is 0.75 + 1.50?", "a": "2.25" },
                "700": { "q": "Convert 0.25 to a simplified fraction.", "a": "1/4" },
                "800": { "q": "What is 12.5 / 5?", "a": "2.5" },
                "900": { "q": "What is 0.1 x 0.1?", "a": "0.01" },
                "1000": { "q": "What is 100 x 0.05?", "a": "5" }
            },
            "Word Problems": {
                "100": { "q": "Sam has 12 apples and eats 4. How many are left?", "a": "8" },
                "200": { "q": "A box holds 8 pencils. How many pencils are in 5 boxes?", "a": "40" },
                "300": { "q": "Lily has $20. She buys a book for $7.50. How much change does she get?", "a": "$12.50" },
                "400": { "q": "A train travels at 60 mph. How far does it go in 3 hours?", "a": "180 miles" },
                "500": { "q": "If 4 pencils cost $2.00, how much do 10 pencils cost?", "a": "$5.00" },
                "600": { "q": "Class has 24 students. 1/3 are girls. How many are boys?", "a": "16" },
                "700": { "q": "A rectangle pool is 10m long and 5m wide. What is its perimeter?", "a": "30m" },
                "800": { "q": "Ben is 12 years old. His sister is half his age. How old will she be in 4 years?", "a": "10" },
                "900": { "q": "A baker made 48 cookies. He sells 3/4 of them. How many are left?", "a": "12" },
                "1000": { "q": "Two numbers sum to 15, and their difference is 3. What are they?", "a": "9 and 6" }
            },
            "Measurement": {
                "100": { "q": "How many centimeters are in 1 meter?", "a": "100" },
                "200": { "q": "How many hours are in 2 days?", "a": "48" },
                "300": { "q": "How many milliliters are in 1.5 liters?", "a": "1500" },
                "400": { "q": "How many grams are in 2.5 kilograms?", "a": "2500" },
                "500": { "q": "How many feet are in 3 yards?", "a": "9" },
                "600": { "q": "A movie starts at 2:15 PM and lasts 1h 45m. When does it end?", "a": "4:00 PM" },
                "700": { "q": "How many ounces are in 1 pound?", "a": "16" },
                "800": { "q": "Convert 5000 meters to kilometers.", "a": "5" },
                "900": { "q": "How many cups are in 1 gallon?", "a": "16" },
                "1000": { "q": "Subtract: 3 hours 15 mins minus 1 hour 45 mins.", "a": "1 hour 30 mins" }
            }
        }
    };

    let jeopardyData = JSON.parse(localStorage.getItem('eduplay_jeopardy_data')) || defaultJeopardyData;
    let jeopardyState = JSON.parse(localStorage.getItem('eduplay_jeopardy_state')) || { score: 0, answered: {} };
    let currentSelectedCell = null;

    // Helper to ensure data structure is perfect up to 10 columns and 10 rows
    function ensureJeopardyDataStructure(data) {
        if (!data.categories) {
            data.categories = [...defaultJeopardyData.categories];
        }
        
        // Ensure 10 categories exist
        while (data.categories.length < 10) {
            data.categories.push(defaultJeopardyData.categories[data.categories.length] || `Category ${data.categories.length + 1}`);
        }

        if (!data.board) {
            data.board = {};
        }

        if (typeof data.numCategories === "undefined") {
            data.numCategories = 5;
        }
        if (typeof data.numRows === "undefined") {
            data.numRows = 5;
        }

        // Populate clue matrices
        data.categories.forEach(cat => {
            if (!data.board[cat]) {
                data.board[cat] = {};
            }
            for (let r = 1; r <= 10; r++) {
                const points = r * 100;
                if (!data.board[cat][points]) {
                    // Fall back to default question or a blank one
                    const defaultClue = defaultJeopardyData.board[cat] && defaultJeopardyData.board[cat][points];
                    data.board[cat][points] = defaultClue ? { ...defaultClue } : {
                        q: `Solve a mathematical problem about ${cat} for $${points}.`,
                        a: "Answer"
                    };
                }
            }
        });
    }

    // Run on startup
    document.addEventListener("DOMContentLoaded", () => {
        ensureJeopardyDataStructure(jeopardyData);
        // Save back to ensure structure persistence
        localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));

        renderJeopardyGrid();
        updateJeopardyScoreBoard();
    });

    // Render the grid using CSS Grid
    function renderJeopardyGrid() {
        const container = document.getElementById('jeopardy-grid-container');
        if (!container) return;

        const numCats = jeopardyData.numCategories;
        const numRows = jeopardyData.numRows;

        // Apply dynamic grid layout columns
        container.style.setProperty('--cols', numCats);
        container.innerHTML = "";

        // Row 0: Category Headers
        for (let c = 0; c < numCats; c++) {
            const cat = jeopardyData.categories[c];
            const header = document.createElement('div');
            header.className = "jeopardy-category shadow-sm";
            header.textContent = cat;
            container.appendChild(header);
        }

        // Rows 1-10: Clues
        for (let r = 1; r <= numRows; r++) {
            const points = r * 100;
            for (let c = 0; c < numCats; c++) {
                const cat = jeopardyData.categories[c];
                
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = "btn jeopardy-cell";
                btn.textContent = `$${points}`;

                const answeredKey = `${cat}-${points}`;
                if (jeopardyState.answered[answeredKey]) {
                    btn.disabled = true;
                    btn.classList.add(jeopardyState.answered[answeredKey] === 'correct' ? 'answered-correct' : 'answered-incorrect');
                } else {
                    btn.onclick = () => openJeopardyClue(cat, points);
                }

                container.appendChild(btn);
            }
        }
    }

    // Open play modal
    function openJeopardyClue(category, points) {
        currentSelectedCell = { category, points };
        const clue = jeopardyData.board[category][points];

        document.getElementById('modal-category').textContent = `${category} — $${points}`;
        document.getElementById('modal-question-text').textContent = clue.q;
        document.getElementById('modal-answer-text').textContent = clue.a;

        // Image check
        const imgWrapper = document.getElementById('modal-image-wrapper');
        const imgEl = document.getElementById('modal-question-image');
        if (clue.img) {
            imgEl.src = clue.img;
            imgWrapper.classList.remove('d-none');
        } else {
            imgEl.src = "";
            imgWrapper.classList.add('d-none');
        }

        // Reset collapse state
        const answerCollapse = document.getElementById('answerCollapse');
        const collapseInstance = bootstrap.Collapse.getInstance(answerCollapse) || new bootstrap.Collapse(answerCollapse, {toggle: false});
        collapseInstance.hide();
        document.getElementById('btn-reveal-answer').classList.remove('d-none');

        new bootstrap.Modal(document.getElementById('questionModal')).show();
    }

    function toggleAnswerCollapse() {
        const answerCollapse = document.getElementById('answerCollapse');
        const collapseInstance = bootstrap.Collapse.getInstance(answerCollapse) || new bootstrap.Collapse(answerCollapse, {toggle: false});
        collapseInstance.show();
        document.getElementById('btn-reveal-answer').classList.add('d-none');
    }

    function markJeopardyCell(isCorrect) {
        if (!currentSelectedCell) return;
        const key = `${currentSelectedCell.category}-${currentSelectedCell.points}`;

        jeopardyState.answered[key] = isCorrect ? 'correct' : 'incorrect';
        if (isCorrect) {
            jeopardyState.score += currentSelectedCell.points;
        }

        localStorage.setItem('eduplay_jeopardy_state', JSON.stringify(jeopardyState));
        updateJeopardyScoreBoard();
        renderJeopardyGrid();

        bootstrap.Modal.getInstance(document.getElementById('questionModal')).hide();
    }

    function updateJeopardyScoreBoard() {
        const scoreEl = document.getElementById('session-score');
        if (scoreEl) scoreEl.textContent = jeopardyState.score;
    }

    async function resetJeopardyScores() {
        const result = await confirmAction("Reset the score to $0 and restore all board cell buttons?", "Reset Board");
        if (result) {
            jeopardyState = { score: 0, answered: {} };
            localStorage.setItem('eduplay_jeopardy_state', JSON.stringify(jeopardyState));
            updateJeopardyScoreBoard();
            renderJeopardyGrid();
        }
    }

    // Confirmation modal functions
    let confirmModalResolve = null;
    
    function confirmAction(message, title = 'Confirm Action') {
        return new Promise((resolve) => {
            confirmModalResolve = resolve;
            
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').style.display = 'flex';
        });
    }
    
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
        if (confirmModalResolve) {
            confirmModalResolve(false);
            confirmModalResolve = null;
        }
    }
    
    function handleConfirm() {
        document.getElementById('confirmModal').style.display = 'none';
        if (confirmModalResolve) {
            confirmModalResolve(true);
            confirmModalResolve = null;
        }
    }
    
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeConfirmModal();
    });
</script>

<!-- Confirmation Modal -->
<div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;">
  <div id="confirmBackdrop" style="position:absolute;inset:0;background:rgba(4,2,10,.85);backdrop-filter:blur(8px);z-index:1;" onclick="closeConfirmModal()"></div>
  <div style="position:relative;z-index:2;background:linear-gradient(145deg,#1a1a2e,#16213e);border:2px solid;border-image:linear-gradient(135deg,var(--arcade-purple),var(--arcade-cyan)) 1;border-radius:20px;padding:36px 32px;max-width:420px;width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.8);">
    <div style="width:60px;height:60px;border-radius:50%;background:rgba(251,191,36,.2);border:2px solid rgba(251,191,36,.4);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
      <i class="bi bi-exclamation-triangle-fill" style="font-size:1.6rem;color:#fbbf24;"></i>
    </div>
    <h5 id="confirmTitle" style="font-family:'Press Start 2P','Courier New',monospace;font-weight:400;color:#fff;margin-bottom:12px;font-size:0.85rem;letter-spacing:1px;">CONFIRM ACTION</h5>
    <p id="confirmMessage" style="color:rgba(255,255,255,.7);font-size:.95rem;margin-bottom:28px;line-height:1.6;">Are you sure you want to proceed?</p>
    <div style="display:flex;gap:12px;justify-content:center;position:relative;z-index:3;">
      <button type="button" onclick="closeConfirmModal()" style="flex:1;max-width:150px;padding:12px 0;border-radius:10px;border:2px solid rgba(255,255,255,.15);background:rgba(255,255,255,.08);color:rgba(255,255,255,.7);font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;font-family:'Press Start 2P','Courier New',monospace;"
        onmouseover="this.style.background='rgba(255,255,255,.15)'" onmouseout="this.style.background='rgba(255,255,255,.08)'">CANCEL</button>
      <button type="button" id="confirmButton" onclick="handleConfirm()" style="flex:1;max-width:150px;padding:12px 0;border-radius:10px;border:2px solid rgba(16,185,129,.5);background:linear-gradient(135deg,rgba(16,185,129,.25),rgba(16,185,129,.15));color:#34d399;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;font-family:'Press Start 2P','Courier New',monospace;"
        onmouseover="this.style.background='linear-gradient(135deg,rgba(16,185,129,.35),rgba(16,185,129,.25))'" onmouseout="this.style.background='linear-gradient(135deg,rgba(16,185,129,.25),rgba(16,185,129,.15))'"><i class="bi bi-check-circle-fill me-2"></i>CONFIRM</button>
    </div>
  </div>
</div>

</body>
</html>
