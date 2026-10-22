const fs = require('fs');

let content = fs.readFileSync('resources/views/games/factor_pond.blade.php', 'utf8');

// 1. New CSS for Flashcard Deck & Simplified Instructions
const newCss = `
/* ============================================================
   OVERLAY SYSTEM — ALWAYS ON TOP, ZERO SCROLLBARS
   ============================================================ */
.overlay, .fc-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(2, 14, 20, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 999999 !important; /* ALWAYS ON TOP OF EVERYTHING */
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    animation: fadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden !important; /* ZERO OUTER SCROLLBAR */
}
.overlay.hidden, .fc-overlay.hidden {
    display: none !important;
}

/* ============================================================
   SIMPLIFIED INSTRUCTION MODAL CARD
   ============================================================ */
.tq-instr-card {
    max-width: 740px !important;
    width: 95% !important;
    margin: auto !important;
    background: linear-gradient(165deg, rgba(8, 38, 46, 0.98) 0%, rgba(4, 20, 26, 0.99) 100%) !important;
    border: 1.5px solid rgba(94, 234, 212, 0.45) !important;
    border-radius: 26px !important;
    padding: 24px 30px 20px !important;
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.9), 0 0 50px rgba(20, 184, 166, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
    position: relative !important;
    overflow: hidden !important; /* ZERO INNER SCROLLBAR */
    text-align: center !important;
}
.tq-instr-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #5eead4 0%, #22d3ee 35%, #fbbf24 70%, #f43f5e 100%);
}

.tq-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(20, 184, 166, 0.18);
    border: 1px solid rgba(94, 234, 212, 0.4);
    border-radius: 99px;
    padding: 4px 14px;
    font-size: 0.74rem;
    font-weight: 800;
    color: #5eead4;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.tq-instr-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.95rem !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #ffffff 0%, #5eead4 50%, #fbbf24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 6px 0 3px !important;
    letter-spacing: -0.5px;
}
.tq-instr-sub {
    color: rgba(204, 251, 241, 0.85);
    font-size: 0.92rem;
    line-height: 1.4;
    margin: 0 auto 16px;
    max-width: 580px;
}

/* 3 Simple Instruction Steps */
.tq-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 14px;
    text-align: left;
}
.tq-feat-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 16px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.tq-feat-head {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tq-feat-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.tq-feat-card.blue  .tq-feat-icon { background: rgba(34, 211, 238, 0.16); border: 1px solid rgba(34, 211, 238, 0.35); }
.tq-feat-card.gold  .tq-feat-icon { background: rgba(251, 191, 36, 0.16); border: 1px solid rgba(251, 191, 36, 0.35); }
.tq-feat-card.green .tq-feat-icon { background: rgba(52, 211, 153, 0.16); border: 1px solid rgba(52, 211, 153, 0.35); }

.tq-feat-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.92rem;
    font-weight: 800;
    color: #ffffff;
}
.tq-feat-desc {
    font-size: 0.8rem;
    color: rgba(204, 251, 241, 0.8);
    line-height: 1.35;
    margin: 0;
}
.tq-feat-desc b { color: #fff; font-weight: 700; }

/* 2 Quick Reference Concepts */
.tq-concepts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 16px;
    text-align: left;
}
.tq-concept-card {
    background: rgba(8, 48, 56, 0.65);
    border: 1px solid rgba(94, 234, 212, 0.24);
    border-radius: 14px;
    padding: 10px 14px;
}
.tq-c-tag {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #5eead4;
    margin-bottom: 2px;
}
.tq-c-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.9rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 2px;
}
.tq-c-desc {
    font-size: 0.78rem;
    color: rgba(204, 251, 241, 0.8);
    line-height: 1.35;
}
.tq-c-desc b { color: #5eead4; }

/* Instruction Footer */
.tq-instr-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    flex-wrap: wrap;
}
.tq-stats-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}
.tq-stat-pill {
    background: rgba(255, 255, 255, 0.045);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 99px;
    padding: 5px 12px;
    font-size: 0.78rem;
    font-weight: 700;
    color: rgba(204, 251, 241, 0.88);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.tq-stat-pill b { color: #5eead4; }

.tq-actions-row {
    display: flex;
    gap: 8px;
    align-items: center;
    flex: 1;
    justify-content: flex-end;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 800;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(.34,1.56,.64,1);
    text-decoration: none;
}
.btn.go {
    background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
    color: #fff;
    box-shadow: 0 5px 18px rgba(13, 148, 136, 0.45);
}
.btn.go:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(13, 148, 136, 0.65);
}
.btn.ghost {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.btn.ghost:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    transform: translateY(-1px);
}

/* ============================================================
   FLASH CARD TUTORIAL DECK (Not in One Container — True Card Deck)
   ============================================================ */
.fc-deck {
    position: relative;
    width: 100%;
    max-width: 520px;
    margin: auto;
}

/* Background Deck Layers — Physical Stack Illusion */
.fc-deck-stack-1 {
    position: absolute;
    inset: 10px 18px -10px;
    background: rgba(13, 148, 136, 0.22);
    border: 1.5px solid rgba(94, 234, 212, 0.22);
    border-radius: 26px;
    transform: rotate(-2.8deg);
    z-index: 1;
    pointer-events: none;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
}
.fc-deck-stack-2 {
    position: absolute;
    inset: 5px 8px -5px;
    background: rgba(5, 34, 42, 0.88);
    border: 1.5px solid rgba(94, 234, 212, 0.35);
    border-radius: 26px;
    transform: rotate(2deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
}

/* The Active Front Flash Card */
.fc-card {
    position: relative;
    z-index: 3;
    background: linear-gradient(165deg, rgba(8, 38, 46, 0.98) 0%, rgba(4, 20, 26, 0.99) 100%);
    border: 2px solid rgba(94, 234, 212, 0.5);
    border-radius: 26px;
    padding: 22px 26px 20px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 40px rgba(20, 184, 166, 0.22), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    text-align: center;
}
.fc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #5eead4 0%, #22d3ee 35%, #fbbf24 70%, #f43f5e 100%);
}

/* Card Header */
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
    background: rgba(20, 184, 166, 0.18);
    border: 1px solid rgba(94, 234, 212, 0.4);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 0.72rem;
    font-weight: 800;
    color: #5eead4;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}
.fc-close {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.14);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(204, 251, 241, 0.75);
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.fc-close:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

/* Flashcard Slides */
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
}
.fc-desc {
    font-size: 0.88rem;
    color: rgba(204, 251, 241, 0.85);
    line-height: 1.4;
    margin: 0 auto 12px;
    max-width: 440px;
}

/* Flashcard Visual Containers */
.fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(94, 234, 212, 0.2);
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
.fc-item-lbl { font-size: 0.72rem; font-weight: 800; color: #5eead4; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
.fc-item-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 900; color: #fff; }
.fc-item-sub { font-size: 0.72rem; color: rgba(204, 251, 241, 0.75); margin-top: 3px; line-height: 1.3; }
.fc-item-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; margin-top: 4px; }
.fc-item-badge.good { background: rgba(52, 211, 153, 0.2); color: #34d399; }
.fc-item-badge.bad { background: rgba(248, 113, 113, 0.2); color: #f87171; }

.fc-tip {
    background: rgba(20, 184, 166, 0.12);
    border: 1px solid rgba(94, 234, 212, 0.3);
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.78rem;
    color: #ccfbf1;
    width: 100%;
    text-align: center;
    line-height: 1.35;
}
.fc-tip b { color: #5eead4; }

/* Flashcard Footer Navigation */
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
    background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
    border: none;
    box-shadow: 0 4px 14px rgba(13, 148, 136, 0.45);
}
.fc-nav-btn.primary:hover {
    box-shadow: 0 6px 20px rgba(13, 148, 136, 0.65);
    transform: translateY(-2px);
}

/* Dot Indicators */
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
    background: #5eead4;
    box-shadow: 0 0 10px rgba(94, 234, 212, 0.7);
}

/* Demo Fish in Flash Cards */
.demo-strip{display:flex;gap:12px;justify-content:center;margin:6px 0 10px;}
.demo-fish{width:62px;height:62px;border-radius:50%;background:rgba(20,184,166,.15);border:2px solid rgba(94,234,212,.4);display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:900;color:#67e8f9;flex-direction:column;gap:2px;}
.demo-fish.selected{background:rgba(251,191,36,.22);border-color:rgba(251,191,36,.8);color:#fbbf24;box-shadow:0 0 14px rgba(251,191,36,.45);}
.demo-fish-lbl{font-size:10px;font-weight:800;color:rgba(255,255,255,.55);text-align:center;margin-top:3px;}
.demo-fish-wrap{display:flex;flex-direction:column;align-items:center;}
`;

