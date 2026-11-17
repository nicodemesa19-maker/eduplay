/**
 * EduPlay Game Feedback System
 * Provides encouraging feedback notifications for correct/incorrect answers
 */

// Pool of encouraging messages for incorrect answers
const ENCOURAGING_MESSAGES = [
    "Not quite, let's try again!",
    "That's not the answer this time. Give it another go!",
    "Almost! Take another look.",
    "Hmm, not this one. Try a different approach.",
    "Good try! Let's check it together.",
    "Not yet. You're still learning this!",
    "Mistakes help your brain grow. Try again!",
    "You're getting closer. Want a hint?",
    "That didn't work. What else could you try?",
    "Great effort! Let's learn from this one.",
    "Oops! Let's give it another shot.",
    "Nice try! The answer is hiding somewhere else.",
    "Close! One more try?",
    "That answer took a detour. Let's get back on track!"
];

/**
 * Get a random encouraging message
 */
function getEncouragingMessage() {
    return ENCOURAGING_MESSAGES[Math.floor(Math.random() * ENCOURAGING_MESSAGES.length)];
}

/**
 * Play correct answer sound effect
 */
let correctSoundAudio = null;
function playCorrectSound() {
    try {
        // Create audio element if it doesn't exist
        if (!correctSoundAudio) {
            correctSoundAudio = new Audio('/sounds/correct-answer.mp3');
            correctSoundAudio.volume = 0.5; // Set volume to 50%
        }
        
        // Reset and play
        correctSoundAudio.currentTime = 0;
        correctSoundAudio.play().catch(err => {
            console.log('Could not play correct answer sound:', err);
        });
    } catch (err) {
        console.log('Error initializing correct answer sound:', err);
    }
}

/**
 * Play wrong answer sound effect
 */
let wrongSoundAudio = null;
function playWrongSound() {
    try {
        // Create audio element if it doesn't exist
        if (!wrongSoundAudio) {
            wrongSoundAudio = new Audio('/sounds/wrong-answer.mp3');
            wrongSoundAudio.volume = 0.5; // Set volume to 50%
        }
        
        // Reset and play
        wrongSoundAudio.currentTime = 0;
        wrongSoundAudio.play().catch(err => {
            console.log('Could not play wrong answer sound:', err);
        });
    } catch (err) {
        console.log('Error initializing wrong answer sound:', err);
    }
}

/**
 * Create enhanced confetti effect for correct answers
 */
function createConfetti() {
    const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#6c5ce7', '#a29bfe', '#fd79a8', '#fdcb6e', 
                    '#ff9ff3', '#54a0ff', '#48dbfb', '#1dd1a1', '#feca57', '#ff6348', '#ee5a6f', '#c44569'];
    const shapes = ['square', 'circle', 'triangle', 'star', 'ribbon'];
    const confettiCount = window.innerWidth < 768 ? 60 : 120; // Doubled the count
    
    // Create confetti bursts from multiple points
    const burstPoints = [
        { x: 25, y: -10 },
        { x: 50, y: -10 },
        { x: 75, y: -10 }
    ];
    
    burstPoints.forEach((point, burstIndex) => {
        const particlesPerBurst = Math.floor(confettiCount / burstPoints.length);
        
        for (let i = 0; i < particlesPerBurst; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                const shape = shapes[Math.floor(Math.random() * shapes.length)];
                confetti.className = `game-confetti game-confetti-${shape}`;
                
                // Randomize starting position around burst point
                const spreadX = (Math.random() - 0.5) * 20;
                confetti.style.left = `calc(${point.x}vw + ${spreadX}vw)`;
                
                // Random delay for staggered effect
                const delay = Math.random() * 0.3;
                confetti.style.animationDelay = `${delay}s`;
                
                // Vibrant colors with occasional sparkle
                const color = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.backgroundColor = color;
                confetti.style.boxShadow = `0 0 10px ${color}`;
                
                // Varied sizes for depth
                const size = Math.random() * 15 + 8;
                confetti.style.width = `${size}px`;
                confetti.style.height = `${size}px`;
                
                // Random rotation
                confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                
                // Add different animation durations for variety
                const duration = 2.5 + Math.random() * 1.5;
                confetti.style.animationDuration = `${duration}s`;
                
                // Random horizontal drift
                const drift = (Math.random() - 0.5) * 100;
                confetti.style.setProperty('--drift-x', `${drift}px`);
                
                document.body.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, duration * 1000);
            }, i * 8 + burstIndex * 150); // Faster spawn rate
        }
    });
    
    // Add some emoji confetti for extra celebration
    const emojis = ['⭐', '✨', '🎉', '🎊', '💫', '🌟', '⚡'];
    for (let i = 0; i < 15; i++) {
        setTimeout(() => {
            const emoji = document.createElement('div');
            emoji.className = 'game-confetti game-confetti-emoji';
            emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            emoji.style.left = Math.random() * 100 + 'vw';
            emoji.style.animationDelay = Math.random() * 0.2 + 's';
            emoji.style.fontSize = (Math.random() * 10 + 20) + 'px';
            
            const duration = 2 + Math.random() * 1;
            emoji.style.animationDuration = `${duration}s`;
            
            document.body.appendChild(emoji);
            
            setTimeout(() => {
                emoji.remove();
            }, duration * 1000);
        }, i * 50);
    }
}

