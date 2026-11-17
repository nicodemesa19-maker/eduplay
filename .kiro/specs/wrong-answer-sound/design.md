# Wrong-Answer Sound Bugfix Design

## Overview

When a student answers incorrectly in any EduPlay game — or fails a Rocket Math session by not launching enough rockets before the timer expires — the intended cue, the provided `wrong-answer.mp3` sound file, is never played. The defect has three forms: five question-style games play no audio at all on a wrong answer; two games (`mdas_maze`, `the_metric_quest`) play a locally-synthesized sawtooth tone instead of the provided file; and Rocket Math plays no audio when a session is failed (the session timer expires with a score below the win threshold). The fix makes `wrong-answer.mp3` the single wrong-answer / session-fail sound across all eight games.

The strategy is centralized and minimal. All eight games already load `public/js/game-feedback.js`. Seven of them route their wrong-answer feedback through the shared `showGameFeedback(isCorrect, ...)` function, so we mirror the existing, proven `playCorrectSound()` helper by adding a sibling `playWrongSound()` that plays `/sounds/wrong-answer.mp3`, and we invoke it in the `else` (wrong) branch of `showGameFeedback`. This single change makes every game that submits a wrong answer through `showGameFeedback` play the correct file. For `mdas_maze` and `the_metric_quest`, we then remove/replace the local synthesized `soundWrong()` calls so that only the `.mp3` is heard — no synthesized wrong tone remains. Rocket Math does not route its session-fail through `showGameFeedback` (its only `showGameFeedback` call is for success), so we expose `playWrongSound` on `window` and call it directly in the session-fail branch of `showSessionResult`.

## Glossary

- **Bug_Condition (C)**: A wrong-answer event or a Rocket Math session-fail — a student submits an incorrect answer in an educational game, or fails a Rocket Math session by not launching enough rockets before the timer expires.
- **Property (P)**: On a wrong-answer event the fixed code plays `wrong-answer.mp3`, and in `mdas_maze`/`the_metric_quest` plays *only* that file (no synthesized wrong tone); on a Rocket Math session-fail the fixed code plays `wrong-answer.mp3`.
- **Preservation (¬C)**: All non-wrong-answer and non-session-fail behavior — correct-answer sounds, session-win feedback, background music, visual feedback, score/lives/streaks, progression — is identical before and after the fix.
- **showGameFeedback(isCorrect, correctAnswer, duration, onContinue, explanation, customIcon)**: The shared feedback function in `public/js/game-feedback.js`. It builds and displays a notification and (currently) plays a sound only when `isCorrect` is true.
- **playCorrectSound()**: The shared helper in `game-feedback.js` that plays `/sounds/correct-answer.mp3` via a cached `Audio` element at 50% volume. It is the pattern `playWrongSound()` mirrors.
- **soundWrong()**: A local function defined in `mdas_maze.blade.php` and `the_metric_quest.blade.php` that plays a synthesized two-note sawtooth tone via the Web Audio `playTone()` helper. This is the synthesized wrong tone the bugfix must eliminate.
- **playTone()**: The Web Audio API oscillator helper used by `soundCorrect()`, `soundWrong()`, `soundWin()`, `soundHint()`, and `soundClick()` in the two games. It is preserved for all non-wrong uses.
- **showSessionResult(won)**: The Rocket Math function (`resources/views/games/rocket_math.blade.php`, ~line 1737) that builds and displays the end-of-session result modal. When `won` is true it shows the "Level Complete!" modal with confetti; when `won` is false it shows the "Time's Up!" session-fail modal. The session-fail (`else`) branch is where `playWrongSound()` must be called.
- **endSession()**: The Rocket Math function (~line 1721) invoked when the session timer expires. It computes `won = GameState.score >= WIN_SCORE` and calls `showSessionResult(won)`.
- **WIN_SCORE**: The Rocket Math constant (`= 8`, ~line 1550). A session is won when the student launches at least `WIN_SCORE` rockets correctly before `SESSION_SECS` expires.
- **handleCanisterClick(idx)**: The Rocket Math function that adds a fuel canister; when the accumulated fuel equals the target exactly, success is reported via `showGameFeedback(true, ...)`. Rocket Math has no wrong-answer submission event — its only failing outcome is the session-fail.
- **GameState**: The Rocket Math runtime state object holding `score` (rockets launched correctly), `sessionEnded`, and other session fields used by `showSessionResult`.

