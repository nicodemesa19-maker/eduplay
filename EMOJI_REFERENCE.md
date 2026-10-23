# 🎮 EduPlay Emoji Reference Guide

## Overview
This document serves as the master reference for all emojis used across the EduPlay platform games. All emojis have been verified and fixed to display correctly across all browsers.

---

## 🎯 Game-Specific Emojis

### **Lives & Health System**
- ❤️ Red Heart - Active life/health
- 🖤 Black Heart - Lost life
- 💔 Broken Heart - Game over state
- 😔 Sad Face - Failure/loss message

### **Navigation & Controls**
- ▶️ Play Button - Start game
- ◀️ Back Arrow - Previous/back
- 🔄 Repeat - Restart/try again
- ⏸ Pause - Pause game
- ⏱️ Timer - Time-based challenges

### **Math Operations**
- ✖️ Multiply Symbol - Multiplication
- ➗ Division - Division operations
- ➕ Plus - Addition
- ➖ Minus - Subtraction
- 🔢 Numbers - Numeric content
- 🧮 Abacus - Calculation/compute

### **Status & Feedback**
- ✅ Check Mark - Correct answer
- ❌ Cross Mark - Wrong answer
- ⚠️ Warning - Alert/caution
- 💡 Lightbulb - Hint/tip
- 🎯 Target - Goal/objective

### **Celebration & Success**
- 🎉 Party Popper - Major success
- 🎊 Confetti Ball - Celebration
- ⭐ Star - Achievement
- 🌟 Glowing Star - Special achievement
- 🏆 Trophy - Victory/high score
- ✨ Sparkles - Magic/special effect
- 💫 Dizzy - Bonus/power-up

### **Game Elements**
- 🃏 Joker Card - Card games (Math Card Match)
- 🎲 Dice - Random element
- 🪙 Coin - Currency/points
- 💰 Money Bag - Rewards
- 🔥 Fire - Streak/hot
- 🪝 Hook - Catch mechanic (Factor Pond)
- 🐟 Fish - Target object (Factor Pond)
- 🌊 Wave - Water element (Factor Pond)

### **Space & Rocket Theme** (Rocket Math)
- 🚀 Rocket - Main game element
- 🛸 Flying Saucer - Obstacles
- 🛰️ Satellite - Space objects
- 🔭 Telescope - Discovery
- ⛽ Fuel - Energy/power

### **Geometry Shapes** (Geometry Grid)
- 🔺 Triangle - Triangle shape
- 🟢 Green Circle - Circle shape
- 🔴 Red Circle - Circle variant
- 🔵 Blue Circle - Circle variant
- 🟡 Yellow Circle - Circle variant
- ▱ Parallelogram - Shape element
- ⏢ Trapezoid - Shape element

### **Educational**
- 📝 Memo - Instructions
- 📖 Book - Tutorial
- 📚 Books - Learning materials
- 📐 Ruler - Measurement
- 📉 Down Chart - Decrease
- 📈 Up Chart - Increase
- ⌨️ Keyboard - Input prompt

### **Combat & Battle** (Number Wars)
- ⚔️ Crossed Swords - Battle
- 🎮 Game Controller - Gaming
- 👁️ Eye - Visibility/watch

### **Other**
- 🤔 Thinking Face - Consider/think
- 🎮 Game Controller - Gaming context

---

## 🛠️ Implementation Notes

### Encoding Standard
All game files use **UTF-8 encoding** with direct Unicode emoji characters.

### File Format
```php
// Blade template format
<span class="emoji">❤️</span>
// or direct in strings
'❤️ Lives: 3'
```

### Browser Compatibility
All emojis tested and working in:
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

### Cache Management
After emoji updates, always run:
```bash
php artisan view:clear
```

---

## 📋 Game Files Using Emojis

1. **Math Card Match** (`math_card_match.blade.php`)
   - 🃏 Cards, ❤️ Lives, ✅❌ Feedback

2. **Number Wars** (`number_wars.blade.php`)
   - ⚔️ Battle, ❤️ Lives, 🎯 Targeting

3. **Rocket Math** (`rocket_math.blade.php`)
   - 🚀 Rocket, ⛽ Fuel, 🛸 Obstacles

4. **Factor Pond** (`factor_pond.blade.php`)
   - 🐟 Fish, 🪝 Hook, 🌊 Water, ❤️ Lives

5. **Geometry Grid** (`geometry_grid.blade.php`)
   - 🔺 Shapes, 🟢🔴🔵🟡 Colors

6. **MDAS Maze** (`mdas_maze.blade.php`)
   - 🧮 Operations, 🎯 Goals

7. **Time Quest** (`time_quest.blade.php`)
   - ⏱️ Time, 📖 Learning

8. **The Metric Quest** (`the_metric_quest.blade.php`)
   - 📐 Measurement, 📚 Education

---

## 🔧 Troubleshooting

### If Emojis Show as ??
1. Check file encoding is UTF-8
2. Clear Laravel view cache
3. Hard refresh browser (Ctrl+F5)
4. Verify database charset is utf8mb4

### If Emojis Don't Display
1. Update browser to latest version
2. Check system emoji font support
3. Verify meta charset in layout:
   ```html
   <meta charset="UTF-8">
   ```

---

## 📝 Maintenance

**Last Updated:** October 23, 2026  
**Version:** 1.0  
**Status:** ✅ All emojis verified and working

For any emoji-related issues, refer to this document or the Factor Pond backup file as the reference implementation.