/**
 * Show feedback notification
 * @param {boolean} isCorrect - Whether the answer was correct
 * @param {string} correctAnswer - The correct answer (always shown)
 * @param {number} duration - Deprecated, kept for compatibility (now ignored, only Continue button dismisses)
 * @param {function} onContinue - Callback function when Continue button is clicked
 * @param {string} explanation - Optional step-by-step solution/explanation
 * @param {string} customIcon - Optional custom icon (emoji or HTML) for the feedback header
 */
function showGameFeedback(isCorrect, correctAnswer = null, duration = 0, onContinue = null, explanation = null, customIcon = null) {
    // Remove any existing feedback
    const existing = document.getElementById('game-feedback-notification');
    if (existing) {
        existing.remove();
    }

    // Play sound effect
    if (isCorrect) {
        playCorrectSound();
    } else {
        playWrongSound();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'game-feedback-notification';
    notification.className = 'game-feedback-notification';
    
    // Determine icon to use
    const icon = customIcon || (isCorrect ? '🚀' : '💭');
    
    if (isCorrect) {
        notification.classList.add('feedback-correct');
        
        // Always show correct answer, even for correct responses
        let answerDisplay = '';
        if (correctAnswer) {
            answerDisplay = `<div class="feedback-answer feedback-answer-correct">Correct answer: <strong>${correctAnswer}</strong></div>`;
        }
        
        // Show explanation if provided
        let explanationHTML = '';
        if (explanation) {
            explanationHTML = `<div class="feedback-explanation">${explanation}</div>`;
        }
        
        notification.innerHTML = `
            <div class="feedback-header">
                <div class="feedback-icon">${icon}</div>
                <div class="feedback-content">
                    <div class="feedback-title">You got it right!</div>
                    <div class="feedback-subtitle">Amazing work!</div>
                </div>
            </div>
            <div class="feedback-body">
                ${answerDisplay}
                ${explanationHTML}
                <button class="feedback-continue-btn" id="feedbackContinueBtn">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        `;
        
        // Trigger confetti for correct answers
        createConfetti();
    } else {
        const encouragingMsg = getEncouragingMessage();
        notification.classList.add('feedback-incorrect');
        
        // Always show the correct answer for learning
        let correctAnswerHTML = '';
        if (correctAnswer) {
            correctAnswerHTML = `<div class="feedback-answer">The correct answer is: <strong>${correctAnswer}</strong></div>`;
        }
        
        // Show step-by-step solution if provided
        let explanationHTML = '';
        if (explanation) {
            explanationHTML = `
                <div class="feedback-explanation">
                    <div class="explanation-header">
                        <i class="bi bi-lightbulb-fill"></i> How to solve it:
                    </div>
                    <div class="explanation-content">${explanation}</div>
                </div>
            `;
        }
        
        notification.innerHTML = `
            <div class="feedback-header">
                <div class="feedback-icon">${icon}</div>
                <div class="feedback-content">
                    <div class="feedback-title">${encouragingMsg}</div>
                    <div class="feedback-subtitle">Let's learn from this!</div>
                </div>
            </div>
            <div class="feedback-body">
                ${correctAnswerHTML}
                ${explanationHTML}
                <button class="feedback-continue-btn" id="feedbackContinueBtn">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        `;
    }

    // Add to page - append to fullscreen element if in fullscreen, otherwise body
    const fullscreenElement = document.fullscreenElement || document.webkitFullscreenElement || 
                             document.mozFullScreenElement || document.msFullscreenElement;
    const targetElement = fullscreenElement || document.body;
    targetElement.appendChild(notification);

    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Handle continue button click (ONLY way to dismiss)
    const continueBtn = document.getElementById('feedbackContinueBtn');
    if (continueBtn) {
        continueBtn.addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
            
            if (onContinue && typeof onContinue === 'function') {
                onContinue();
            }
        });
    }

    // NO auto-dismiss - feedback stays until Continue is clicked
    // Duration parameter is now ignored for user control
}