## Bug Details

### Bug Condition

The bug manifests in two distinct ways. (A) A student answers a game question incorrectly: depending on the game, the wrong-answer handler either produces no audio at all or plays a synthesized sawtooth tone (`soundWrong()`) instead of the provided `wrong-answer.mp3`. The shared entry point for the question-style games is `showGameFeedback(false, ...)`; the two games with local audio additionally call their own `soundWrong()`. (B) A student fails a Rocket Math session: the session timer expires with `GameState.score < WIN_SCORE`, `endSession()` calls `showSessionResult(false)`, and the "Time's Up!" session-fail modal is shown with no audio at all.

**Formal Specification:**
```
FUNCTION isBugCondition(event)
  INPUT: event representing a student's answer attempt or session outcome in a game
  OUTPUT: boolean

  // Case A: wrong-answer submission in a question-style game
  IF event.type = 'answer_submission' THEN
    RETURN event.isCorrect = FALSE
           AND event.game IN GAMES_WITH_ANSWERS
  END IF

  // Case B: Rocket Math session-fail (timer expires below win threshold)
  IF event.type = 'session_fail'
     AND event.game = 'rocket_math'
     AND event.score < WIN_SCORE THEN
    RETURN TRUE
  END IF

  RETURN FALSE
END FUNCTION
```

Where `GAMES_WITH_ANSWERS` is the set of games whose wrong-answer path is in scope: `factor_pond`, `geometry_grid`, `math_card_match`, `number_wars`, `time_quest`, `mdas_maze`, `the_metric_quest`. Rocket Math's in-scope event is the session-fail (`showSessionResult(false)`), not a per-answer submission: it has no wrong-answer submission event, and its only `showGameFeedback` call is for success (`showGameFeedback(true, ...)`). (The `the_metric_quest` "time's up" timeout and stage-fail outcomes are addressed for consistency as related synthesized-tone sites — see Fix Implementation.)

### Examples