// Replace CSS chunk
const cssStartIndex = content.indexOf('/* ============================================================\n   OVERLAY SYSTEM — ALWAYS ON TOP, ZERO SCROLLBARS');
const cssEndIndex = content.indexOf('/* ============================================================\n   GAME SCREEN & HUD (Upper-Right Button Group, No Restart)');

if (cssStartIndex === -1 || cssEndIndex === -1) {
    console.error('Could not find CSS markers:', { cssStartIndex, cssEndIndex });
    process.exit(1);
}

content = content.substring(0, cssStartIndex) + newCss + '\n' + content.substring(cssEndIndex);

// 2. New HTML for #overlay and #tutorialOverlay
const newHtml = `
  <!-- ============================================================
       INSTRUCTION MODAL (Simple, Clear & Easy to Understand)
       ============================================================ -->
  <div id="overlay" class="overlay" role="dialog" aria-modal="true" aria-label="Factor Pond Instructions">
    <div class="card tq-instr-card">
      <div style="margin-bottom:2px;">
        <span class="tq-badge"><i class="bi bi-water"></i> Grade {{ auth()->user()->grade_level ?? 4 }} • Factor Pond</span>
      </div>
      <h2 class="tq-instr-title">Factor Pond</h2>
      <p class="tq-instr-sub">
        Catch all the right fish matching your mission and fill your net!
      </p>

      <!-- 3 Simple Steps -->
      <div class="tq-features-grid">
        <div class="tq-feat-card blue">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🎯</div>
            <div class="tq-feat-title">1. Read Mission</div>
          </div>
          <p class="tq-feat-desc">
            Check the top banner: are you hunting <b>Factors</b> or <b>Multiples</b>?
          </p>
        </div>

        <div class="tq-feat-card gold">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🐟</div>
            <div class="tq-feat-title">2. Tap Matching Fish</div>
          </div>
          <p class="tq-feat-desc">
            Click fish to pick them — matching fish glow <b style="color:#fbbf24;">gold ✨</b>. Click again to unpick.
          </p>
        </div>

        <div class="tq-feat-card green">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🪝</div>
            <div class="tq-feat-title">3. Cast Net!</div>
          </div>
          <p class="tq-feat-desc">
            Hit <b>🪝 Cast Net!</b> when all correct fish are glowing. Make <b>10 catches</b> to level up!
          </p>
        </div>
      </div>

      <!-- Quick Concept Guide -->
      <div class="tq-concepts-grid">
        <div class="tq-concept-card">
          <div class="tq-c-tag">🔢 WHAT IS A FACTOR?</div>
          <div class="tq-c-title">Divides Evenly (0 Remainder)</div>
          <div class="tq-c-desc">Factors of 12: <b>1, 2, 3, 4, 6, 12</b> (e.g. 12 ÷ 4 = 3 ✅)</div>
        </div>
        <div class="tq-concept-card">
          <div class="tq-c-tag">✖️ WHAT IS A MULTIPLE?</div>
          <div class="tq-c-title">Times Table (Skip Counting)</div>
          <div class="tq-c-desc">Multiples of 4: <b>4, 8, 12, 16, 20...</b> (e.g. 4 × 3 = 12)</div>
        </div>
      </div>

      <!-- Bottom Action Bar -->
      <div class="tq-instr-footer">
        <div class="tq-stats-pills">
          <div class="tq-stat-pill">❤️ <b>5</b> Lives</div>
          <div class="tq-stat-pill">🎯 <b>10</b> to Win</div>
          <div class="tq-stat-pill">🌊 Level <b><span id="instrLevel">{{ $progress->current_level ?? 1 }}</span></b></div>
        </div>

        <div class="tq-actions-row">
          <button id="viewTutBtn" class="btn ghost" type="button" onclick="openTut(false)" style="min-height:44px;font-size:0.9rem;padding:0 18px;">
            <i class="bi bi-stack"></i> 🃏 Flash Cards Tutorial
          </button>
          <button id="startBtn" class="btn go" type="button" style="min-height:44px;font-size:0.98rem;padding:0 26px;">
            🎣 Start Fishing!
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================================
       FLASH CARD TUTORIAL DECK (Not in One Container — True Deck UI)
       ============================================================ -->
  <div id="tutorialOverlay" class="fc-overlay hidden" role="dialog" aria-modal="true" aria-label="Factor Pond Flash Cards Tutorial">
    <div class="fc-deck">
      <!-- Physical Stack Layers -->
      <div class="fc-deck-stack-1"></div>
      <div class="fc-deck-stack-2"></div>

      <!-- Active Front Flash Card -->
      <div class="fc-card">
        <div class="fc-top">
          <span class="fc-badge" id="fcBadge">🃏 CARD 1 OF 5 • THE MISSION</span>
          <button class="fc-close" type="button" onclick="closeTut()" title="Close Flash Cards">&times;</button>
        </div>

        <!-- CARD 1: Check Your Mission -->
        <div id="fcSlide1" class="fc-slide">
          <h3 class="fc-title" style="color:#5eead4;"><span>🎯</span> Look Before You Fish!</h3>
          <p class="fc-desc">Read the top mission banner before clicking any fish. You will hunt for either <b>Factors</b> or <b>Multiples</b>:</p>
          <div class="fc-row-2 fc-box">
            <div class="fc-item">
              <div class="fc-item-ico">🔢</div>
              <div class="fc-item-lbl">Factors Mission</div>
              <div class="fc-item-math">Target: 16</div>
              <div class="fc-item-badge good">Catch: 1, 2, 4, 8, 16</div>
            </div>
            <div class="fc-item">
              <div class="fc-item-ico">✖️</div>
              <div class="fc-item-lbl">Multiples Mission</div>
              <div class="fc-item-math">Target: 5</div>
              <div class="fc-item-badge good">Catch: 5, 10, 15, 20...</div>
            </div>
          </div>
          <div class="fc-tip">
            💡 <b>Rule of Thumb:</b> Always glance at the top banner first so you know what fish to catch!
          </div>
        </div>

        <!-- CARD 2: What is a Factor? -->
        <div id="fcSlide2" class="fc-slide hidden">
          <h3 class="fc-title" style="color:#22d3ee;"><span>🔢</span> What is a Factor?</h3>
          <p class="fc-desc">A factor divides the target number with <b>0 remainder</b>. If there is a remainder, it is NOT a factor!</p>
          <div class="fc-row-2 fc-box">
            <div class="fc-item">
              <div class="fc-item-ico">✅</div>
              <div class="fc-item-lbl">Target: 12</div>
              <div class="fc-item-math">12 ÷ 4 = 3</div>
              <div class="fc-item-badge good">0 Remainder ➔ Factor!</div>
            </div>
            <div class="fc-item">
              <div class="fc-item-ico">❌</div>
              <div class="fc-item-lbl">Target: 12</div>
              <div class="fc-item-math">12 ÷ 5 = 2 R 2</div>
              <div class="fc-item-badge bad">Remainder 2 ➔ Not Factor</div>
            </div>
          </div>
          <div class="fc-tip">
            💡 <b>Golden Rule:</b> Factors are <b>always equal to or smaller</b> than the target number.
          </div>
        </div>

        <!-- CARD 3: What is a Multiple? -->
        <div id="fcSlide3" class="fc-slide hidden">
          <h3 class="fc-title" style="color:#fbbf24;"><span>✖️</span> What is a Multiple?</h3>
          <p class="fc-desc">Multiples come from your times table or skip-counting! Multiply the target by 1, 2, 3, 4, 5 and so on.</p>
          <div class="fc-box" style="text-align:center;border-color:rgba(251,191,36,0.35);">
            <div style="font-size:0.75rem;font-weight:800;color:#fbbf24;letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">Multiples of 6 (Skip Counting)</div>
            <div style="font-family:'Space Grotesk',sans-serif;font-size:1.3rem;font-weight:900;color:#fff;letter-spacing:1px;margin-bottom:6px;">6, 12, 18, 24, 30, 36...</div>
            <div style="font-size:0.78rem;color:rgba(254,240,138,0.85);font-weight:700;">6×1=6 &bull; 6×2=12 &bull; 6×3=18 &bull; 6×4=24</div>
          </div>
          <div class="fc-tip">
            💡 <b>Golden Rule:</b> Multiples are <b>always equal to or bigger</b> than the target number.
          </div>
        </div>

        <!-- CARD 4: How to Catch Fish -->
        <div id="fcSlide4" class="fc-slide hidden">
          <h3 class="fc-title" style="color:#34d399;"><span>🐟</span> Tap Fish &amp; Cast Net</h3>
          <p class="fc-desc">Click fish to select them. Selected fish glow with a bright <b style="color:#fbbf24;">golden ring ✨</b>:</p>
          <div class="demo-strip" style="margin:4px 0 10px;">
            <div class="demo-fish-wrap"><div class="demo-fish selected">2</div><div class="demo-fish-lbl">✅ Picked</div></div>
            <div class="demo-fish-wrap"><div class="demo-fish">5</div><div class="demo-fish-lbl">Not picked</div></div>
            <div class="demo-fish-wrap"><div class="demo-fish selected">4</div><div class="demo-fish-lbl">✅ Picked</div></div>
          </div>
          <div class="fc-tip">
            💡 <b>Need to unpick?</b> Click any picked fish again. When ready, click <b>🪝 Cast Net!</b>
          </div>
        </div>

        <!-- CARD 5: 5 Lives & How to Win -->
        <div id="fcSlide5" class="fc-slide hidden">
          <h3 class="fc-title" style="color:#f43f5e;"><span>❤️</span> 5 Lives &amp; 10 to Win</h3>
          <p class="fc-desc">Keep your hearts safe and fill your net to clear the level!</p>
          <div class="fc-row-2 fc-box">
            <div class="fc-item">
              <div class="fc-item-ico">💔</div>
              <div class="fc-item-lbl">Lose 1 Life</div>
              <div class="fc-item-sub">Picking wrong fish or leaving a matching fish behind.</div>
            </div>
            <div class="fc-item">
              <div class="fc-item-ico">🏆</div>
              <div class="fc-item-lbl">Win Level</div>
              <div class="fc-item-sub">Make <b>10 perfect net casts</b> to unlock the next level!</div>
            </div>
          </div>
          <div class="fc-tip">
            💡 <b>Shortcuts:</b> Press <b>P</b> to pause anytime. Press <b>Space</b> to Cast Net!
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
            <button class="fc-dot" type="button" onclick="goFlashCard(5)" title="Card 5"></button>
          </div>

          <button class="fc-nav-btn primary" id="fcNextBtn" type="button" onclick="stepFlashCard(1)">
            <span>Next</span> <i class="bi bi-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
`;

