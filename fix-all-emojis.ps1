# Comprehensive emoji fix script - replaces ALL corrupted patterns
$ErrorActionPreference = 'Stop'

$gameFiles = @(
    'resources\views\games\math_card_match.blade.php',
    'resources\views\games\number_wars.blade.php',
    'resources\views\games\rocket_math.blade.php',
    'resources\views\games\factor_pond.blade.php',
    'resources\views\games\geometry_grid.blade.php',
    'resources\views\games\mdas_maze.blade.php',
    'resources\views\games\time_quest.blade.php',
    'resources\views\games\the_metric_quest.blade.php'
)

# Define ALL replacements - order matters!
$replacements = @(
    # Common button text
    @(' ? ', ' ▶️ '),
    @('>?<', '>▶️<'),
    @(' ? ', ' ◀️ '),
    
    # Multiply/Divide symbols
    @(' � ', ' × '),
    @('(�)', '(×)'),
    @(' � ', ' ÷ '),
    @('(�)', '(÷)'),
    
    # Degree symbol
    @('180�', '180°'),
    @('360�', '360°'),
    
    # Bullet points
    @(' � ', ' • '),
    @('>�<', '>•<'),
    
    # Question marks that should be emojis (context-based)
    @(': ???', ': ❓❓❓'),
    @('???', '⭐⭐⭐'),
    @('??', '⭐⭐'),
    @(' ? ', ' ⭐ '),
    
    # Common single ? patterns
    @('? correct', '✓ correct'),
    @('? Correct', '✓ Correct'),
    @('? Back', '◀️ Back'),
    @('? Next', '▶️ Next'),
    @('? Resume', '▶️ Resume'),
    @('? Start', '▶️ Start'),
    @('? Play', '▶️ Play'),
    @('? Try', '▶️ Try'),
    @('? Launch', '🚀 Launch'),
    @('? Return', '◀️ Return')
)

Write-Host "`n=== BULK EMOJI FIX SCRIPT ===" -ForegroundColor Cyan
Write-Host "Processing $($gameFiles.Count) game files...`n" -ForegroundColor Yellow

$totalChanges = 0

foreach ($file in $gameFiles) {
    if (Test-Path $file) {
        $gameName = [System.IO.Path]::GetFileNameWithoutExtension($file)
        Write-Host "Processing: $gameName..." -ForegroundColor White
        
        $content = Get-Content $file -Raw -Encoding UTF8
        $originalContent = $content
        $fileChanges = 0
        
        foreach ($pair in $replacements) {
            $pattern = $pair[0]
            $replacement = $pair[1]
            $beforeCount = ($content.Split($pattern, [StringSplitOptions]::None)).Count - 1
            if ($beforeCount -gt 0) {
                $content = $content.Replace($pattern, $replacement)
                $afterCount = ($content.Split($pattern, [StringSplitOptions]::None)).Count - 1
                $changed = $beforeCount - $afterCount
                if ($changed -gt 0) {
                    $fileChanges += $changed
                }
            }
        }
        
        if ($content -ne $originalContent) {
            Set-Content -Path $file -Value $content -Encoding UTF8 -NoNewline
            Write-Host "  ✓ Fixed $fileChanges patterns" -ForegroundColor Green
            $totalChanges += $fileChanges
        } else {
            Write-Host "  - No changes needed" -ForegroundColor Gray
        }
    } else {
        Write-Host "  ✗ File not found: $file" -ForegroundColor Red
    }
}

Write-Host "`n=== SUMMARY ===" -ForegroundColor Cyan
Write-Host "Total patterns fixed: $totalChanges" -ForegroundColor Green
Write-Host "`nRunning: php artisan view:clear" -ForegroundColor Yellow
& php artisan view:clear
Write-Host "`n✓ Complete! Please refresh your browser (Ctrl+F5)" -ForegroundColor Green