- **factor_pond (silent)**: Student picks the wrong factor list. `showGameFeedback(false, correctAnswer, ...)` runs. Expected: `wrong-answer.mp3` plays. Actual (unfixed): no audio at all.
- **geometry_grid (silent)**: Student picks the wrong shape. `showGameFeedback(false, correct, ...)` runs. Expected: `wrong-answer.mp3`. Actual: silence.
- **math_card_match (silent)**: A non-matching card pair is flipped. `showGameFeedback(false, null, ...)` runs. Expected: `wrong-answer.mp3`. Actual: silence.
- **number_wars (silent)**: Student enters a wrong answer. `showGameFeedback(false, currentProblem.answer, ...)` runs. Expected: `wrong-answer.mp3`. Actual: silence.
- **time_quest (silent)**: Student picks the wrong time. `showGameFeedback(false, state.answerLabel, ...)` runs. Expected: `wrong-answer.mp3`. Actual: silence.
- **mdas_maze (synthesized)**: Student rolls/solves a wrong MDAS challenge. `soundWrong()` (sawtooth) plays, then `showGameFeedback(false, ...)` shows feedback. Expected: only `wrong-answer.mp3`. Actual: synthesized tone, no `.mp3`.
- **the_metric_quest (synthesized)**: Student picks a wrong conversion. `soundWrong()` plays, then `showGameFeedback(false, q.correct, ...)`. Expected: only `wrong-answer.mp3`. Actual: synthesized tone, no `.mp3`.
- **Edge — the_metric_quest timeout**: Time expires on a question (`onTimeOut`). `soundWrong()` plays; the feedback box is marked `wrong` and the correct conversion is shown. This is a per-question wrong outcome. Expected (fixed): `wrong-answer.mp3` plays, no synthesized tone. Actual (unfixed): synthesized tone only.
- **Edge — the_metric_quest stage-fail**: Student finishes a stage below the accuracy threshold. `soundWrong()` plays with the "Keep Exploring!" overlay. Expected (fixed): synthesized tone removed (replaced by `wrong-answer.mp3` as a failure cue). Actual (unfixed): synthesized tone only.
- **rocket_math (session-fail, silent)**: Student launches only 5 of the needed 8 rockets before the 300-second timer expires. `endSession()` computes `won = false`, calls `showSessionResult(false)`, and the "Time's Up!" modal appears with "❌ Only 5 correct — need 8 to advance." Expected: `wrong-answer.mp3` plays. Actual (unfixed): no audio at all.

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**
- A correct answer SHALL continue to play `correct-answer.mp3` (via `playCorrectSound()`) and show the correct-answer visual feedback, including confetti. (Req 3.1)
- In `mdas_maze` and `the_metric_quest`, a correct answer SHALL continue to play its existing correct-answer sound — the local synthesized `soundCorrect()` tone together with `correct-answer.mp3`. `soundCorrect()` and `playTone()` for correct/win/hint/click sounds are untouched. (Req 3.5)
- Background game music (via `game-music-player.js`) SHALL continue to play unaffected by the wrong-answer sound changes. (Req 3.2)
- A wrong answer SHALL continue to show the existing wrong-answer visual feedback — encouraging message, correct-answer text, explanation, and the Continue button — with no visual change; only an audio cue is added. (Req 3.3)
- Score, lives, streaks, and game progression SHALL update exactly as before; the fix modifies only sound calls, not game-state logic. (Req 3.4)
- A Rocket Math session win (score ≥ WIN_SCORE) SHALL continue to show the existing "Level Complete!" success feedback and play the existing success sound (confetti) unchanged. The fix adds sound only to the session-fail branch. (Req 3.6)
- A Rocket Math session fail SHALL continue to show the existing "Time's Up!" modal and its progress message ("❌ Only X correct — need 8 to advance.") unchanged; only an audio cue is added. (Req 3.7)

**Scope:**
All inputs that are NOT a wrong-answer event or a Rocket Math session-fail SHALL be completely unaffected by this fix. This includes:
- Correct-answer submissions (any game)
- Rocket Math session wins (existing success feedback and sound)
- Background music playback and music-player controls
- All wrong-answer visual feedback, scoring, streak, and progression logic
- The Rocket Math session-fail modal visual (text, emoji, buttons)
- The synthesized correct/win/hint/click tones in `mdas_maze` and `the_metric_quest`

## Hypothesized Root Cause

Based on the bug description and code inspection, the root cause is an omission in the shared feedback module combined with per-game divergence and an un-wired session-fail path:

1. **Missing wrong-sound call in the shared feedback function**: `showGameFeedback` plays a sound only in its `if (isCorrect)` branch (`playCorrectSound()`). The `else` branch builds the wrong-answer notification but plays no sound. Because every question-style game routes wrong answers through this `else` branch, five games get silence by construction.
2. **Per-game synthesized substitute**: `mdas_maze` and `the_metric_quest` each define a local `soundWrong()` using `playTone()` with sawtooth waves and call it on wrong answers (and, in `the_metric_quest`, also on timeout and stage-fail). These produce a synthesized tone instead of the provided `wrong-answer.mp3`.
3. **No central wrong-sound helper**: Unlike the correct path, which has a shared `playCorrectSound()`, there is no shared `playWrongSound()` helper, so the wrong path never references the existing `wrong-answer.mp3` asset.
4. **Asset exists but is unreferenced**: `public/sounds/wrong-answer.mp3` is present but referenced nowhere in application code — confirming it was intended to be used but never wired up.
5. **Rocket Math session-fail plays no audio**: `showSessionResult(won)` builds the result modal but never calls any sound function in either branch. The success branch relies on `createConfetti()` for visual feedback (no explicit sound), and the session-fail (`else`) branch plays nothing. Because Rocket Math's only `showGameFeedback` call is for success (`handleCanisterClick` → `showGameFeedback(true, ...)`), the shared `else`-branch fix does not reach the session-fail path, so a direct `window.playWrongSound()` call is required there.

