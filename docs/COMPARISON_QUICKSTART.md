# Comparison Mode - Quick Start Guide

## 🚀 5-Minute Quick Start

### Step 1: Access the Feature
1. Log in as a **Teacher**
2. Navigate to **Dashboard** (should be default page)
3. Scroll down to **"Data Science Analytics"** section

### Step 2: Enable Comparison Mode
1. Look for the **"Scope"** toggle buttons
2. You'll see: `General` `Grade` `Section` `Student` `Compare`
3. Click **"Compare"** button (it will turn purple when active)

### Step 3: Select What to Compare
Two new dropdowns will appear:

#### A. Choose Comparison Type
Click the **"Compare By"** dropdown:
- **Students** - Compare individual students head-to-head
- **Sections** - Compare classroom sections
- **Grades** - Compare grade levels (4, 5, 6)

#### B. Select Items
A checkbox list will appear below.

**Example:** If you selected "Students":
```
☑ Alice Chen (Grade 4)
☑ Bob Kumar (Grade 5)
☐ Carol Smith (Grade 6)
☑ David Lee (Grade 4)
```
✅ Check 2-3 students to compare

### Step 4: Choose a Metric
On the right side, select from the **"Metric"** dropdown:
- Average Score by Game
- Engagement (Games Played)
- Low-Progress Students by Grade
- Current Level Distribution
- Mastery by Skill/Game

### Step 5: View the Comparison
The chart will update to show:
- **Multiple colored bars** - one color per item you selected
- **Legend** - shows which color represents which student/section/grade
- **Summary cards** - 4 stat cards with comparison info
- **Insight text** - explains what you're seeing

### Step 6: Explore
Try:
- Switching between different metrics
- Selecting/deselecting items (checkboxes)
- Hovering over bars to see exact values
- Clicking legend items to hide/show datasets

---

## 📊 Example Use Cases

### Compare Your Top 3 Students
**Goal:** See who performs best across all games

1. Compare By: **Students**
2. Select: Your top 3 students
3. Metric: **Average Score by Game**
4. **Look for:** Which student has the tallest bars

**Insight:** Helps identify strongest performer for recognition/rewards

---

### Compare Two Sections
**Goal:** See which classroom is doing better

1. Compare By: **Sections**
2. Select: Two classroom sections
3. Metric: **Mastery by Skill/Game**
4. **Look for:** Which section has higher mastery percentages

**Insight:** Helps identify effective teaching methods

---

### Compare Grade Levels
**Goal:** Check if curriculum progression is working

1. Compare By: **Grades**
2. Select: Grade 4, 5, and 6
3. Metric: **Average Score by Game**
4. **Look for:** Scores should increase from Grade 4 → 5 → 6

**Insight:** Validates curriculum is appropriately leveled

---

### Find At-Risk Students
**Goal:** Identify which sections need intervention

1. Compare By: **Sections**
2. Select: All your sections
3. Metric: **Low-Progress Students by Grade**
4. **Look for:** Sections with tallest bars (most at-risk students)

**Insight:** Prioritize intervention efforts

---

## 🎨 Understanding the Chart

### Colors
Each compared item gets a unique color:
- 🔵 First item: Indigo
- 🟢 Second item: Green
- 🟠 Third item: Orange
- 🔴 Fourth item: Red
- (and so on...)

### Legend
At the top of the chart, you'll see:
```
■ Alice Chen   ■ Bob Kumar   ■ David Lee
```
**Tip:** Click a legend item to hide/show that dataset

### Tooltips
Hover over any bar to see:
```
┌─────────────────────┐
│ Math Card Match     │
│ Alice: 120 pts avg  │
└─────────────────────┘
```

### Summary Cards
Four cards show:
1. **Items Compared** - How many items selected
2. **Data Points** - How many categories in chart
3. **Comparison Type** - Students/Sections/Grades
4. **Metric** - Current metric name

---

## ⚠️ Common Mistakes

### ❌ Mistake 1: Comparing Too Many Items
**Problem:** Selecting 10+ students makes chart unreadable  
**Solution:** Compare 2-5 items at a time

### ❌ Mistake 2: Wrong Comparison Type
**Problem:** Selected "Sections" but wanted to compare students  
**Solution:** Change "Compare By" dropdown, checkboxes will update

### ❌ Mistake 3: No Items Selected
**Problem:** Chart shows "No items selected"  
**Solution:** Check at least 2 items from the list

