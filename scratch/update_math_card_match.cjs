const fs = require('fs');

let content = fs.readFileSync('resources/views/games/math_card_match.blade.php', 'utf8');

// 1. Add Flashcard Deck CSS
const flashcardCss = `
/* ============================================================
   FLASH CARD SYSTEM — ALWAYS ON TOP, ZERO SCROLLBARS
   ============================================================ */
.fc-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(4, 2, 12, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 999999 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    animation: fadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden !important;
}
.fc-overlay.hidden {
    display: none !important;
}

.fc-deck {
    position: relative;
    width: 100%;
    max-width: 530px;
    margin: auto;
}

.fc-deck-stack-1 {
    position: absolute;
    inset: 10px 18px -10px;
    background: rgba(124, 58, 237, 0.22);
    border: 1.5px solid rgba(167, 139, 250, 0.25);
    border-radius: 26px;
    transform: rotate(-2.8deg);
    z-index: 1;
    pointer-events: none;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
}
.fc-deck-stack-2 {
    position: absolute;
    inset: 5px 8px -5px;
    background: rgba(15, 6, 40, 0.88);
    border: 1.5px solid rgba(167, 139, 250, 0.35);
    border-radius: 26px;
    transform: rotate(2deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
}

.fc-card {
    position: relative;
    z-index: 3;
    background: linear-gradient(165deg, rgba(20, 8, 50, 0.98) 0%, rgba(8, 4, 24, 0.99) 100%);
    border: 2px solid rgba(167, 139, 250, 0.55);
    border-radius: 26px;
    padding: 22px 26px 20px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 40px rgba(124, 58, 237, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    text-align: center;
}
.fc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #c4b5fd 0%, #818cf8 35%, #38bdf8 70%, #ec4899 100%);
}

.fc-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.fc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(124, 58, 237, 0.22);
    border: 1px solid rgba(167, 139, 250, 0.45);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 800;
    color: #c4b5fd;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}
.fc-quick-action-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: rgba(220, 210, 255, 0.9);
    border-radius: 99px;
    padding: 4px 12px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.74rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
}
.fc-quick-action-btn:hover {
    background: rgba(124, 58, 237, 0.35);
    border-color: rgba(167, 139, 250, 0.6);
    color: #fff;
}
.fc-quick-action-btn.is-close {
    width: 32px;
    height: 32px;
    padding: 0;
    justify-content: center;
    font-size: 1.2rem;
    border-radius: 10px;
}
.fc-quick-action-btn.is-close:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

.fc-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 245px;
    animation: fcSlideIn 0.25s cubic-bezier(.34,1.56,.64,1);
}
.fc-slide.hidden {
    display: none !important;
}
@keyframes fcSlideIn {
    from { opacity: 0; transform: scale(0.96) translateY(6px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.fc-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.42rem !important;
    font-weight: 900 !important;
    margin: 0 0 6px !important;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    color: #c4b5fd;
}
.fc-desc {
    font-size: 0.88rem;
    color: rgba(220, 210, 255, 0.85);
    line-height: 1.4;
    margin: 0 auto 12px;
    max-width: 440px;
}

.fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(167, 139, 250, 0.25);
    border-radius: 16px;
    padding: 12px 14px;
    margin-bottom: 12px;
    width: 100%;
}
.fc-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    width: 100%;
}
.fc-item {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 12px;
    padding: 10px 10px;
    text-align: center;
}
.fc-item-ico { font-size: 1.5rem; margin-bottom: 2px; }
.fc-item-lbl { font-size: 0.72rem; font-weight: 800; color: #a78bfa; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
.fc-item-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 900; color: #fff; }
.fc-item-sub { font-size: 0.72rem; color: rgba(220, 210, 255, 0.75); margin-top: 3px; line-height: 1.3; }

.fc-tip {
    background: rgba(124, 58, 237, 0.15);
    border: 1px solid rgba(167, 139, 250, 0.35);
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.78rem;
    color: #e9d5ff;
    width: 100%;
    text-align: center;
    line-height: 1.35;
}
.fc-tip b { color: #c4b5fd; }

.fc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    margin-top: 6px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.fc-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: #fff;
    padding: 8px 16px;
    border-radius: 11px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.84rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
}
.fc-nav-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.14);
    transform: translateY(-1px);
}
.fc-nav-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.fc-nav-btn.primary {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
    border: none;
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.45);
}
.fc-nav-btn.primary:hover {
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.65);
    transform: translateY(-2px);
}

.fc-dots {
    display: flex;
    align-items: center;
    gap: 6px;
}
.fc-dot {
    width: 8px;
    height: 8px;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.22);
    border: none;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(.34,1.56,.64,1);
    padding: 0;
}
.fc-dot.active {
    width: 22px;
    background: #a78bfa;
    box-shadow: 0 0 10px rgba(167, 139, 250, 0.7);
}
`;

