# Comparison Mode - Testing Checklist

## Pre-Test Setup
- [ ] Ensure database has at least 3 students with progress data
- [ ] Ensure students are in different classrooms/sections
- [ ] Ensure students have different grade levels (4, 5, 6)
- [ ] Clear browser cache if testing after changes
- [ ] Open browser console to check for JavaScript errors

## Test 1: Basic Comparison Mode Access
- [ ] Navigate to Teacher Dashboard
- [ ] Locate the "Scope" toggle buttons
- [ ] Click the **"Compare"** button
- [ ] Verify two new dropdowns appear:
  - "Compare By" dropdown
  - "Select Items to Compare" checkbox area

## Test 2: Student-to-Student Comparison

### Test 2.1: Average Score by Game
- [ ] Set Compare By = "Students"
- [ ] Check 2-3 student names from the list
- [ ] Select Metric = "Average Score by Game"
- [ ] Verify:
  - Chart shows multiple colored bars per game
  - Each student has their own color
  - Legend shows student names
  - Summary cards show comparison stats
  - No JavaScript errors in console

### Test 2.2: Engagement (Games Played)
- [ ] Keep same students selected
- [ ] Change Metric = "Engagement (Games Played)"
- [ ] Verify:
  - Chart shows bars for each selected student
  - Values match the number of games each student started
  - No errors

### Test 2.3: Low-Progress Students
- [ ] Keep same students selected
- [ ] Change Metric = "Low-Progress Students by Grade"
- [ ] Verify:
  - Chart shows 0 or 1 for each student (at-risk status)
  - Labels show grade levels
  - No errors

### Test 2.4: Current Level Distribution
- [ ] Keep same students selected
- [ ] Change Metric = "Current Level Distribution"
- [ ] Verify:
  - Chart shows level distribution for each student
  - X-axis shows "Level 1", "Level 2", etc.
  - No errors

### Test 2.5: Mastery by Skill/Game
- [ ] Keep same students selected
- [ ] Change Metric = "Mastery by Skill/Game"
- [ ] Verify:
  - Chart shows percentage mastery per game
  - Values are 0-100%
  - No errors

## Test 3: Section-to-Section Comparison

### Test 3.1: Setup
- [ ] Set Compare By = "Sections"
- [ ] Verify checkbox list now shows classroom names
- [ ] Check 2-3 sections from the list

### Test 3.2: All Metrics
- [ ] Test each of the 5 metrics (same as Test 2.1-2.5)
- [ ] Verify charts show section names in legend
- [ ] Verify values are aggregated/averaged per section
- [ ] No JavaScript errors

## Test 4: Grade-to-Grade Comparison

### Test 4.1: Setup
- [ ] Set Compare By = "Grades"
- [ ] Verify checkbox list shows "Grade 4", "Grade 5", "Grade 6"
- [ ] Check all 3 grades

### Test 4.2: All Metrics
- [ ] Test each of the 5 metrics
- [ ] Verify charts show "Grade 4", "Grade 5", "Grade 6" in legend
- [ ] Verify values are aggregated/averaged per grade
- [ ] No JavaScript errors

## Test 5: Edge Cases

### Test 5.1: No Items Selected
- [ ] In Compare mode, uncheck all items
- [ ] Verify chart shows "No items selected" or empty state
- [ ] No JavaScript errors

### Test 5.2: Single Item Selected
- [ ] Select only 1 student/section/grade
- [ ] Verify chart still renders (single dataset)
- [ ] No errors

### Test 5.3: Student with No Progress
- [ ] Include a student who has never played any games
- [ ] Verify they show 0 values (not errors)
- [ ] Chart renders correctly

### Test 5.4: Empty Section
- [ ] Select a section with no students
- [ ] Verify it shows 0 values (not errors)
- [ ] Chart renders correctly

### Test 5.5: Switch Between Compare Types
- [ ] Start with Students selected
- [ ] Switch to Sections
- [ ] Verify checkboxes update correctly
- [ ] Switch to Grades
- [ ] Verify checkboxes update correctly
- [ ] No errors during switches

## Test 6: Context and Summary

