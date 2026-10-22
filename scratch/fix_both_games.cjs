const fs = require('fs');

// ==========================================
// 1. FIX MATH CARD MATCH
// ==========================================
(function fixMathCardMatch() {
    const file = 'resources/views/games/math_card_match.blade.php';
    let content = fs.readFileSync(file, 'utf8');

    // Remove any previous window.togglePause block around line 1350
    // so we can place a clean hoisted function right before openTut
    content = content.replace(/\/\* ═+\s+PAUSE CONTROLLER[\s\S]*?window\.togglePause\s*=\s*function\(\)\{[\s\S]*?\};\s*/g, '');

    // Now insert function togglePause and window.togglePause right before FLASHCARD DECK CONTROLLER
    const targetAnchor = '/* ═══════════════════════════════════════════════════════\r\n   FLASHCARD DECK CONTROLLER';
    const targetAnchorLF = '/* ═══════════════════════════════════════════════════════\n   FLASHCARD DECK CONTROLLER';

    const pauseControllerCode = 
`/* ═══════════════════════════════════════════════════════
   PAUSE CONTROLLER
═══════════════════════════════════════════════════════ */
function togglePause(){
    if(!gameActive)return;
    isPaused = !isPaused;
    const overlay = document.getElementById('pauseOverlay');
    const btn = document.getElementById('pauseBtn');
    if(isPaused){
        clearInterval(timerInt);
        lockBoard = true;
        if(overlay) overlay.classList.add('active');
        if(btn){
            btn.classList.add('paused');
            btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
        }
    } else {
        lockBoard = false;
        if(overlay) overlay.classList.remove('active');
        if(btn){
            btn.classList.remove('paused');
            btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
        }
        clearInterval(timerInt);
        timerInt = setInterval(()=>{
            seconds++;
            timeValEl.textContent = fmtTime(seconds);
            if(seconds<=90)       timeValEl.style.color = '#34d399';
            else if(seconds<=150) timeValEl.style.color = '#fbbf24';
            else                  timeValEl.style.color = '#f87171';
            const hint = document.getElementById('starGoalHint');
            if(hint){
                if(seconds<=90)       hint.textContent = '⭐⭐⭐ <1:30';
                else if(seconds<=150) hint.textContent = '⭐⭐ <2:30';
                else                  hint.textContent = '⭐ 3:30+';
            }
        },1000);
    }
}
window.togglePause = togglePause;

`;

    if (content.includes('FLASHCARD DECK CONTROLLER')) {
        if (content.includes(targetAnchor)) {
            content = content.replace(targetAnchor, pauseControllerCode + targetAnchor);
        } else if (content.includes(targetAnchorLF)) {
            content = content.replace(targetAnchorLF, pauseControllerCode + targetAnchorLF);
        }
        console.log('Math Card Match: togglePause placed before openTut');
    }

    fs.writeFileSync(file, content, 'utf8');
    console.log('Math Card Match: updated successfully');
})();

// ==========================================
// 2. FIX ROCKET MATH
// ==========================================
(function fixRocketMath() {
    const file = 'resources/views/games/rocket_math.blade.php';
    let content = fs.readFileSync(file, 'utf8');

    // Remove any recursive wrappers
    content = content.replace(/window\.rmOpenTut\s*=\s*function\(fromGame\s*=\s*false\)\s*\{\s*return\s*rmOpenTut\(fromGame\);\s*\};\s*/g, '');
    content = content.replace(/window\.rmCloseTut\s*=\s*function\(andStart\s*=\s*false\)\s*\{\s*return\s*rmCloseTut\(andStart\);\s*\};\s*/g, '');
    content = content.replace(/window\.rmGoFlashCard\s*=\s*function\(c\)\s*\{\s*return\s*rmGoFlashCard\(c\);\s*\};\s*/g, '');
    content = content.replace(/window\.rmStepFlashCard\s*=\s*function\(d\)\s*\{\s*return\s*rmStepFlashCard\(d\);\s*\};\s*/g, '');
    content = content.replace(/window\.rmStartPracticeFromTut\s*=\s*function\(\)\s*\{\s*return\s*rmStartPracticeFromTut\(\);\s*\};\s*/g, '');

    // Ensure RM_TUTORIAL_KEY is declared once at top of <script>
    content = content.replace(/const RM_TUTORIAL_KEY = 'rm_tutorial_done';\s*/g, '');
    
    // Add const RM_TUTORIAL_KEY right at start of script
    const scriptStartTag = '<script>';
    content = content.replace(scriptStartTag, scriptStartTag + '\r\n        const RM_TUTORIAL_KEY = \'rm_tutorial_done\';');

    // Expose window functions safely at the end of rmCloseTut
    const targetEndRmCloseTut = 'function rmCloseTut(andStartGame = false) {';
    if (!content.includes('window.rmOpenTut = rmOpenTut;')) {
        const exposeBlock = 
`window.rmOpenTut = rmOpenTut;
        window.rmCloseTut = rmCloseTut;
        window.rmGoFlashCard = rmGoFlashCard;
        window.rmStepFlashCard = rmStepFlashCard;
        window.rmStartPracticeFromTut = rmStartPracticeFromTut;
        `;
        // insert before function rmOpenTut
        content = content.replace('function rmOpenTut(fromGame = false) {', exposeBlock + 'function rmOpenTut(fromGame = false) {');
        console.log('Rocket Math: exposed window functions non-recursively');
    }

    fs.writeFileSync(file, content, 'utf8');
    console.log('Rocket Math: updated successfully');
})();