// Insert flashcard CSS before #trialScreen CSS
content = content.replace('/* ─────────────────────────────────────────────\n   TUTORIAL SCREEN', flashcardCss + '\n/* ─────────────────────────────────────────────\n   TUTORIAL SCREEN');

// 2. Replace #tutorialScreen and #instrScreen with the new Flash Card Deck
const flashcardHtml = `
    <!-- ══════════════════════════════════════════
         FLASH CARD INSTRUCTIONS & TUTORIAL DECK
    ══════════════════════════════════════════ -->
    <div id="mcmTutOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="Math Card Match Instructions">
        <div class="fc-deck">
            <!-- Physical Stack Layers -->
            <div class="fc-deck-stack-1"></div>
            <div class="fc-deck-stack-2"></div>

            <!-- Active Front Flash Card -->
            <div class="fc-card">
                <div class="fc-top">
                    <span class="fc-badge" id="fcBadge">🃏 HOW TO PLAY • CARD 1 OF 4</span>
                    <button class="fc-quick-action-btn" id="fcQuickActionBtn" type="button" onclick="closeTut(true)" title="Skip to Play">
                        <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
                    </button>
                </div>

                <!-- CARD 1: Word Problem & Answer Pairs -->
                <div id="fcSlide1" class="fc-slide">
                    <h3 class="fc-title"><span>🃏</span> Match Problems to Answers</h3>
                    <p class="fc-desc">Hidden on the board are <b>6 Word Problem cards (📖)</b> and <b>6 Answer cards (💡)</b>. Find all matching pairs!</p>
                    <div class="fc-row-2 fc-box">
                        <div class="fc-item">
                            <div class="fc-item-ico">📖</div>
                            <div class="fc-item-lbl">Word Problem</div>
                            <div class="fc-item-sub">"Maya had 5 space rocks and found 3 more."</div>
                        </div>
                        <div class="fc-item">
                            <div class="fc-item-ico">💡</div>
                            <div class="fc-item-lbl">Matching Answer</div>
                            <div class="fc-item-math" style="color:#2dd4bf;margin-top:6px;">8 Space Rocks</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Concept:</b> One card states the story problem, and the other shows the answer!
                    </div>
                </div>

                <!-- CARD 2: How to Flip & Match -->
                <div id="fcSlide2" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#38bdf8;"><span>👆</span> Flip Two Cards at a Time</h3>
                    <p class="fc-desc">Click any card to flip it over, then click a second card to look for its match!</p>
                    <div class="fc-row-2 fc-box">
                        <div class="fc-item">
                            <div class="fc-item-ico">✅</div>
                            <div class="fc-item-lbl" style="color:#34d399;">Cards Match!</div>
                            <div class="fc-item-sub">They stay revealed, glow green, and you score <b>+10 points</b>!</div>
                        </div>
                        <div class="fc-item">
                            <div class="fc-item-ico">❌</div>
                            <div class="fc-item-lbl" style="color:#f87171;">No Match</div>
                            <div class="fc-item-sub">Both cards flip back. <b>Remember</b> where they are!</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Memory Strategy:</b> Memorize the locations of cards you've already seen!
                    </div>
                </div>

                <!-- CARD 3: Speed & Few Tries = 3 Stars! -->
                <div id="fcSlide3" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#fbbf24;"><span>⭐</span> Speed &amp; Fewer Tries = ⭐⭐⭐</h3>
                    <p class="fc-desc">Complete the board quickly with as few tries as possible to earn top honors:</p>
                    <div class="fc-box" style="text-align:center;border-color:rgba(251,191,36,0.35);">
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.15rem;font-weight:900;color:#fbbf24;margin-bottom:4px;">Star Goals</div>
                        <div style="display:flex;justify-content:space-around;font-size:0.8rem;font-weight:700;">
                            <div>⭐⭐⭐ &lt; 1:30</div>
                            <div>⭐⭐ &lt; 2:30</div>
                            <div>⭐ 3:30+</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Need a breather?</b> Click <b>Pause</b> or press <b>P</b> anytime!
                    </div>
                </div>

                <!-- CARD 4: Clear Board & Practice Option -->
                <div id="fcSlide4" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#ec4899;"><span>🚀</span> Ready to Match!</h3>
                    <p class="fc-desc">Match all <b>6 pairs</b> to clear the level and advance! You can do a quick practice round or jump into the real game:</p>
                    <div class="fc-row-2 fc-box">
                        <button type="button" class="fc-item" onclick="startPracticeFromTut()" style="cursor:pointer;background:rgba(34,211,238,0.12);border-color:rgba(34,211,238,0.35);color:#fff;">
                            <div class="fc-item-ico">🎮</div>
                            <div class="fc-item-lbl" style="color:#38bdf8;">Practice Round</div>
                            <div class="fc-item-sub">Try 2 pairs first</div>
                        </button>
                        <button type="button" class="fc-item" onclick="closeTut(true)" style="cursor:pointer;background:rgba(124,58,237,0.18);border-color:rgba(167,139,250,0.45);color:#fff;">
                            <div class="fc-item-ico">🏆</div>
                            <div class="fc-item-lbl" style="color:#c4b5fd;">Real Game</div>
                            <div class="fc-item-sub">Start 6 pairs now</div>
                        </button>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Goal:</b> Find all 6 matches before time runs out!
                    </div>
                </div>

                <!-- Flashcard Footer Navigation -->
                <div class="fc-footer">
                    <button class="fc-nav-btn" id="fcPrevBtn" type="button" onclick="stepFlashCard(-1)">
                        <i class="bi bi-arrow-left"></i> Prev
                    </button>

                    <div class="fc-dots" id="fcDots" role="tablist" aria-label="Flash Cards">
                        <button class="fc-dot active" type="button" onclick="goFlashCard(1)" title="Card 1"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(2)" title="Card 2"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(3)" title="Card 3"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(4)" title="Card 4"></button>
                    </div>

                    <button class="fc-nav-btn primary" id="fcNextBtn" type="button" onclick="stepFlashCard(1)">
                        <span>Next</span> <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
`;

