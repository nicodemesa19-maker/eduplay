const fs = require('fs');

// ==========================================
// 1. UPDATE ROCKET MATH
// ==========================================
(function updateRocketMath() {
    const file = 'resources/views/games/rocket_math.blade.php';
    let content = fs.readFileSync(file, 'utf8');

    // 1. In init(), always call rmOpenTut(false) and do NOT auto-start the game
    const oldInitRegex = /if\s*\(!localStorage\.getItem\(RM_TUTORIAL_KEY\)\)\s*\{[\s\S]*?resetRound\(\);\s*\}/;
    const newInitCode = `// Hide pause button until mission actually begins
            const pb = document.getElementById('pauseBtn');
            if (pb) pb.style.display = 'none';

            // ALWAYS show instructions flashcard deck before proceeding to the game
            rmOpenTut(false);`;

    if (oldInitRegex.test(content)) {
        content = content.replace(oldInitRegex, newInitCode);
        console.log('Rocket Math: replaced localStorage check in init with rmOpenTut(false)');
    } else {
        console.log('Rocket Math: oldInitRegex not matched, checking init content');
    }

    // 2. In handleCanisterClick, ensure canisters cannot be attached if game has not started yet
    content = content.replace(
        'if (GameState.isGameOver || GameState.success || GameState.isPaused) return;',
        'if (!GameState.gameStarted || GameState.isGameOver || GameState.success || GameState.isPaused) return;'
    );

    // 3. In rmCloseTut, ensure closing the instructions before game starts always starts the real game
    const oldCloseTut = /function rmCloseTut\(andStartGame = false\)\s*\{[\s\S]*?function rmShowTrialOverlay/;
    const newCloseTut = `function rmCloseTut(andStartGame = false) {
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.classList.remove('show');

            if (rmTutOpenedFromGame) {
                if (GameState.isPaused) togglePause();
            } else {
                // User closed the initial instruction deck -> proceed directly to real mission!
                rmStartRealGame();
            }
        }

        function rmShowTrialOverlay`;
    content = content.replace(oldCloseTut, newCloseTut);

    fs.writeFileSync(file, content, 'utf8');
    console.log('Rocket Math: updated successfully');
})();

// ==========================================
// 2. UPDATE MATH CARD MATCH
// ==========================================
(function updateMathCardMatch() {
    const file = 'resources/views/games/math_card_match.blade.php';
    let content = fs.readFileSync(file, 'utf8');

    // In closeTut, ensure closing before game starts always starts the game cleanly
    const oldCloseTutMcm = /window\.closeTut\s*=\s*function\(andStartGame\s*=\s*false\)\s*\{[\s\S]*?\/\* ═══════════════════════════════════════════════════════\s*TRIAL GAME/;
    const newCloseTutMcm = `window.closeTut = function(andStartGame = false){
    const ov = document.getElementById('mcmTutOverlay');
    if (ov) ov.classList.add('hidden');

    if (tutOpenedFromGame){
        if (isPaused) window.togglePause();
    } else {
        // User closed the initial instructions -> proceed to real game!
        localStorage.setItem(TUTORIAL_KEY, '1');
        if (trialScreen) trialScreen.style.display = 'none';
        if (gameScreen) gameScreen.style.display = 'flex';
        initGame();
    }
};

/* ═══════════════════════════════════════════════════════
   TRIAL GAME`;

    content = content.replace(oldCloseTutMcm, newCloseTutMcm);
    fs.writeFileSync(file, content, 'utf8');
    console.log('Math Card Match: updated successfully');
})();
