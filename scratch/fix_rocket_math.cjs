const fs = require('fs');
const path = 'resources/views/games/rocket_math.blade.php';
let content = fs.readFileSync(path, 'utf8');

// 1. Add helpNavBtn if not present
if (!content.includes('id="helpNavBtn"')) {
    const targetNav = '<div class="eduplay-game-nav ms-1">';
    const replaceNav = '<div class="eduplay-game-nav ms-1">\r\n                            <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play" onclick="rmOpenTut(true)">\r\n                                <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>\r\n                            </button>';
    content = content.replace(targetNav, replaceNav);
    console.log('Added helpNavBtn');
} else {
    console.log('helpNavBtn already present');
}

// 2. Add RM_TUTORIAL_KEY if not present
if (!content.includes('const RM_TUTORIAL_KEY')) {
    const targetTut = '// ===== FLASH CARD INSTRUCTIONS & TUTORIAL CONTROLLER =====';
    const replaceTut = '// ===== FLASH CARD INSTRUCTIONS & TUTORIAL CONTROLLER =====\r\n        const RM_TUTORIAL_KEY = \'rm_tutorial_done\';';
    content = content.replace(targetTut, replaceTut);
    console.log('Added RM_TUTORIAL_KEY');
} else {
    console.log('RM_TUTORIAL_KEY already present');
}

// 3. Expose window functions for rmOpenTut etc.
if (!content.includes('window.rmOpenTut = rmOpenTut')) {
    const targetFunc = 'function rmOpenTut(fromGame = false) {';
    const replaceFunc = 'window.rmOpenTut = function(fromGame = false) { return rmOpenTut(fromGame); };\r\n        window.rmCloseTut = function(andStart = false) { return rmCloseTut(andStart); };\r\n        window.rmGoFlashCard = function(c) { return rmGoFlashCard(c); };\r\n        window.rmStepFlashCard = function(d) { return rmStepFlashCard(d); };\r\n        window.rmStartPracticeFromTut = function() { return rmStartPracticeFromTut(); };\r\n        ' + targetFunc;
    content = content.replace(targetFunc, replaceFunc);
    console.log('Exposed window.rmOpenTut');
}

fs.writeFileSync(path, content, 'utf8');
console.log('Successfully updated rocket_math.blade.php');