// Replace old #tutorialScreen and #instrScreen
const oldTutStart = content.indexOf('<div id="tutorialScreen">');
const oldInstrEnd = content.indexOf('<!-- ══════════════════════════════════════════\n         GAME SCREEN');

if (oldTutStart !== -1 && oldInstrEnd !== -1) {
    // Keep #trialScreen between them if needed or replace both tutorialScreen and instrScreen
    // Let's replace tutorialScreen, keep trialScreen, remove instrScreen
    const trialStart = content.indexOf('<!-- ══════════════════════════════════════════\n         TRIAL SCREEN');
    const trialEnd = content.indexOf('<!-- ══════════════════════════════════════════\n         INSTRUCTION SCREEN');
    const gameScreenStart = content.indexOf('<!-- ══════════════════════════════════════════\n         GAME SCREEN');

    const trialHtml = content.substring(trialStart, trialEnd);
    content = content.substring(0, oldTutStart) + flashcardHtml.trim() + '\n\n' + trialHtml + '\n' + content.substring(gameScreenStart);
}

// 3. Add Tutorial button to the HUD
content = content.replace(
    `<div class="eduplay-game-nav">\n                    <button type="button" class="eduplay-nav-btn eduplay-btn-pause"`,
    `<div class="eduplay-game-nav">
                    <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play" onclick="openTut(true)">
                        <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
                    </button>
                    <button type="button" class="eduplay-nav-btn eduplay-btn-pause"`
);