// Replace HTML chunk
const htmlStartIndex = content.indexOf('<!-- ============================================================\n       INSTRUCTION MODAL (Simple, Clear & Easy to Understand)');
const htmlEndIndex = content.indexOf('<!-- WRONG ANSWER MODAL -->');

if (htmlStartIndex === -1 || htmlEndIndex === -1) {
    console.error('Could not find HTML markers:', { htmlStartIndex, htmlEndIndex });
    process.exit(1);
}

content = content.substring(0, htmlStartIndex) + newHtml.trim() + '\n</div>\n\n' + content.substring(htmlEndIndex);

// 3. New JavaScript Controller for Flashcards
const newJs = `
/* ============================================================
   FLASHCARD DECK TUTORIAL CONTROLLER (Step-by-Step, Always on top)
   ============================================================ */
let tutOpenedFromGame = false;
let currentFcCard = 1;
const TOTAL_FC_CARDS = 5;

window.openTut = function(fromGame = false){
    tutOpenedFromGame = fromGame;
    if (fromGame){
        isPaused = true; // Pause fish movement
        const pauseOv = document.getElementById('pauseOverlay');
        if (pauseOv) pauseOv.classList.remove('active'); // Hide pause card so it never conflicts
    }
    overlay.classList.add('hidden');
    tutorialOverlay.classList.remove('hidden');
    goFlashCard(1);
};

window.goFlashCard = function(cardNum){
    if (cardNum < 1) cardNum = 1;
    if (cardNum > TOTAL_FC_CARDS) cardNum = TOTAL_FC_CARDS;
    currentFcCard = cardNum;

    // Show active slide, hide others
    for (let i = 1; i <= TOTAL_FC_CARDS; i++){
        const slide = document.getElementById('fcSlide' + i);
        if (slide) slide.classList.toggle('hidden', i !== currentFcCard);
    }

    // Update dots
    const dots = document.querySelectorAll('#fcDots .fc-dot');
    dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentFcCard - 1);
    });

    // Update Badge
    const badge = document.getElementById('fcBadge');
    if (badge){
        const tags = ['THE MISSION', 'FACTORS', 'MULTIPLES', 'HOW TO PLAY', 'LIVES & WIN'];
        badge.textContent = '🃏 CARD ' + currentFcCard + ' OF ' + TOTAL_FC_CARDS + ' • ' + tags[currentFcCard - 1];
    }

    // Update Prev button
    const prevBtn = document.getElementById('fcPrevBtn');
    if (prevBtn){
        prevBtn.disabled = (currentFcCard === 1);
    }

    // Update Next button
    const nextBtn = document.getElementById('fcNextBtn');
    if (nextBtn){
        if (currentFcCard === TOTAL_FC_CARDS){
            if (tutOpenedFromGame){
                nextBtn.innerHTML = '<span>▶ Return to Game</span>';
            } else {
                nextBtn.innerHTML = '<span>🚀 Start Fishing!</span>';
            }
        } else {
            nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
        }
    }
};

window.stepFlashCard = function(delta){
    if (currentFcCard === TOTAL_FC_CARDS && delta > 0){
        // Finished last card!
        closeTut(!tutOpenedFromGame);
        return;
    }
    goFlashCard(currentFcCard + delta);
};

window.closeTut = function(andStartGame = false){
    tutorialOverlay.classList.add('hidden');
    if (tutOpenedFromGame){
        isPaused = false; // Resume game
        const pauseBtn = document.getElementById('pauseBtn');
        if (pauseBtn){
            pauseBtn.classList.remove('paused');
            pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
        }
    } else {
        if (andStartGame){
            overlay.classList.add('hidden');
            gameScreen.style.display = 'flex';
            refreshBounds();
            startGame();
        } else {
            overlay.classList.remove('hidden');
        }
    }
};
`;

// Replace JS chunk
const jsStartIndex = content.indexOf('/* ============================================================\n   TUTORIAL CONTROLLER (Step-by-Step, Always on top, In-front of Game)');
const jsEndIndex = content.indexOf('(function init(){');

if (jsStartIndex === -1 || jsEndIndex === -1) {
    console.error('Could not find JS markers:', { jsStartIndex, jsEndIndex });
    process.exit(1);
}

content = content.substring(0, jsStartIndex) + newJs.trim() + '\n\n' + content.substring(jsEndIndex);

fs.writeFileSync('resources/views/games/factor_pond.blade.php', content, 'utf8');
console.log('Successfully updated resources/views/games/factor_pond.blade.php with Flash Card UI and Simplified Instructions!');