/**
 * Inject CSS styles if not already present
 */
function injectFeedbackStyles() {
    if (document.getElementById('game-feedback-styles')) {
        return; // Already injected
    }

    const style = document.createElement('style');
    style.id = 'game-feedback-styles';
    style.textContent = `
        .game-feedback-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.8);
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.97) 0%, rgba(30, 41, 59, 0.99) 50%, rgba(15, 23, 42, 0.97) 100%);
            border: 1.5px solid rgba(56, 189, 248, 0.4);
            border-radius: 24px;
            padding: 0;
            box-shadow: 
                0 25px 70px rgba(0, 0, 0, 0.9),
                0 0 60px rgba(56, 189, 248, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            z-index: 999999;
            min-width: 440px;
            max-width: 90vw;
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            overflow: hidden;
        }

        .game-feedback-notification.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
            pointer-events: all;
        }
        
        /* Ensure visibility in fullscreen mode */
        :fullscreen .game-feedback-notification,
        :-webkit-full-screen .game-feedback-notification,
        :-moz-full-screen .game-feedback-notification,
        :-ms-fullscreen .game-feedback-notification {
            position: fixed !important;
            z-index: 2147483647 !important;
        }

        .game-feedback-notification::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #38bdf8, #818cf8, #c084fc, #38bdf8);
            background-size: 200% 100%;
            animation: feedbackGlowBar 4s linear infinite;
        }

        @keyframes feedbackGlowBar {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .game-feedback-notification.feedback-correct::before {
            background: linear-gradient(90deg, #10b981, #34d399, #6ee7b7, #10b981);
            background-size: 200% 100%;
        }

        .game-feedback-notification.feedback-incorrect::before {
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #fde68a, #f59e0b);
            background-size: 200% 100%;
        }

        .feedback-header {
            padding: 36px 40px 24px 40px;
            text-align: center;
        }

        .feedback-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            line-height: 1;
            filter: drop-shadow(0 6px 15px rgba(0, 0, 0, 0.6));
            animation: feedbackIconFloat 3s ease-in-out infinite;
        }

        .feedback-correct .feedback-icon {
            background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, rgba(52, 211, 153, 0.15) 60%, transparent 100%);
            border: 2px solid rgba(16, 185, 129, 0.6);
            box-shadow: 
                0 0 30px rgba(16, 185, 129, 0.4), 
                inset 0 0 20px rgba(16, 185, 129, 0.2);
        }

        .feedback-incorrect .feedback-icon {
            background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, rgba(251, 191, 36, 0.15) 60%, transparent 100%);
            border: 2px solid rgba(245, 158, 11, 0.6);
            box-shadow: 
                0 0 30px rgba(245, 158, 11, 0.4), 
                inset 0 0 20px rgba(245, 158, 11, 0.2);
        }

        @keyframes feedbackIconFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-8px) scale(1.05); }
        }

        .feedback-content {
            text-align: center;
        }

        .feedback-title {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            font-size: 1.85rem;
            font-weight: 900;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .feedback-correct .feedback-title {
            background: linear-gradient(135deg, #10b981 0%, #34d399 50%, #d1fae5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 20px rgba(16, 185, 129, 0.3));
        }

        .feedback-incorrect .feedback-title {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #fef3c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.3));
        }

        .feedback-subtitle {
            font-size: 1.05rem;
            color: rgba(226, 232, 240, 0.8);
            font-weight: 500;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .feedback-body {
            padding: 0 40px 36px 40px;
        }

        .feedback-answer {
            margin-bottom: 18px;
            padding: 16px 20px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 14px;
            font-size: 1.02rem;
            color: rgba(226, 232, 240, 0.95) !important;
            line-height: 1.6;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.4);
            text-align: center;
        }
        
        .feedback-answer * {
            color: rgba(226, 232, 240, 0.95) !important;
        }

        .feedback-answer strong {
            color: #38bdf8 !important;
            font-weight: 800;
            font-size: 1.08em;
        }

        .feedback-correct .feedback-answer {
            border-color: rgba(16, 185, 129, 0.35);
            background: rgba(16, 185, 129, 0.06);
        }

        .feedback-correct .feedback-answer strong {
            color: #34d399 !important;
        }

        .feedback-incorrect .feedback-answer {
            border-color: rgba(245, 158, 11, 0.35);
        }

        .feedback-incorrect .feedback-answer strong {
            color: #fbbf24 !important;
        }

        /* Correct answer shown on correct responses */
        .feedback-answer-correct {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .feedback-continue-btn {
            width: 100%;
            background: linear-gradient(135deg, #38bdf8 0%, #4f46e5 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 16px 32px;
            font-size: 1.15rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.25s ease;
            box-shadow: 0 8px 25px rgba(56, 189, 248, 0.4);
            letter-spacing: 0.3px;
            text-transform: none;
        }

        .feedback-continue-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(56, 189, 248, 0.55);
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
        }

        .feedback-continue-btn:active {
            transform: translateY(-1px);
        }

        .feedback-continue-btn i {
            font-size: 1.3rem;
        }

        /* Explanation/Solution section */
        .feedback-explanation {
            margin-bottom: 18px;
            padding: 16px 20px;
            background: rgba(139, 92, 246, 0.08);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 14px;
            font-size: 0.98rem;
            line-height: 1.7;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.25);
            text-align: left;
            color: rgba(226, 232, 240, 0.95) !important;
        }
        
        .feedback-explanation * {
            color: rgba(226, 232, 240, 0.95) !important;
        }

        .explanation-header {
            color: #c4b5fd !important;
            font-weight: 800;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
        }

        .explanation-header i {
            font-size: 1.3rem;
            color: #fbbf24 !important;
        }

        .explanation-content {
            color: rgba(226, 232, 240, 0.95) !important;
            padding-left: 0;
        }
        
        .explanation-content div {
            color: rgba(226, 232, 240, 0.95) !important;
            margin-bottom: 8px;
        }

        .explanation-content ol, .explanation-content ul {
            margin: 10px 0 0 0;
            padding-left: 28px;
            color: rgba(226, 232, 240, 0.95) !important;
        }

        .explanation-content li {
            margin-bottom: 6px;
            color: rgba(226, 232, 240, 0.95) !important;
        }

        .explanation-content strong, .explanation-content b {
            color: #fde68a !important;
            font-weight: 800;
        }

        .explanation-content code {
            background: rgba(0, 0, 0, 0.5);
            padding: 3px 9px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            color: #34d399 !important;
            font-size: 0.92em;
            border: 1px solid rgba(52, 211, 153, 0.25);
        }
        
        .explanation-content br {
            line-height: 1.8;
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            .game-feedback-notification {
                min-width: 320px;
                max-width: 95vw;
            }

            .feedback-header {
                padding: 28px 24px 20px 24px;
                gap: 16px;
            }

            .feedback-body {
                padding: 0 24px 28px 24px;
            }

            .feedback-icon {
                width: 64px;
                height: 64px;
                font-size: 2.5rem;
            }

            .feedback-title {
                font-size: 1.35rem;
            }

            .feedback-subtitle {
                font-size: 0.9rem;
            }

            .feedback-answer {
                font-size: 0.9rem;
                padding: 12px 14px;
            }

            .feedback-continue-btn {
                font-size: 1rem;
                padding: 12px 20px;
            }

            .explanation-content {
                font-size: 0.9rem;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            .game-feedback-notification,
            .feedback-icon {
                animation: none !important;
                transition: opacity 0.2s ease !important;
            }
            .game-confetti {
                display: none !important;
            }
        }

        /* Confetti animations */
        .game-confetti {
            position: fixed;
            top: -10px;
            z-index: 9999;
            pointer-events: none;
            animation: confettiFall 3s ease-out forwards;
            will-change: transform, opacity;
        }
        
        /* Different confetti shapes */
        .game-confetti-square {
            border-radius: 2px;
        }
        
        .game-confetti-circle {
            border-radius: 50%;
        }
        
        .game-confetti-triangle {
            width: 0 !important;
            height: 0 !important;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 14px solid;
            border-bottom-color: inherit;
            background: transparent !important;
        }
        
        .game-confetti-star {
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }
        
        .game-confetti-ribbon {
            border-radius: 4px;
            width: 8px !important;
            height: 20px !important;
        }
        
        .game-confetti-emoji {
            font-size: 24px;
            animation: emojiFloat 2.5s ease-out forwards;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        @keyframes confettiFall {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg) scale(1);
                opacity: 1;
            }
            25% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) translateX(var(--drift-x, 0)) rotate(1080deg) scale(0.8);
                opacity: 0;
            }
        }
        
        @keyframes emojiFloat {
            0% {
                transform: translateY(0) translateX(0) rotate(0deg) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(50vh) translateX(20px) rotate(180deg) scale(1.2);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) translateX(-20px) rotate(360deg) scale(0.5);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// Auto-inject styles when script loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', injectFeedbackStyles);
} else {
    injectFeedbackStyles();
}

// Export for use in games
window.showGameFeedback = showGameFeedback;
window.getEncouragingMessage = getEncouragingMessage;
window.playCorrectSound = playCorrectSound;
window.playWrongSound = playWrongSound;
