const fs = require('fs');

let content = fs.readFileSync('resources/views/games/rocket_math.blade.php', 'utf8');

// 1. Add Flashcard Deck CSS
const flashcardCss = `
/* ============================================================
   ROCKET MATH FLASH CARD SYSTEM — ALWAYS ON TOP, ZERO SCROLLBARS
   ============================================================ */
#rmTutOverlay.fc-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(2, 6, 20, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 999999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    animation: rmFadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden !important;
}
#rmTutOverlay.fc-overlay.show {
    display: flex !important;
}

.rm-fc-deck {
    position: relative;
    width: 100%;
    max-width: 530px;
    margin: auto;
}

.rm-fc-deck-stack-1 {
    position: absolute;
    inset: 10px 18px -10px;
    background: rgba(56, 189, 248, 0.18);
    border: 1.5px solid rgba(100, 181, 246, 0.25);
    border-radius: 26px;
    transform: rotate(-2.8deg);
    z-index: 1;
    pointer-events: none;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
}
.rm-fc-deck-stack-2 {
    position: absolute;
    inset: 5px 8px -5px;
    background: rgba(15, 25, 55, 0.9);
    border: 1.5px solid rgba(100, 181, 246, 0.35);
    border-radius: 26px;
    transform: rotate(2deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
}

.rm-fc-card {
    position: relative;
    z-index: 3;
    background: linear-gradient(165deg, rgba(16, 28, 60, 0.98) 0%, rgba(8, 14, 32, 0.99) 100%);
    border: 2px solid rgba(100, 181, 246, 0.55);
    border-radius: 26px;
    padding: 22px 26px 20px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 40px rgba(56, 189, 248, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    text-align: center;
}
.rm-fc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #38bdf8 0%, #818cf8 35%, #4caf50 70%, #f59e0b 100%);
}

.rm-fc-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.rm-fc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(100, 181, 246, 0.18);
    border: 1px solid rgba(100, 181, 246, 0.45);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 800;
    color: #64b5f6;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}
.rm-fc-quick-action-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: rgba(200, 230, 255, 0.9);
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
.rm-fc-quick-action-btn:hover {
    background: rgba(100, 181, 246, 0.35);
    border-color: rgba(100, 181, 246, 0.6);
    color: #fff;
}
.rm-fc-quick-action-btn.is-close {
    width: 32px;
    height: 32px;
    padding: 0;
    justify-content: center;
    font-size: 1.2rem;
    border-radius: 10px;
}
.rm-fc-quick-action-btn.is-close:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

.rm-fc-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 245px;
    animation: rmFcSlideIn 0.25s cubic-bezier(.34,1.56,.64,1);
}
.rm-fc-slide.hidden {
    display: none !important;
}
@keyframes rmFcSlideIn {
    from { opacity: 0; transform: scale(0.96) translateY(6px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.rm-fc-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.42rem !important;
    font-weight: 900 !important;
    margin: 0 0 6px !important;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    color: #64b5f6;
}
.rm-fc-desc {
    font-size: 0.88rem;
    color: rgba(220, 235, 255, 0.85);
    line-height: 1.4;
    margin: 0 auto 12px;
    max-width: 440px;
}

.rm-fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(100, 181, 246, 0.25);
    border-radius: 16px;
    padding: 12px 14px;
    margin-bottom: 12px;
    width: 100%;
}
.rm-fc-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    width: 100%;
}
.rm-fc-item {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 12px;
    padding: 10px 10px;
    text-align: center;
}
.rm-fc-item-ico { font-size: 1.5rem; margin-bottom: 2px; }
.rm-fc-item-lbl { font-size: 0.72rem; font-weight: 800; color: #64b5f6; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
.rm-fc-item-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 900; color: #fff; }
.rm-fc-item-sub { font-size: 0.72rem; color: rgba(200, 225, 255, 0.75); margin-top: 3px; line-height: 1.3; }

.rm-fc-tip {
    background: rgba(100, 181, 246, 0.12);
    border: 1px solid rgba(100, 181, 246, 0.35);
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.78rem;
    color: #e0f2fe;
    width: 100%;
    text-align: center;
    line-height: 1.35;
}
.rm-fc-tip b { color: #64b5f6; }

.rm-fc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    margin-top: 6px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.rm-fc-nav-btn {
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
.rm-fc-nav-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.14);
    transform: translateY(-1px);
}
.rm-fc-nav-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.rm-fc-nav-btn.primary {
    background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
    border: none;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.45);
}
.rm-fc-nav-btn.primary:hover {
    box-shadow: 0 6px 20px rgba(2, 132, 199, 0.65);
    transform: translateY(-2px);
}

.rm-fc-dots {
    display: flex;
    align-items: center;
    gap: 6px;
}
.rm-fc-dot {
    width: 8px;
    height: 8px;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.22);
    border: none;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(.34,1.56,.64,1);
    padding: 0;
}
.rm-fc-dot.active {
    width: 22px;
    background: #38bdf8;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.7);
}
`;

