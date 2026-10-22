# Comparison Mode - Visual Guide

## UI Layout Overview

```
┌─────────────────────────────────────────────────────────────────────┐
│  Teacher Portal · Grades 4–6                                        │
│  Dashboard                                                          │
│  Manage classrooms, quizzes and student progress                    │
│                                                                     │
│  ┌─────────┬───────────┬──────────┬────────────┐                  │
│  │Dashboard│Classrooms │ Students │Export Data │                  │
│  └─────────┴───────────┴──────────┴────────────┘                  │
└─────────────────────────────────────────────────────────────────────┘
```

## Comparison Mode - Controls Section

### When "Compare" Scope is Selected

```
┌─────────────────────────────────────────────────────────────────────┐
│ 📊 Data Science Analytics                                          │
│ Filter by student, section, or the whole school (Grades 4–6)       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                     │
│ Scope:                                                              │
│ ┌─────────┬──────┬─────────┬─────────┬─────────┐                  │
│ │ General │ Grade│ Section │ Student │COMPARE  │                  │
│ └─────────┴──────┴─────────┴─────────┴─────────┘                  │
│                                         ^^^^^^^^^ (Active)          │
│                                                                     │
│ Compare By:              Select Items to Compare:                  │
│ ┌─────────────────┐      ┌───────────────────────────────┐        │
│ │ ▼ Students      │      │ ☑ Alice Chen (Grade 4)        │        │
│ │   Students      │      │ ☑ Bob Kumar (Grade 5)         │        │
│ │   Sections      │      │ ☐ Carol Smith (Grade 6)       │        │
│ │   Grades        │      │ ☑ David Lee (Grade 4)         │        │
│ └─────────────────┘      │ ☐ Emma Wilson (Grade 5)       │        │
│                          │ ... (scrollable list)          │        │
│                          └───────────────────────────────┘        │
│                                                                     │
│                                           Metric:                   │
│                                           ┌──────────────────────┐ │
│                                           │ ▼ Average Score      │ │
│                                           │   by Game            │ │
│                                           └──────────────────────┘ │
│                                                                     │
│ Showing: Comparison — 3 student(s) selected                        │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

## Summary Cards in Comparison Mode

```
┌─────────────────────────────────────────────────────────────────────┐
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐ ┌────────────┐│
│ │ITEMS COMPARED│ │ DATA POINTS  │ │COMPARISON TYPE│ │   METRIC   ││
│ │      3       │ │      4       │ │   Students    │ │ Avg Score  ││
│ └──────────────┘ └──────────────┘ └──────────────┘ └────────────┘│
└─────────────────────────────────────────────────────────────────────┘
```

## Comparison Chart - Student vs Student

### Example: 3 Students - Average Score by Game

```
┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│ Legend:  ■ Alice Chen   ■ Bob Kumar   ■ David Lee                  │
│                                                                     │
│ 200│                                                                │
│    │                                                                │
│ 150│     ███            ███                                         │
│    │     ███            ███       ███                               │
│ 100│     ███  ███       ███       ███       ███                     │
│    │     ███  ███  ███  ███  ███  ███  ███  ███                     │
│  50│     ███  ███  ███  ███  ███  ███  ███  ███                     │
│    │     ███  ███  ███  ███  ███  ███  ███  ███                     │
│   0└─────┴────┴────┴────┴────┴────┴────┴────┴────                   │
│         Math  Number Rocket Factor                                 │
│         Card   Wars   Math   Pond                                  │
│         Match                                                       │
│                                                                     │
│ 💡 Comparing 3 students across 4 data point(s).                    │
└─────────────────────────────────────────────────────────────────────┘
```

## Comparison Types - Data Representation

### 1️⃣ Student-to-Student Comparison

**Data shown per student:**
- ✅ Individual student's exact values
- ✅ Direct head-to-head comparison
- ✅ Best for comparing specific individuals

**Example Metrics:**
```
Average Score by Game
  Alice: [Math Card: 120, Number Wars: 95, Rocket Math: 110, Factor Pond: 105]
  Bob:   [Math Card: 130, Number Wars: 100, Rocket Math: 120, Factor Pond: 90]
  David: [Math Card: 110, Number Wars: 105, Rocket Math: 115, Factor Pond: 100]