The fix addresses root causes 1, 3, and 5 (add a shared `playWrongSound()`, call it in the wrong branch, and call it in the Rocket Math session-fail branch) and root cause 2 (eliminate the per-game synthesized `soundWrong()` calls).

## Correctness Properties

Property 1: Bug Condition - Wrong answer plays wrong-answer.mp3

_For any_ input where the bug condition holds because a student submits a wrong answer in a question-style game (factor_pond, geometry_grid, math_card_match, number_wars, time_quest), the fixed code SHALL play the `wrong-answer.mp3` sound file via the shared wrong-answer feedback path, so the student hears the intended wrong-answer cue.

**Validates: Requirements 2.1**

Property 2: Bug Condition - No synthesized wrong tone in mdas_maze and the_metric_quest

_For any_ input where the bug condition holds and the game is `mdas_maze` or `the_metric_quest`, the fixed code SHALL play only the `wrong-answer.mp3` sound file and SHALL NOT play the synthesized wrong-answer tone (`soundWrong()` / `playTone()` with a sawtooth wrong tone).

**Validates: Requirements 2.2**

Property 3: Bug Condition - Rocket Math session-fail plays wrong-answer.mp3

_For any_ input where the bug condition holds because a Rocket Math session is failed (the session timer expires with a score below the win threshold), the fixed code SHALL play the `wrong-answer.mp3` sound file in the session-fail branch of `showSessionResult`, so the student hears the intended failure cue alongside the existing "Time's Up!" modal.

**Validates: Requirements 2.3**

Property 4: Preservation - Correct-answer behavior unchanged

_For any_ input where the bug condition does NOT hold because the answer is correct (any game), the fixed code SHALL produce the same result as the original code, preserving the `correct-answer.mp3` sound, the confetti visual feedback, and (in `mdas_maze`/`the_metric_quest`) the synthesized `soundCorrect()` tone.

**Validates: Requirements 3.1, 3.5**

Property 5: Preservation - Rocket Math session-win and session-fail modal unchanged

_For any_ input where the bug condition does NOT hold because a Rocket Math session is won (score ≥ WIN_SCORE), the fixed code SHALL produce the same result as the original code, preserving the existing "Level Complete!" success feedback and success sound. _For any_ Rocket Math session-fail, the fixed code SHALL preserve the existing "Time's Up!" modal text, emoji, and buttons unchanged, adding only the `wrong-answer.mp3` audio cue.

**Validates: Requirements 3.6, 3.7**

Property 6: Preservation - Non-answer behavior unchanged

_For any_ input where the bug condition does NOT hold because it is not a wrong-answer event or Rocket Math session-fail (background music playback, visual feedback rendering, score/lives/streak/progression updates), the fixed code SHALL produce the same result as the original code, preserving all non-audio and non-wrong-answer behavior including background music and game-state updates.

**Validates: Requirements 3.2, 3.3, 3.4**

## Fix Implementation

Assuming our root cause analysis is correct:

**File**: `public/js/game-feedback.js`

**Specific Changes**:
1. **Add `playWrongSound()` helper** (mirroring `playCorrectSound()`):
   - Declare a module-level cached `let wrongSoundAudio = null;`.
   - Implement `playWrongSound()`: create `new Audio('/sounds/wrong-answer.mp3')` once (cached), set `volume = 0.5`, then on each call reset `currentTime = 0` and call `.play()` with a `.catch()` that logs a harmless message — exactly like `playCorrectSound()`.
2. **Call `playWrongSound()` in the wrong-answer branch**: In `showGameFeedback`, symmetric to the existing `if (isCorrect) { playCorrectSound(); }`, add an `else { playWrongSound(); }` (or add `playWrongSound()` at the top of the `else` notification-building block) so every `showGameFeedback(false, ...)` call plays the file.
3. **Export `playWrongSound` on `window`**: Add `window.playWrongSound = playWrongSound;` alongside the existing `window.showGameFeedback` / `window.getEncouragingMessage` exports, so `the_metric_quest` and Rocket Math can play the file outside the modal flow (timeout/stage-fail/session-fail sites that do not route through `showGameFeedback`).