### Test 6.1: Context Label
- [ ] Verify context label updates when items are selected
- [ ] Should show: "Showing: **Comparison** — X student(s)/section(s)/grade(s) selected"

### Test 6.2: Summary Cards
- [ ] Verify 4 summary cards appear in comparison mode
- [ ] Cards should show:
  1. Items Compared (count)
  2. Data Points (count)
  3. Comparison Type (Students/Sections/Grades)
  4. Metric (current metric name)

### Test 6.3: Insight Text
- [ ] Verify insight text updates with selection
- [ ] Should show: "Comparing X [type] across Y data point(s)."

## Test 7: Chart Appearance

### Test 7.1: Colors
- [ ] Verify each dataset has a distinct color
- [ ] Colors should cycle through: indigo, green, orange, red, purple, pink, teal

### Test 7.2: Legend
- [ ] Verify legend appears at top of chart
- [ ] Legend items match selected items (students/sections/grades)
- [ ] Clicking legend items toggles datasets (Chart.js default behavior)

### Test 7.3: Tooltips
- [ ] Hover over bars in the chart
- [ ] Verify tooltips show:
  - Dataset name (student/section/grade)
  - Value with appropriate unit
  - No [object Object] or undefined

## Test 8: Switch Back to Other Scopes

### Test 8.1: General Scope
- [ ] Click "General" scope button
- [ ] Verify comparison UI disappears
- [ ] Verify chart returns to single-dataset mode
- [ ] No errors

### Test 8.2: Grade Scope
- [ ] Click "Grade" scope button
- [ ] Verify grade filter dropdown appears
- [ ] Verify comparison UI disappears
- [ ] Select a grade and verify chart updates
- [ ] No errors

### Test 8.3: Section Scope
- [ ] Click "Section" scope button
- [ ] Verify section filter dropdown appears
- [ ] Select a section and verify chart updates
- [ ] No errors

### Test 8.4: Student Scope
- [ ] Click "Student" scope button
- [ ] Verify section and student dropdowns appear
- [ ] Select a student and verify chart updates
- [ ] No errors

## Test 9: Browser Compatibility

### Test 9.1: Chrome/Edge
- [ ] Test all above in Chrome or Edge
- [ ] No errors or visual issues

### Test 9.2: Firefox
- [ ] Test all above in Firefox
- [ ] No errors or visual issues

### Test 9.3: Safari (if available)
- [ ] Test all above in Safari
- [ ] No errors or visual issues

## Test 10: Performance

### Test 10.1: Large Datasets
- [ ] Select many items (5+ students/sections)
- [ ] Verify chart renders without lag
- [ ] Verify no performance warnings in console

### Test 10.2: Rapid Switching
- [ ] Rapidly switch between metrics
- [ ] Rapidly check/uncheck items
- [ ] Verify no errors or frozen UI

## Common Issues to Watch For

### ❌ Errors to Check
- [ ] "Cannot read properties of undefined (reading 'reduce')"
- [ ] "Chart.js is not defined"
- [ ] "[object Object]" appearing in chart
- [ ] Empty/blank charts when data exists
- [ ] Console errors about missing properties

### ✅ Expected Behavior
- Smooth transitions between scopes
- Clear, readable charts with proper colors
- Accurate data representation
- No console errors
- Responsive UI (works on mobile/tablet)

## Reporting Issues

If you find any issues during testing:

1. Note which test case failed
2. Check browser console for JavaScript errors
3. Note the exact steps to reproduce
4. Check if issue occurs in multiple browsers
5. Verify database has appropriate test data

## Success Criteria

✅ All 5 metrics work in comparison mode  
✅ All 3 comparison types work (students/sections/grades)  
✅ No JavaScript errors in any test case  
✅ Charts render correctly with multiple datasets  
✅ Summary cards and insights update properly  
✅ Smooth transitions between scopes  
✅ Edge cases handled gracefully (no crashes)

---

## Quick Test (5 minutes)

If you're short on time, run this quick test:

1. Go to Teacher Dashboard
2. Click "Compare" scope
3. Select "Students" compare by
4. Check 2-3 students
5. Cycle through all 5 metrics
6. Check console for errors

If this works without errors, the implementation is solid! ✅