// Insert CSS
content = content.replace('.rm-tut-box{', flashcardCss + '\n        .rm-tut-box{');

// 2. Add Tutorial button to header HUD
content = content.replace(
    `<div class="eduplay-game-nav ms-1">\n                            <button type="button" class="eduplay-nav-btn eduplay-btn-pause"`,
    `<div class="eduplay-game-nav ms-1">
                            <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play" onclick="rmOpenTut(true)">
                                <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
                            </button>
                            <button type="button" class="eduplay-nav-btn eduplay-btn-pause"`
);

// 3. Replace #rmTutOverlay HTML with Flash Card Deck HTML
const flashcardHtml = `
    <!-- FLASH CARD INSTRUCTIONS & TUTORIAL DECK -->
    <div id="rmTutOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="Rocket Math Instructions">
        <div class="rm-fc-deck">
            <!-- Physical Stack Layers -->
            <div class="rm-fc-deck-stack-1"></div>
            <div class="rm-fc-deck-stack-2"></div>

            <!-- Active Front Flash Card -->
            <div class="rm-fc-card">
                <div class="rm-fc-top">
                    <span class="rm-fc-badge" id="rmFcBadge">🃏 HOW TO PLAY • CARD 1 OF 4</span>
                    <button class="rm-fc-quick-action-btn" id="rmFcQuickActionBtn" type="button" onclick="rmCloseTut(true)" title="Skip to Play">
                        <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
                    </button>
                </div>

                <!-- CARD 1: Read Mission Briefing -->
                <div id="rmFcSlide1" class="rm-fc-slide">
                    <h3 class="rm-fc-title"><span>📖</span> Read Mission Briefing</h3>
                    <p class="rm-fc-desc">Each mission features a story problem at the top. Calculate the math answer to find your rocket's target fuel!</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">📜</div>
                            <div class="rm-fc-item-lbl">Story Problem</div>
                            <div class="rm-fc-item-sub">"5L for takeoff + 3L for landing. Total fuel?"</div>
                        </div>
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">🎯</div>
                            <div class="rm-fc-item-lbl">Target Fuel</div>
                            <div class="rm-fc-item-math" style="color:#38bdf8;margin-top:6px;">8.0 Liters</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Concept:</b> Solve the word problem to know the exact fuel target!
                    </div>
                </div>

                <!-- CARD 2: Load Fuel Canisters -->
                <div id="rmFcSlide2" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#34d399;"><span>⛽</span> Load Fuel Canisters</h3>
                    <p class="rm-fc-desc">Click fuel canisters on the right to attach them to the rocket. Click again to remove them!</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">⛽</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Canister 3.0L</div>
                            <div class="rm-fc-item-sub">Attached ✓ (Glows green)</div>
                        </div>
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">⛽</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Canister 5.0L</div>
                            <div class="rm-fc-item-sub">Attached ✓ (3.0 + 5.0 = 8.0L)</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Loading Strategy:</b> Combine canisters to equal the target fuel amount!
                    </div>
                </div>

                <!-- CARD 3: Watch the Fuel Gauge -->
                <div id="rmFcSlide3" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#fbbf24;"><span>⚡</span> Hit Exact Fuel Level</h3>
                    <p class="rm-fc-desc">The fuel gauge on the left must be filled to <b>exactly</b> the target amount:</p>
                    <div class="rm-fc-box" style="display:flex;justify-content:space-around;text-align:center;">
                        <div>
                            <div style="font-size:1.4rem;">🔵</div>
                            <div style="font-size:0.75rem;font-weight:700;color:#64b5f6;">Too Low</div>
                            <div style="font-size:0.68rem;color:rgba(200,225,255,0.6);">Needs more</div>
                        </div>
                        <div>
                            <div style="font-size:1.4rem;">🚀</div>
                            <div style="font-size:0.75rem;font-weight:800;color:#34d399;">Exact Match!</div>
                            <div style="font-size:0.68rem;color:#a7f3d0;">Auto Blastoff!</div>
                        </div>
                        <div>
                            <div style="font-size:1.4rem;">🔴</div>
                            <div style="font-size:0.75rem;font-weight:700;color:#f87171;">Overloaded</div>
                            <div style="font-size:0.68rem;color:rgba(200,225,255,0.6);">Detach a can</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Careful:</b> Don't overload! When fuel hits the exact number, launch begins!
                    </div>
                </div>

                <!-- CARD 4: Session Timer & Goals -->
                <div id="rmFcSlide4" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#f59e0b;"><span>⏱️</span> 5-Min Timer &amp; Goals</h3>
                    <p class="rm-fc-desc">Launch as many rockets as you can in 5 minutes! Launch <b>8–10 rockets</b> to pass the level.</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <button type="button" class="rm-fc-item" onclick="rmStartPracticeFromTut()" style="cursor:pointer;background:rgba(56,189,248,0.14);border-color:rgba(56,189,248,0.4);color:#fff;">
                            <div class="rm-fc-item-ico">🎮</div>
                            <div class="rm-fc-item-lbl" style="color:#38bdf8;">Practice Round</div>
                            <div class="rm-fc-item-sub">Test 5.0L target first</div>
                        </button>
                        <button type="button" class="rm-fc-item" onclick="rmCloseTut(true)" style="cursor:pointer;background:rgba(16,185,129,0.16);border-color:rgba(52,211,153,0.4);color:#fff;">
                            <div class="rm-fc-item-ico">🚀</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Real Mission</div>
                            <div class="rm-fc-item-sub">Start 5-min session</div>
                        </button>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Pro Tip:</b> Spend coins for hints, or click "New Round" if you want a fresh mission!
                    </div>
                </div>

                <!-- Flashcard Footer Navigation -->
                <div class="rm-fc-footer">
                    <button class="rm-fc-nav-btn" id="rmFcPrevBtn" type="button" onclick="rmStepFlashCard(-1)">
                        <i class="bi bi-arrow-left"></i> Prev
                    </button>

                    <div class="rm-fc-dots" id="rmFcDots" role="tablist" aria-label="Flash Cards">
                        <button class="rm-fc-dot active" type="button" onclick="rmGoFlashCard(1)" title="Card 1"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(2)" title="Card 2"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(3)" title="Card 3"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(4)" title="Card 4"></button>
                    </div>

                    <button class="rm-fc-nav-btn primary" id="rmFcNextBtn" type="button" onclick="rmStepFlashCard(1)">
                        <span>Next</span> <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
`;

