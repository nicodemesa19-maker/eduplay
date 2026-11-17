# Bugfix Requirements Document

## Introduction

The EduPlay platform includes eight educational math games. A dedicated wrong-answer sound file (`wrong-answer.mp3`) exists in the public sounds directory but is never played when a student answers incorrectly or fails a game session. Five of the games (factor_pond, geometry_grid, math_card_match, number_wars, and time_quest) play no audio at all on a wrong answer; two games (mdas_maze and the_metric_quest) play a synthesized audio tone instead of the provided sound file; and Rocket Math plays no audio when a student fails a session by not launching enough rockets before the timer expires. As a result, students do not hear a consistent, intended wrong-answer cue. This bugfix makes `wrong-answer.mp3` the single wrong-answer sound across all eight games, so a student always hears the same feedback whenever an answer is wrong or a session is failed.

## Bug Analysis

### Current Behavior (Defect)

When a student gives a wrong answer or fails a game session, the `wrong-answer.mp3` sound file is not played. The defect takes three forms depending on the game.

1.1 WHEN a student submits a wrong answer in factor_pond, geometry_grid, math_card_match, number_wars, or time_quest THEN the system plays no audio feedback for the wrong answer.

1.2 WHEN a student submits a wrong answer in mdas_maze or the_metric_quest THEN the system plays a synthesized audio tone instead of the `wrong-answer.mp3` sound file.

1.3 WHEN a student fails a Rocket Math session by not launching enough rockets before the session timer expires THEN the system shows the session-fail result without playing any audio feedback.

### Expected Behavior (Correct)

2.1 WHEN a student submits a wrong answer in factor_pond, geometry_grid, math_card_match, number_wars, or time_quest THEN the system SHALL play the `wrong-answer.mp3` sound file.

2.2 WHEN a student submits a wrong answer in mdas_maze or the_metric_quest THEN the system SHALL play only the `wrong-answer.mp3` sound file and SHALL NOT play the synthesized wrong-answer tone.

2.3 WHEN a student fails a Rocket Math session by not launching enough rockets before the session timer expires THEN the system SHALL play the `wrong-answer.mp3` sound file.

### Unchanged Behavior (Regression Prevention)

3.1 WHEN a student submits a correct answer THEN the system SHALL CONTINUE TO play the `correct-answer.mp3` sound and show the correct-answer visual feedback (including confetti).

3.2 WHEN background game music is active THEN the system SHALL CONTINUE TO play background music unaffected by the wrong-answer sound changes.

3.3 WHEN a student submits a wrong answer THEN the system SHALL CONTINUE TO show the existing wrong-answer visual feedback (encouraging message, correct answer, explanation, and Continue button) and existing visual effects without change.

3.4 WHEN a student submits a correct or wrong answer THEN the system SHALL CONTINUE TO update score, lives, streaks, and game progression exactly as before.

3.5 WHEN a student submits a correct answer in mdas_maze or the_metric_quest THEN the system SHALL CONTINUE TO play its existing correct-answer sound (the synthesized correct tone together with `correct-answer.mp3`) unchanged.

3.6 WHEN a student wins a Rocket Math session by launching enough rockets THEN the system SHALL CONTINUE TO show the existing session success feedback and play the existing success sound unchanged.

3.7 WHEN a student fails a Rocket Math session THEN the system SHALL CONTINUE TO show the existing "Time's Up!" session-fail modal and its progress message unchanged.