**File**: `resources/views/games/mdas_maze.blade.php`

**Specific Changes**:
4. **Remove the synthesized wrong-tone call**: Delete the `soundWrong();` statement at the wrong-answer handler (~line 2644). The `window.showGameFeedback(false, ...)` call that follows (~line 2655) now plays `wrong-answer.mp3`.
5. **Remove the now-unused `soundWrong()` definition** (~lines 1981-1985): with no remaining callers, delete the function to remove dead code. (Design decision — see Notes. Alternative: leave it as dead code.)

**File**: `resources/views/games/the_metric_quest.blade.php`

**Specific Changes**:
6. **Remove the synthesized wrong-tone call at the per-answer wrong handler** (~line 3108): delete `soundWrong();`. The following `window.showGameFeedback(false, q.correct, ...)` (~line 3123) now plays `wrong-answer.mp3`.
7. **Replace the synthesized wrong-tone call at the timeout handler** (~line 3044, `onTimeOut`): change `soundWrong();` to `window.playWrongSound();`. A timeout is a per-question wrong outcome (the code resets streak and marks the feedback box `wrong`), so it SHALL play `wrong-answer.mp3` and no synthesized tone.
8. **Replace the synthesized wrong-tone call at the stage-fail handler** (~line 3267): change `soundWrong();` to `window.playWrongSound();`. This removes the synthesized tone and keeps a consistent failure cue using the shared file. (Design decision — see Notes. Alternative: remove the call entirely so a stage-fail is silent; acceptable because a stage-fail is not a per-answer wrong submission and Requirement 2.2 does not mandate a sound there. The recommended replacement preserves a failure cue and eliminates the synthesized tone.)
9. **Remove the now-unused `soundWrong()` definition** (~lines 2315-2319): with no remaining callers, delete the function. (Design decision — see Notes. Alternative: leave it as dead code.)
10. **Keep `soundCorrect()` and all other synthesized sound functions** (`soundWin`, `soundHint`, `soundClick`, `playTone`) untouched, and keep all `soundCorrect()`/`soundClick()` call sites intact.

**File**: `resources/views/games/rocket_math.blade.php`

**Specific Changes**:
11. **Add `window.playWrongSound()` in the session-fail branch of `showSessionResult`**: In the `else` block of `showSessionResult(won)` (~line 1771, where the "Time's Up!" modal content is built), add `window.playWrongSound();` as the first statement so the failure cue plays when the session-fail modal is shown. Rocket Math already loads `game-feedback.js` (~line 1412), which exports `window.playWrongSound` after the fix.
    - Place the call at the top of the `else` branch (before the modal DOM/text assignments) so it fires as soon as the session-fail outcome is determined.
    - Do NOT add any sound to the `if (won)` branch — the session-win path keeps its existing behavior (confetti, no explicit sound change).
    - No `soundWrong()` or `playTone()` function exists in Rocket Math, so there is no synthesized tone to remove.

**Notes (Design Decisions)**:
- **Exposing `playWrongSound` on `window`**: `playCorrectSound()` is internal-only because no correct-answer event occurs outside `showGameFeedback`. The wrong path is asymmetric — `the_metric_quest` has wrong-outcome sites (timeout, stage-fail) outside the modal flow, and Rocket Math's session-fail does not route through `showGameFeedback` at all — so exposing `playWrongSound` is justified and avoids silent regressions at those sites. If the team prefers to keep `playWrongSound` internal (strictly mirroring `playCorrectSound`), then steps 7, 8, and 11 become "remove `soundWrong()`/play nothing" with no replacement, leaving timeout, stage-fail, and Rocket Math session-fail silent; this is requirements-non-compliant for Rocket Math (Requirement 2.3 mandates a sound on session-fail), so the recommended approach keeps the `window.playWrongSound()` calls.
- **Removing `soundWrong()` definitions**: Recommended to delete them (no remaining callers) to prevent accidental re-introduction of the synthesized tone. Leaving them as dead code is an acceptable, lower-risk alternative.
- **Rocket Math session-fail placement**: Placing `window.playWrongSound()` at the top of the `else` branch guarantees the sound fires before the modal's `setTimeout` show delay (`won ? 2200 : 400`), so the cue is immediate on failure. The modal display timing and text are untouched, satisfying Requirement 3.7.