### ❌ Mistake 4: Can't Find Comparison Mode
**Problem:** Not seeing "Compare" button  
**Solution:** Make sure you're on the Dashboard page and scrolled to "Data Science Analytics"

---

## 🔄 Switching Back to Normal Mode

To exit comparison mode:
1. Click any other Scope button:
   - **General** - See all students across all sections
   - **Grade** - Filter by grade level
   - **Section** - Filter by specific section
   - **Student** - View individual student
2. The comparison dropdowns will disappear
3. Chart returns to single-dataset view

---

## 💡 Pro Tips

### Tip 1: Start Simple
Your first comparison: Compare just 2 students with "Average Score by Game"

### Tip 2: Compare Apples to Apples
- Student vs Student ✅
- Section vs Section ✅
- Grade vs Grade ✅
- Student vs Section ❌ (doesn't make sense)

### Tip 3: Use Right Metric for Your Question
- "Who scores higher?" → **Average Score by Game**
- "Who plays more?" → **Engagement (Games Played)**
- "Who needs help?" → **Low-Progress Students**
- "Who's progressing?" → **Current Level Distribution**
- "Who masters content?" → **Mastery by Skill/Game**

### Tip 4: Check Console for Errors
If something looks wrong:
1. Press `F12` to open browser console
2. Look for red error messages
3. Take a screenshot and report to developer

---

## 📱 Mobile/Tablet Users

### On Small Screens
- Controls stack vertically (more scrolling)
- Charts may require horizontal scroll
- Checkbox list becomes scrollable
- All features still work!

### Best Experience
- Use landscape orientation
- Zoom out if needed
- Desktop/laptop recommended for detailed analysis

---

## 🆘 Troubleshooting

### Problem: Chart is Blank
**Possible causes:**
- No items selected → Check 2+ items
- Selected items have no data → Try different items
- JavaScript error → Check console (F12)

### Problem: Can't See All Items in List
**Solution:** The checkbox area is scrollable - scroll down!

### Problem: Colors Look Similar
**Solution:** Read legend text labels, don't rely on color alone

### Problem: Numbers Don't Look Right
**Solution:**
- Hover over bars to see exact values
- Check which comparison type you selected
- Sections/Grades show averages, not individual student values

---

## 📚 More Information

### For Detailed Technical Info
Read: `COMPARISON_MODE_COMPLETE.md`

### For Comprehensive Testing
Read: `COMPARISON_MODE_TEST_CHECKLIST.md`

### For Visual Examples
Read: `COMPARISON_MODE_VISUAL_GUIDE.md`

### For Implementation Details
Read: `COMPARISON_IMPLEMENTATION_SUMMARY.md`

---

## ✅ Quick Checklist

Before asking for help, verify:

- [ ] You're logged in as a **Teacher**
- [ ] You're on the **Dashboard** page
- [ ] You scrolled to **"Data Science Analytics"** section
- [ ] You clicked the **"Compare"** button in Scope toggle
- [ ] You selected a **"Compare By"** type
- [ ] You **checked 2+ items** from the list
- [ ] You selected a **Metric** from the dropdown
- [ ] Your browser is **up-to-date**
- [ ] JavaScript is **enabled**
- [ ] You checked the **browser console** for errors (F12)

---

## 🎓 Learning Path

### Beginner (First Time)
1. Read this Quick Start (you're here!)
2. Try: Compare 2 students, "Average Score by Game"
3. Experiment: Click different metrics

### Intermediate (After First Try)
1. Try all 5 metrics
2. Compare sections instead of students
3. Compare grade levels
4. Read: `COMPARISON_MODE_VISUAL_GUIDE.md`

### Advanced (Regular User)
1. Use comparison for real teaching decisions
2. Export data for further analysis
3. Combine with other dashboard features
4. Provide feedback on desired features

---

## 🎯 Summary

**What is it?**  
A way to compare students, sections, or grades side-by-side using charts

**Where is it?**  
Dashboard → Data Science Analytics → Scope: Compare

**How do I use it?**  
1. Click "Compare"
2. Choose what to compare (students/sections/grades)
3. Check 2+ items
4. Pick a metric
5. View the chart!

**What can I learn?**  
- Who performs best
- Which sections are strongest
- If grade progression is working
- Where to focus intervention efforts

---

**Time to Try:** Less than 5 minutes  
**Difficulty:** Easy (if you can use checkboxes, you can use this!)  
**Support:** Check browser console (F12) for errors, see docs for details

**Ready? Let's compare! 🚀**