```

### 2️⃣ Section-to-Section Comparison

**Data shown per section:**
- ✅ Aggregated averages across all students in section
- ✅ Shows section-level trends
- ✅ Best for comparing teaching effectiveness

**Example Metrics:**
```
Average Score by Game (Section A vs Section B)
  Section A (15 students avg): [Math Card: 118, Number Wars: 98, ...]
  Section B (18 students avg): [Math Card: 125, Number Wars: 102, ...]
```

### 3️⃣ Grade-to-Grade Comparison

**Data shown per grade:**
- ✅ Aggregated averages across all students in that grade
- ✅ Shows grade-level performance
- ✅ Best for curriculum evaluation

**Example Metrics:**
```
Average Score by Game (Grade 4 vs 5 vs 6)
  Grade 4 (50 students avg): [Math Card: 110, Number Wars: 90, ...]
  Grade 5 (48 students avg): [Math Card: 125, Number Wars: 105, ...]
  Grade 6 (52 students avg): [Math Card: 135, Number Wars: 115, ...]
```

## Color Palette

Charts use 7 rotating colors for datasets:

```
Dataset 1: 🔵 #6366f1 (Indigo)   - Primary brand color
Dataset 2: 🟢 #10b981 (Green)    - Success/positive
Dataset 3: 🟠 #f59e0b (Orange)   - Warning/attention
Dataset 4: 🔴 #ef4444 (Red)      - Alert/critical
Dataset 5: 🟣 #8b5cf6 (Purple)   - Alternate 1
Dataset 6: 🌸 #ec4899 (Pink)     - Alternate 2
Dataset 7: 🔷 #14b8a6 (Teal)     - Alternate 3
```

If comparing 8+ items, colors repeat from the start.

## Tooltip Format

When hovering over bars in comparison mode:

```
┌─────────────────────────┐
│ Math Card Match         │
│ Alice Chen: 120 pts avg │
└─────────────────────────┘
```

or

```
┌─────────────────────────┐
│ Rocket Math             │
│ Section A: 118 pts avg  │
└─────────────────────────┘
```

## Metric-Specific Behaviors

### 1. Average Score by Game
```
Chart shows: Multiple bars per game (one per compared item)
Y-axis: Points (0-200+)
X-axis: Game names
Best for: Seeing which student/section/grade excels at which game
```

### 2. Engagement (Games Played)
```
Chart shows: Single bar per compared item
Y-axis: Number of games (0-10+)
X-axis: Student/Section/Grade names
Best for: Identifying most/least active participants
```

### 3. Low-Progress Students by Grade
```
Chart shows: Multiple bars per grade level OR single bar per item
Y-axis: Count of students (0-20+)
X-axis: Grade levels OR compared item names
Best for: Spotting at-risk populations by group
```

### 4. Current Level Distribution
```
Chart shows: Multiple bars per level (one per compared item)
Y-axis: Count of students (0-15+)
X-axis: Level numbers (Level 1, Level 2, ...)
Best for: Seeing progression spread across groups
```

### 5. Mastery by Skill/Game
```
Chart shows: Multiple bars per game (one per compared item)
Y-axis: Percentage (0-100%)
X-axis: Game names
Best for: Measuring depth of understanding across groups
```

## Responsive Behavior

### Desktop (1200px+)
- All controls in single row
- Charts full width
- 4 summary cards in one row

### Tablet (768px - 1199px)
- Controls wrap to 2 rows
- Charts full width
- 2 summary cards per row

### Mobile (<768px)
- Controls stack vertically
- Horizontal scroll for charts
- 1 summary card per row
- Checkbox list becomes scrollable

## Accessibility Features

### Keyboard Navigation
- ✅ Tab through checkboxes
- ✅ Space to toggle checkboxes
- ✅ Arrow keys in dropdowns

### Screen Readers
- ✅ Labels properly associated with inputs
- ✅ Chart has aria-label
- ✅ Summary cards have semantic structure

### Color Blindness
- ✅ Distinct colors chosen for common color blindness types
- ✅ Legend text labels each dataset
- ✅ Tooltips reinforce dataset identity

## Animation

### Chart Transitions
- **Duration:** 500ms
- **Easing:** easeOutQuart (smooth deceleration)
- **Effect:** Bars grow from bottom to final height

### Scope Switching
- **Effect:** Instant (no animation)
- **Reason:** Fast feedback for user interaction

## Empty States

### No Items Selected
```
┌─────────────────────────────────────────┐
│                                         │
│   📊 No items selected                  │
│                                         │
│   Select items to compare from          │
│   the list above                        │
│                                         │
└─────────────────────────────────────────┘
```

### No Data Available
```
┌─────────────────────────────────────────┐
│                                         │
│   📭 No Data                            │
│                                         │
│   The selected items have no            │
│   progress data yet                     │
│                                         │
└─────────────────────────────────────────┘
```

## Usage Scenarios

### Scenario 1: Identify Top Performers
**Goal:** Find the best student in math games
**Steps:**
1. Scope = Compare
2. Compare By = Students
3. Select all students in a class
4. Metric = Average Score by Game
5. **Result:** See which student has highest bars

### Scenario 2: Compare Teaching Methods
**Goal:** See which section performs better
**Steps:**
1. Scope = Compare
2. Compare By = Sections
3. Select 2 sections with different teachers
4. Metric = Mastery by Skill/Game
5. **Result:** Compare mastery % between sections

### Scenario 3: Curriculum Evaluation
**Goal:** Check if grade progression is appropriate
**Steps:**
1. Scope = Compare
2. Compare By = Grades
3. Select Grade 4, 5, 6
4. Metric = Average Score by Game
5. **Result:** Should see upward trend across grades

### Scenario 4: Identify Struggling Groups
**Goal:** Find which section needs help
**Steps:**
1. Scope = Compare
2. Compare By = Sections
3. Select all sections
4. Metric = Low-Progress Students by Grade
5. **Result:** Section with tallest bars needs intervention

## Best Practices

### ✅ Do:
- Select 2-5 items for clearest comparison
- Use consistent comparison types for related analyses
- Export data for further statistical analysis
- Check console for errors during testing

### ❌ Don't:
- Compare too many items (chart becomes cluttered)
- Mix comparison types in same analysis session
- Rely solely on visual comparison for important decisions
- Ignore students with no data (they may need outreach)

## Technical Notes

### Performance
- Client-side rendering (no server calls after initial load)
- Chart.js canvas-based (hardware accelerated)
- Recommended max: 10 compared items

### Browser Requirements
- ES6 JavaScript support
- Canvas API support
- LocalStorage for persistence (future feature)

### Data Caching
- Data loaded once on page load
- All comparisons calculated from cached data
- Refresh page to get latest data

---

## Quick Reference Card

```
╔═══════════════════════════════════════════════════════════════╗
║              COMPARISON MODE QUICK REFERENCE                  ║
╠═══════════════════════════════════════════════════════════════╣
║ Access:     Click "Compare" in Scope toggle                  ║
║ Select:     Use checkboxes under "Select Items to Compare"   ║
║ Compare:    Choose Students, Sections, or Grades             ║
║ Metrics:    All 5 metrics fully supported                    ║
║ Chart:      Multi-colored grouped bar chart                  ║
║ Legend:     Click to toggle dataset visibility               ║
║ Summary:    4 stat cards + insight text                      ║
║ Colors:     7 distinct colors, rotating                      ║
╚═══════════════════════════════════════════════════════════════╝
```

---

**Need help?** Check the browser console for error messages or refer to `COMPARISON_MODE_TEST_CHECKLIST.md` for troubleshooting steps.