## Testing Strategy

### Validation Approach

The testing strategy follows a two-phase approach: first, surface counterexamples that demonstrate the bug on unfixed code, then verify the fix plays the correct file and preserves all existing behavior.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexamples that demonstrate the bug BEFORE implementing the fix. Confirm or refute the root cause analysis. If we refute, we will need to re-hypothesize.

**Test Plan**: Intercept `Audio` construction/`play()` and `playTone()` (or the games' `soundWrong`), then trigger wrong-answer events in each game and assert what audio is produced. For Rocket Math, drive a session to expiry with a sub-threshold score and assert no audio plays. Run these tests on the UNFIXED code to observe failures.

**Test Cases**:
1. **Silent-game wrong answer** (factor_pond / geometry_grid / math_card_match / number_wars / time_quest): trigger a wrong answer; assert NO audio plays (will fail the desired behavior on unfixed code).
2. **mdas_maze wrong answer**: trigger a wrong challenge; assert `soundWrong()`/`playTone()` sawtooth fires and `wrong-answer.mp3` does NOT (counterexample: synthesized instead of file).
3. **the_metric_quest wrong answer**: trigger a wrong conversion; assert synthesized tone fires and `wrong-answer.mp3` does NOT.
4. **the_metric_quest timeout**: let a question time out; assert synthesized tone fires and no `wrong-answer.mp3` (edge counterexample).
5. **rocket_math session-fail**: drive a session to timer expiry with score < WIN_SCORE (e.g., 5 correct); assert `showSessionResult(false)` runs and NO audio plays (counterexample: silent session-fail).

**Expected Counterexamples**:
- On unfixed code, wrong answers play either silence or a synthesized sawtooth tone — never `wrong-answer.mp3`.
- On unfixed code, a Rocket Math session-fail shows the "Time's Up!" modal but plays no audio.
- Possible causes: missing wrong-sound call in the shared `else` branch; per-game synthesized substitute; un-wired session-fail path in Rocket Math.

### Fix Checking

**Goal**: Verify that for all inputs where the bug condition holds, the fixed function produces the expected behavior.

**Pseudocode:**
```
FOR ALL event WHERE isBugCondition(event) DO
  result := fixedWrongAnswerHandler(event)
  IF event.type = 'answer_submission' AND event.game IN ['factor_pond','geometry_grid','math_card_match','number_wars','time_quest'] THEN
    ASSERT playsWrongAnswerMp3(result)
  ELSE IF event.game IN ['mdas_maze','the_metric_quest'] THEN
    ASSERT playsWrongAnswerMp3(result)
    ASSERT NOT playsSynthesizedWrongTone(result)
  ELSE IF event.type = 'session_fail' AND event.game = 'rocket_math' THEN
    ASSERT playsWrongAnswerMp3(result)
  END IF
END FOR
```

### Preservation Checking

**Goal**: Verify that for all inputs where the bug condition does NOT hold, the fixed function produces the same result as the original function.

**Pseudocode:**
```
FOR ALL event WHERE NOT isBugCondition(event) DO
  ASSERT originalBehavior(event) = fixedBehavior(event)
END FOR
```

**Testing Approach**: Property-based testing is recommended for preservation checking because:
- It generates many test cases automatically across the input domain
- It catches edge cases that manual unit tests might miss
- It provides strong guarantees that behavior is unchanged for all non-buggy inputs

**Test Plan**: Observe behavior on UNFIXED code for correct answers, Rocket Math session wins, session-fail modal visuals, background music, visual feedback, and scoring; then write property-based tests capturing that behavior and re-run on fixed code.

**Test Cases**:
1. **Correct-answer sound preservation**: observe `correct-answer.mp3` + confetti on correct answers (unfixed), then verify identical after fix (incl. `soundCorrect()` in the two games).
2. **Rocket Math session-win preservation**: observe the "Level Complete!" modal, confetti, and success feedback on a session win (score ≥ WIN_SCORE, unfixed), then verify identical after fix — no new sound is added to the win path.
3. **Rocket Math session-fail modal preservation**: observe the "Time's Up!" modal text/emoji/buttons on a session fail (unfixed), then verify byte-for-byte identical after fix — only an audio cue is added.
4. **Visual feedback preservation**: observe wrong-answer notification DOM/classes/text (unfixed), then verify byte-for-byte identical after fix.
5. **Background music preservation**: observe music plays (unfixed), then verify `game-music-player.js` untouched and music still plays after fix.
6. **Scoring preservation**: observe score/lives/streak/progression on wrong and correct answers and on Rocket Math session outcomes (unfixed), then verify identical after fix.

### Unit Tests

- `game-feedback.js`: assert `playWrongSound()` creates/uses a cached `Audio` with `src` ending in `wrong-answer.mp3`, sets volume `0.5`, resets `currentTime` to 0, and calls `play()`; assert errors are swallowed.
- `showGameFeedback(false, ...)`: assert `playWrongSound()` is invoked (mock it) and the wrong notification is built with the expected classes/text.
- `showGameFeedback(true, ...)`: assert `playCorrectSound()` is still invoked and `playWrongSound()` is NOT.
- `mdas_maze` / `the_metric_quest` wrong handlers: assert `soundWrong` is NOT called and `window.playWrongSound` (or `showGameFeedback(false)`) IS.
- `the_metric_quest` timeout (`onTimeOut`) and stage-fail: assert `window.playWrongSound()` is called and `soundWrong()`/sawtooth `playTone` is not.
- `rocket_math` `showSessionResult(false)`: assert `window.playWrongSound()` is called; assert the "Time's Up!" modal text/emoji/buttons are unchanged.
- `rocket_math` `showSessionResult(true)`: assert `window.playWrongSound()` is NOT called and the win path (confetti, "Level Complete!") is unchanged.

### Property-Based Tests

- Generate random wrong-answer events across all in-scope question-style games and assert `wrong-answer.mp3` plays each time (Property 1).
- Generate random wrong-answer events in `mdas_maze`/`the_metric_quest` and assert no synthesized sawtooth tone is produced and only `wrong-answer.mp3` plays (Property 2).
- Generate random Rocket Math sessions that end in failure (score < WIN_SCORE at timer expiry) and assert `wrong-answer.mp3` plays on the session-fail (Property 3).
- Generate random correct-answer events across all games and assert `correct-answer.mp3` (and `soundCorrect()` in the two games) still play and confetti still appears — identical to original (Property 4).
- Generate random Rocket Math sessions that end in a win (score ≥ WIN_SCORE) and assert the success feedback and sound are unchanged; generate session-fails and assert the modal visuals are unchanged (Property 5).
- Generate random non-answer interactions (music toggles, UI clicks, scoring updates) and assert outputs match the original function exactly (Property 6).

### Integration Tests

- Full wrong-answer flow in each question-style game (factor_pond, geometry_grid, math_card_match, number_wars, time_quest): submit a wrong answer and assert `wrong-answer.mp3` plays and the feedback notification appears unchanged.
- Full wrong-answer flow in `mdas_maze` and `the_metric_quest`: submit a wrong answer and assert only `wrong-answer.mp3` plays (no synthesized tone) while visual feedback and scoring behave as before.
- `the_metric_quest` timeout and stage-fail flows: assert `wrong-answer.mp3` plays and no synthesized tone plays.
- Full Rocket Math session-fail flow: let the timer expire with a sub-threshold score and assert `wrong-answer.mp3` plays and the "Time's Up!" modal appears with unchanged text/buttons.
- Full Rocket Math session-win flow: reach the win threshold before expiry and assert the "Level Complete!" modal and confetti appear unchanged with no new sound added.
- Correct-answer flow in `mdas_maze` and `the_metric_quest`: assert the synthesized correct tone and `correct-answer.mp3` both still play together, unchanged.
- Background music: assert music continues during wrong and correct answers and during Rocket Math session outcomes after the fix.