// Replace old tutorial overlay HTML
const oldRmTutStart = content.indexOf('<!-- TUTORIAL OVERLAY (full step-by-step');
const oldRmTutEnd = content.indexOf('<!-- RESULTS MODAL -->');

if (oldRmTutStart !== -1 && oldRmTutEnd !== -1) {
    content = content.substring(0, oldRmTutStart) + flashcardHtml.trim() + '\n\n    ' + content.substring(oldRmTutEnd);
}

// 4. Update JS Controller
const newRmJs = `
        // ===== FLASH CARD INSTRUCTIONS & TUTORIAL CONTROLLER =====
        let rmTutOpenedFromGame = false;
        let rmCurrentFcCard = 1;
        const RM_TOTAL_FC_CARDS = 4;

        function rmOpenTut(fromGame = false) {
            rmTutOpenedFromGame = fromGame;
            const ov = document.getElementById('rmTutOverlay');
            const topActionBtn = document.getElementById('rmFcQuickActionBtn');

            if (fromGame) {
                if (GameState.gameStarted && !GameState.isPaused && !GameState.sessionEnded) {
                    togglePause();
                }
                if (topActionBtn) {
                    topActionBtn.className = 'rm-fc-quick-action-btn is-close';
                    topActionBtn.innerHTML = '&times;';
                    topActionBtn.title = 'Close Tutorial';
                    topActionBtn.onclick = () => rmCloseTut(false);
                }
            } else {
                if (topActionBtn) {
                    topActionBtn.className = 'rm-fc-quick-action-btn';
                    topActionBtn.innerHTML = '<span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>';
                    topActionBtn.title = 'Skip Instructions';
                    topActionBtn.onclick = () => rmCloseTut(true);
                }
            }

            if (ov) ov.classList.add('show');
            rmGoFlashCard(1);
        }

        function rmGoFlashCard(cardNum) {
            if (cardNum < 1) cardNum = 1;
            if (cardNum > RM_TOTAL_FC_CARDS) cardNum = RM_TOTAL_FC_CARDS;
            rmCurrentFcCard = cardNum;

            for (let i = 1; i <= RM_TOTAL_FC_CARDS; i++) {
                const slide = document.getElementById('rmFcSlide' + i);
                if (slide) slide.classList.toggle('hidden', i !== rmCurrentFcCard);
            }

            const dots = document.querySelectorAll('#rmFcDots .rm-fc-dot');
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === rmCurrentFcCard - 1);
            });

            const badge = document.getElementById('rmFcBadge');
            if (badge) {
                const tags = ['MISSION BRIEFING', 'CANISTERS', 'FUEL GAUGE', 'TIMER & GOALS'];
                const prefix = rmTutOpenedFromGame ? 'TUTORIAL' : 'HOW TO PLAY';
                badge.textContent = '🃏 ' + prefix + ' • CARD ' + rmCurrentFcCard + ' OF ' + RM_TOTAL_FC_CARDS + ' • ' + tags[rmCurrentFcCard - 1];
            }

            const prevBtn = document.getElementById('rmFcPrevBtn');
            if (prevBtn) {
                prevBtn.disabled = (rmCurrentFcCard === 1);
            }

            const nextBtn = document.getElementById('rmFcNextBtn');
            if (nextBtn) {
                if (rmCurrentFcCard === RM_TOTAL_FC_CARDS) {
                    if (rmTutOpenedFromGame) {
                        nextBtn.innerHTML = '<span>▶ Return to Game</span>';
                    } else {
                        nextBtn.innerHTML = '<span>🚀 Start Mission!</span>';
                    }
                } else {
                    nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
                }
            }
        }

        function rmStepFlashCard(delta) {
            if (rmCurrentFcCard === RM_TOTAL_FC_CARDS && delta > 0) {
                rmCloseTut(true);
                return;
            }
            rmGoFlashCard(rmCurrentFcCard + delta);
        }

        function rmStartPracticeFromTut() {
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.classList.remove('show');
            rmShowTrialOverlay();
        }

        function rmCloseTut(andStartGame = false) {
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.classList.remove('show');

            if (rmTutOpenedFromGame) {
                if (GameState.isPaused) togglePause();
            } else {
                if (andStartGame) {
                    rmStartRealGame();
                }
            }
        }
`;

