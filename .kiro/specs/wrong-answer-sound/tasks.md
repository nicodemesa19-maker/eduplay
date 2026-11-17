# Implementation Plan

## Overview

This task list implements the fix for the wrong-answer sound bug in EduPlay games. The bug condition methodology is followed: first write a bug condition exploration test (Property 1) that must fail on unfixed code, then write preservation property tests (Property 2) that must pass on unfixed code, then implement the fix across four files, and finally verify both test suites pass after the fix.

The fix modifies four files:
- `public/js/game-feedback.js` — add `playWrongSound()` helper, call it in the wrong-answer branch, export on `window`
- `resources/views/games/mdas_maze.blade.php` — remove synthesized `soundWrong()` call and definition
- `resources/views/games/the_metric_quest.blade.php` — remove/replace three `soundWrong()` call sites and remove the definition
- `resources/views/games/rocket_math.blade.php` — add `window.playWrongSound()` in the session-fail (`else`) branch of `showSessionResult(won)`

All eight games are in scope:
- factor_pond, geometry_grid, math_card_match, number_wars, time_quest (5 silent → fixed via the `game-feedback.js` else branch)
- mdas_maze, the_metric_quest (2 synthesized → remove/replace `soundWrong` + `game-feedback.js` fix)
- rocket_math (1 session-fail silent → add `window.playWrongSound()` in the `showSessionResult` else branch)

## Task Dependency Graph

```json
{
  "waves": [
    {
      "wave": 1,
      "tasks": ["1", "2"]
    },
    {
      "wave": 2,
      "tasks": ["3"]
    },
    {
      "wave": 3,
      "tasks": ["4"]
    }
  ],
  "dependencies": {
    "3": ["1", "2"],
    "4": ["3"]
  }
}
```

## Tasks

- [ ] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Wrong answer and Rocket Math session-fail play wrong-answer.mp3 with no synthesized tone
  - **CRITICAL**: This test MUST FAIL on unfixed code - failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior - it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate the bug exists
  - **Scoped PBT Approach**: Scope the property to concrete failing cases across all eight in-scope games to ensure reproducibility
  - Intercept `Audio` construction/`play()` and the games' `soundWrong()`/`playTone()` calls, then trigger wrong-answer events and assert what audio is produced
  - Test case — silent games (factor_pond, geometry_grid, math_card_match, number_wars, time_quest): trigger a wrong answer via `showGameFeedback(false, ...)`, assert that NO `wrong-answer.mp3` plays on unfixed code (from Bug Condition in design)
  - Test case — synthesized games (mdas_maze): trigger a wrong challenge, assert `soundWrong()`/sawtooth `playTone()` fires and `wrong-answer.mp3` does NOT (counterexample: synthesized instead of file)
  - Test case — synthesized games (the_metric_quest): trigger a wrong conversion, assert synthesized tone fires and `wrong-answer.mp3` does NOT
  - Test case — the_metric_quest timeout (`onTimeOut`): let a question time out, assert synthesized tone fires and no `wrong-answer.mp3` (edge counterexample)
  - Test case — rocket_math session-fail: drive a session to timer expiry with `GameState.score < WIN_SCORE` (e.g., 5 correct), assert `showSessionResult(false)` runs and NO audio plays (counterexample: silent session-fail)
  - The test assertions should match Expected Behavior: for all inputs where `isBugCondition(event)` is true, the fixed code SHALL play `wrong-answer.mp3`; for answer submissions in the seven question-style games, for mdas_maze/the_metric_quest it SHALL play ONLY `wrong-answer.mp3` and NOT the synthesized wrong tone, and for a Rocket Math session-fail (`event.type = 'session_fail'`, `event.game = 'rocket_math'`, `event.score < WIN_SCORE`) it SHALL play `wrong-answer.mp3` in the `showSessionResult` else branch
  - Run test on UNFIXED code
  - **EXPECTED OUTCOME**: Test FAILS (this is correct - it proves the bug exists; wrong answers play silence or a synthesized sawtooth tone, never `wrong-answer.mp3`; Rocket Math session-fails play no audio)
  - Document counterexamples found to understand root cause (e.g., "factor_pond wrong answer: no audio at all; mdas_maze wrong answer: synthesized tone, no .mp3; rocket_math session-fail: no audio at all")
  - Mark task complete when test is written, run, and failure is documented
  - _Requirements: 2.1, 2.2, 2.3_