// 4. Update JS Controller
const newJs = `
/* ═══════════════════════════════════════════════════════
   FLASHCARD DECK CONTROLLER (Instructions & In-game Tutorial)
═══════════════════════════════════════════════════════ */
let tutOpenedFromGame = false;
let currentFcCard = 1;
const TOTAL_FC_CARDS = 4;

window.openTut = function(fromGame = false){
    tutOpenedFromGame = fromGame;
    const ov = document.getElementById('mcmTutOverlay');
    const topActionBtn = document.getElementById('fcQuickActionBtn');

    if (fromGame){
        if (gameActive && !isPaused) window.togglePause();
        if (topActionBtn){
            topActionBtn.className = 'fc-quick-action-btn is-close';
            topActionBtn.innerHTML = '&times;';
            topActionBtn.title = 'Close Tutorial';
            topActionBtn.onclick = () => closeTut(false);
        }
    } else {
        if (topActionBtn){
            topActionBtn.className = 'fc-quick-action-btn';
            topActionBtn.innerHTML = '<span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>';
            topActionBtn.title = 'Skip Instructions';
            topActionBtn.onclick = () => closeTut(true);
        }
    }

    if (ov) ov.classList.remove('hidden');
    goFlashCard(1);
};

window.goFlashCard = function(cardNum){
    if (cardNum < 1) cardNum = 1;
    if (cardNum > TOTAL_FC_CARDS) cardNum = TOTAL_FC_CARDS;
    currentFcCard = cardNum;

    for (let i = 1; i <= TOTAL_FC_CARDS; i++){
        const slide = document.getElementById('fcSlide' + i);
        if (slide) slide.classList.toggle('hidden', i !== currentFcCard);
    }

    const dots = document.querySelectorAll('#fcDots .fc-dot');
    dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentFcCard - 1);
    });

    const badge = document.getElementById('fcBadge');
    if (badge){
        const tags = ['THE BOARD', 'HOW TO MATCH', 'STAR GOALS', 'READY TO PLAY'];
        const prefix = tutOpenedFromGame ? 'TUTORIAL' : 'HOW TO PLAY';
        badge.textContent = '🃏 ' + prefix + ' • CARD ' + currentFcCard + ' OF ' + TOTAL_FC_CARDS + ' • ' + tags[currentFcCard - 1];
    }

    const prevBtn = document.getElementById('fcPrevBtn');
    if (prevBtn){
        prevBtn.disabled = (currentFcCard === 1);
    }

    const nextBtn = document.getElementById('fcNextBtn');
    if (nextBtn){
        if (currentFcCard === TOTAL_FC_CARDS){
            if (tutOpenedFromGame){
                nextBtn.innerHTML = '<span>▶ Return to Game</span>';
            } else {
                nextBtn.innerHTML = '<span>🚀 Start Matching!</span>';
            }
        } else {
            nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
        }
    }
};

window.stepFlashCard = function(delta){
    if (currentFcCard === TOTAL_FC_CARDS && delta > 0){
        closeTut(true);
        return;
    }
    goFlashCard(currentFcCard + delta);
};

window.startPracticeFromTut = function(){
    const ov = document.getElementById('mcmTutOverlay');
    if (ov) ov.classList.add('hidden');
    showTrialScreen();
};

window.closeTut = function(andStartGame = false){
    const ov = document.getElementById('mcmTutOverlay');
    if (ov) ov.classList.add('hidden');

    if (tutOpenedFromGame){
        if (isPaused) window.togglePause();
    } else {
        if (andStartGame){
            localStorage.setItem(TUTORIAL_KEY, '1');
            trialScreen.style.display = 'none';
            gameScreen.style.display = 'flex';
            initGame();
        }
    }
};
`;

// Replace old goToStep, tutNav, skipTutorial with new controller
const jsOldStart = content.indexOf('/* ═══════════════════════════════════════════════════════\n   TUTORIAL STATE');
const jsOldEnd = content.indexOf('/* ═══════════════════════════════════════════════════════\n   TRIAL GAME');

if (jsOldStart !== -1 && jsOldEnd !== -1) {
    content = content.substring(0, jsOldStart) + newJs.trim() + '\n\n' + content.substring(jsOldEnd);
}

// Update entry point init()
content = content.replace(
    `(function init(){\n    if(!localStorage.getItem(TUTORIAL_KEY)){\n        // First time — show tutorial\n        tutorialScreen.style.display='';\n        instrScreen.style.display='none';\n    }else{\n        // Returning player — show instruction screen\n        tutorialScreen.style.display='none';\n        instrScreen.style.display='';\n    }\n})();`,
    `(function init(){\n    openTut(false);\n})();`
);

fs.writeFileSync('resources/views/games/math_card_match.blade.php', content, 'utf8');
console.log('Successfully updated math_card_match.blade.php with Flash Card Deck Instructions!');