// Replace old tutorial JS
const oldJsStart = content.indexOf('// ===== TUTORIAL SYSTEM =====');
const oldJsEnd = content.indexOf('function rmShowTrialOverlay()');

if (oldJsStart !== -1 && oldJsEnd !== -1) {
    content = content.substring(0, oldJsStart) + newRmJs.trim() + '\n\n        ' + content.substring(oldJsEnd);
}

// In init(), always open instructions on page load
content = content.replace(
    `if (!localStorage.getItem(RM_TUTORIAL_KEY)) {\n                // Show tutorial for first-time players\n                document.getElementById('rmTutOverlay').classList.add('show');\n            } else {\n                // Auto-start the game for returning players\n                GameState.gameStarted  = true;\n                GameState.isPaused     = false;\n                GameState.sessionEnded = false;\n                GameState.score        = 0;\n                GameState.sessionTimer = SESSION_SECS;\n                document.getElementById('scoreDisplay').textContent = '0';\n                const pb = document.getElementById('pauseBtn');\n                if (pb) pb.style.display = 'inline-flex';\n                startSessionTimer();\n                resetRound();\n            }`,
    `rmOpenTut(false);`
);

fs.writeFileSync('resources/views/games/rocket_math.blade.php', content, 'utf8');
console.log('Successfully updated rocket_math.blade.php with Flash Card Deck Instructions!');