- [ ] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Correct-answer, session-win, and non-answer behavior unchanged
  - **IMPORTANT**: Follow observation-first methodology
  - Observe behavior on UNFIXED code for non-buggy inputs (cases where `isBugCondition` returns false)
  - Write property-based tests capturing observed behavior patterns from Preservation Requirements
  - Property-based testing generates many test cases for stronger guarantees
  - Test — correct-answer sound preservation: observe `correct-answer.mp3` + confetti on correct answers via `showGameFeedback(true, ...)` on unfixed code; assert `playCorrectSound()` is invoked and `playWrongSound()` is NOT; verify identical after fix, including `soundCorrect()` synthesized tone in mdas_maze and the_metric_quest (Req 3.1, 3.5)
  - Test — visual feedback preservation: observe wrong-answer notification DOM/classes/text on unfixed code; assert byte-for-byte identical after fix — only an audio cue is added, no visual change (Req 3.3)
  - Test — background music preservation: observe music plays via `game-music-player.js` on unfixed code; assert music player is untouched and music still plays after fix (Req 3.2)
  - Test — scoring preservation: observe score/lives/streaks/progression updates on wrong and correct answers on unfixed code; assert identical after fix — fix modifies only sound calls, not game-state logic (Req 3.4)
  - Test — synthesized correct/win/hint/click tones: observe `soundCorrect()`, `soundWin()`, `soundHint()`, `soundClick()`, and `playTone()` for non-wrong uses in mdas_maze and the_metric_quest on unfixed code; assert all untouched after fix (Req 3.5)
  - Test — Rocket Math session-win preservation: observe the "Level Complete!" modal, confetti, and success feedback on a session win (`GameState.score >= WIN_SCORE`) on unfixed code; assert identical after fix — no new sound is added to the `if (won)` branch of `showSessionResult` (Req 3.6)
  - Test — Rocket Math session-fail modal preservation: observe the "Time's Up!" modal text, emoji, and buttons on a session fail on unfixed code; assert byte-for-byte identical after fix — only an audio cue (`wrong-answer.mp3`) is added, the modal DOM/text/timing is unchanged (Req 3.7)
  - Run tests on UNFIXED code
  - **EXPECTED OUTCOME**: Tests PASS (this confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

- [ ] 3. Fix for wrong-answer sound not playing (wrong-answer.mp3 never used; synthesized tone or silence instead; Rocket Math session-fail silent)

  - [ ] 3.1 Implement the fix
    - **File: `public/js/game-feedback.js`**
      - Add `playWrongSound()` helper mirroring the existing `playCorrectSound()`: declare module-level cached `let wrongSoundAudio = null;`, create `new Audio('/sounds/wrong-answer.mp3')` once (cached), set `volume = 0.5`, reset `currentTime = 0` on each call, call `.play()` with a `.catch()` that logs a harmless message
      - Call `playWrongSound()` in the `else` (wrong-answer) branch of `showGameFeedback`, symmetric to the existing `if (isCorrect) { playCorrectSound(); }`
      - Export `playWrongSound` on `window` alongside the existing `window.showGameFeedback` and `window.getEncouragingMessage` exports
    - **File: `resources/views/games/mdas_maze.blade.php`**
      - Remove the `soundWrong();` call at the wrong-answer handler (~line 2644); the following `window.showGameFeedback(false, ...)` call now plays `wrong-answer.mp3`
      - Remove the now-unused `soundWrong()` function definition (~lines 1981-1985) to remove dead code
      - Keep `soundCorrect()`, `soundWin()`, `soundHint()`, `soundClick()`, and `playTone()` untouched
    - **File: `resources/views/games/the_metric_quest.blade.php`**
      - Remove the `soundWrong();` call at the per-answer wrong handler (~line 3108); the following `window.showGameFeedback(false, q.correct, ...)` now plays `wrong-answer.mp3`
      - Replace `soundWrong();` with `window.playWrongSound();` at the timeout handler `onTimeOut` (~line 3044) — a timeout is a per-question wrong outcome that SHALL play `wrong-answer.mp3` and no synthesized tone
      - Replace `soundWrong();` with `window.playWrongSound();` at the stage-fail handler (~line 3267) — removes the synthesized tone and keeps a consistent failure cue using the shared file
      - Remove the now-unused `soundWrong()` function definition (~lines 2315-2319) to remove dead code
      - Keep `soundCorrect()`, `soundWin()`, `soundHint()`, `soundClick()`, and `playTone()` untouched, and keep all `soundCorrect()`/`soundClick()` call sites intact
    - **File: `resources/views/games/rocket_math.blade.php`**
      - Add `window.playWrongSound();` as the first statement in the `else` (session-fail) branch of `showSessionResult(won)` (~line 1771, where the "Time's Up!" modal content is built), before the `modalEmoji`/`modalTitleText` assignments, so the failure cue fires as soon as the session-fail outcome is determined
      - Do NOT add any sound to the `if (won)` branch — the session-win path keeps its existing behavior (confetti, no explicit sound change)
      - Rocket Math already loads `game-feedback.js` (~line 1411), which exports `window.playWrongSound` after the fix
      - No `soundWrong()` or `playTone()` function exists in rocket_math, so there is no synthesized tone to remove
    - _Bug_Condition: isBugCondition(event) where (event.type = 'answer_submission' AND event.isCorrect = FALSE AND event.game IN ['factor_pond','geometry_grid','math_card_match','number_wars','time_quest','mdas_maze','the_metric_quest']) OR (event.type = 'session_fail' AND event.game = 'rocket_math' AND event.score < WIN_SCORE)_
    - _Expected_Behavior: For all inputs where isBugCondition holds, fixed code plays wrong-answer.mp3; for mdas_maze/the_metric_quest answer submissions, plays ONLY wrong-answer.mp3 and NOT the synthesized wrong tone; for rocket_math session-fail, plays wrong-answer.mp3 in the showSessionResult else branch_
    - _Preservation: Correct-answer sounds (correct-answer.mp3 + soundCorrect()), background music, wrong-answer visual feedback, score/lives/streaks/progression, synthesized correct/win/hint/click tones, Rocket Math session-win feedback/sound, and Rocket Math session-fail modal text/emoji/buttons all unchanged_
    - _Requirements: 2.1, 2.2, 2.3, 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

  - [ ] 3.2 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Wrong answer and Rocket Math session-fail play wrong-answer.mp3 with no synthesized tone
    - **IMPORTANT**: Re-run the SAME test from task 1 - do NOT write a new test
    - The test from task 1 encodes the expected behavior
    - When this test passes, it confirms the expected behavior is satisfied
    - Run bug condition exploration test from step 1
    - **EXPECTED OUTCOME**: Test PASSES (confirms bug is fixed — wrong-answer.mp3 plays for all in-scope games; no synthesized wrong tone in mdas_maze/the_metric_quest; wrong-answer.mp3 plays on Rocket Math session-fail)
    - _Requirements: 2.1, 2.2, 2.3_

  - [ ] 3.3 Verify preservation tests still pass
    - **Property 2: Preservation** - Correct-answer, session-win, and non-answer behavior unchanged
    - **IMPORTANT**: Re-run the SAME tests from task 2 - do NOT write new tests
    - Run preservation property tests from step 2
    - **EXPECTED OUTCOME**: Tests PASS (confirms no regressions — correct-answer sounds, background music, visual feedback, scoring, synthesized non-wrong tones, Rocket Math session-win feedback, and Rocket Math session-fail modal visuals all unchanged)
    - Confirm all tests still pass after fix (no regressions)
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

- [ ] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- **Why `playWrongSound` is exported on `window`**: `playCorrectSound()` is internal-only because no correct-answer event occurs outside `showGameFeedback`. The wrong path is asymmetric — `the_metric_quest` has wrong-outcome sites (timeout, stage-fail) outside the modal flow, and Rocket Math's session-fail does not route through `showGameFeedback` at all — so exposing `playWrongSound` avoids silent regressions at those sites and is required for Requirement 2.3 (Rocket Math session-fail must play a sound).
- **Rocket Math session-fail placement**: Placing `window.playWrongSound()` as the first statement in the `else` branch of `showSessionResult(won)` guarantees the sound fires before the modal's `setTimeout` show delay (`won ? 2200 : 400`), so the cue is immediate on failure. The modal display timing and text are untouched, satisfying Requirements 3.6 and 3.7.
- **Rocket Math has no wrong-answer submission event**: Its only failing outcome is the session-fail (timer expires with `score < WIN_SCORE`). Its only `showGameFeedback` call is for success (`handleCanisterClick` → `showGameFeedback(true, ...)`), so the shared `else`-branch fix does not reach the session-fail path — a direct `window.playWrongSound()` call is required in `showSessionResult`.
- **Removing `soundWrong()` definitions**: Recommended to delete them (no remaining callers after the fix) to prevent accidental re-introduction of the synthesized tone. Leaving them as dead code is an acceptable, lower-risk alternative.
- **Line numbers are approximate**: The design document references specific line numbers (~2644, ~1981, ~3108, ~3044, ~3267, ~2315, ~1771, ~1411) based on the code at design time. Verify exact locations during implementation.
