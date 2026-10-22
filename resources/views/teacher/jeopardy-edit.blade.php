@extends('layouts.app')
@section('content')
<div class="min-vh-100 jeopardy-edit-shell py-4 py-md-5">
    <div class="container-fluid px-3 px-md-4" style="max-width: 1400px;">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden jeopardy-edit-card">
            
            {{-- PAGE HEADER --}}
            <div class="card-header border-0 py-4 px-4 px-md-5 jeopardy-edit-header">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;" aria-label="Go back">
                            <i class="bi bi-arrow-left fs-5"></i>
                        </a>
                        <div>
                            <p class="text-uppercase small fw-bold mb-1 text-light-50" style="letter-spacing: 1px;">Classroom Discussion Aid</p>
                            <h1 class="h2 mb-1 text-white fw-black" style="font-family: 'Space Grotesk', sans-serif;">
                                🛠️ Jeopardy Customization Board
                            </h1>
                            <p class="text-light-50 mb-0 fs-6">Personalize category names and cell questions, then launch play mode for the classroom.</p>
                        </div>
                    </div>
                    
                    {{-- Launch Play Mode Trigger --}}
                    <div>
                        <a href="{{ route('teacher.jeopardy.play') }}" class="btn btn-success rounded-4 px-4 py-2.5 fw-bold fs-5 shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-play-circle-fill"></i>
                            <span>Launch Play Mode</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- MAIN LAYOUT --}}
            <div class="card-body p-4 p-md-5">
                <div class="row g-4 align-items-stretch">
                    
                    {{-- Editable Game Board --}}
                    <div class="col-lg-8" id="jeopardy-board-col">
                        <div class="card h-100 border-0 rounded-4 shadow-sm jeopardy-board-wrapper">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                                    <h2 class="h4 mb-0 fw-black text-white" style="font-family: 'Space Grotesk', sans-serif;">Board Editor Preview</h2>
                                    <span class="badge bg-info text-dark fw-bold rounded-pill px-3 py-1.5 fs-6 uppercase">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit Mode
                                    </span>
                                </div>

                                {{-- CSS Grid Jeopardy Board Container with Scroll Wrapper --}}
                                <div class="jeopardy-grid-scroll-wrapper">
                                    <div id="jeopardy-grid-container">
                                        {{-- Dynamically populated via JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Control Sidebar --}}
                    <div class="col-lg-4" id="jeopardy-sidebar-col">
                        <div class="card h-100 border-0 rounded-4 shadow-sm control-sidebar">
                            <div class="card-body p-4 d-flex flex-column justify-content-between gap-4">
                                
                                {{-- Board Sizing Section --}}
                                <div class="instruction-section">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="round-icon-bg bg-warning-subtle text-warning"><i class="bi bi-gear-fill"></i></div>
                                        <h3 class="h5 mb-0 fw-bold">Board Layout Size</h3>
                                    </div>
                                    <p class="text-light-50 mb-3 fs-6">Choose how many categories and questions you need for your math lesson plan.</p>
                                    
                                    <div class="p-3 mb-4 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                                        <div class="mb-3">
                                            <label class="form-label text-light-50 small fw-bold">Number of Categories (Columns)</label>
                                            <select id="config-categories" class="form-select bg-dark text-white border-secondary rounded-3" onchange="adjustGridSize()">
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Category' : 'Categories' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label text-light-50 small fw-bold">Rows (Clues) per Category</label>
                                            <select id="config-rows" class="form-select bg-dark text-white border-secondary rounded-3" onchange="adjustGridSize()">
                                                @for($r = 1; $r <= 10; $r++)
                                                    <option value="{{ $r }}">{{ $r }} {{ $r === 1 ? 'Row' : 'Rows' }} ($100 - ${{ $r * 100 }})</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="round-icon-bg bg-primary-subtle text-primary"><i class="bi bi-info-circle-fill"></i></div>
                                        <h3 class="h5 mb-0 fw-bold">Personalization Instructions</h3>
                                    </div>
                                    
                                    <div class="list-group list-group-flush rounded-3 overflow-hidden bg-transparent mb-4">
                                        <div class="list-group-item bg-transparent text-white border-light-subtle py-2 px-0 d-flex gap-3 align-items-center">
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            <span>Click a Category Header to rename it.</span>
                                        </div>
                                        <div class="list-group-item bg-transparent text-white border-light-subtle py-2 px-0 d-flex gap-3 align-items-center">
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                            <span>Click any point value button to edit its math question and answer.</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-2">
                                        <button type="button" class="btn btn-danger w-100 rounded-3 py-2.5 fw-bold text-white shadow-sm" onclick="resetJeopardyToDefaults()">
                                            <i class="bi bi-trash3-fill me-1"></i> Load Math Defaults
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-indigo-subtle border-0 rounded-4 p-3 d-flex align-items-center gap-3">
                                    <i class="bi bi-patch-check-fill fs-3 text-indigo"></i>
                                    <div>
                                        <h4 class="h6 mb-1 text-white fw-bold">Autosaved</h4>
                                        <p class="mb-0 text-light-50 small">All modifications are preserved automatically in this browser.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Clue Custom Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg jeopardy-modal-content">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <span class="badge bg-info text-dark fw-bold rounded-pill px-3 py-1.5 uppercase" id="edit-modal-category">Edit Clue</span>
                    <h5 class="modal-title fw-black text-white h4 mt-2" id="edit-modal-title">Customize Question</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 px-4">
                <form id="edit-clue-form" onsubmit="saveJeopardyClue(event)">
                    <div class="mb-4">
                        <label class="form-label text-light-50 fw-semibold fs-5">Clue / Question Text</label>
                        <textarea id="edit-question" class="form-control bg-dark border-secondary text-white rounded-3 p-3 fs-5" rows="4" required placeholder="Enter math problem..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-light-50 fw-semibold fs-5">Clue Image (Optional)</label>
                        <input type="file" id="edit-question-image-file" class="form-control bg-dark border-secondary text-white rounded-3 p-2 fs-5" accept="image/*" onchange="previewClueImage(event)">
                        <div id="edit-image-preview-wrapper" class="mt-3 d-none position-relative border border-secondary p-1 rounded-3 d-inline-block" style="max-width: 250px; background: rgba(0,0,0,0.2);">
                            <img id="edit-image-preview" src="" class="img-fluid rounded-3" style="max-height: 150px; object-fit: contain;">
                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-1" onclick="removeClueImagePreview()" title="Remove Image">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-light-50 fw-semibold fs-5">Correct Answer</label>
                        <input type="text" id="edit-answer" class="form-control bg-dark border-secondary text-white rounded-3 p-3 fs-5" required placeholder="Enter answer...">
                    </div>
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2 fs-5" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info rounded-3 px-5 py-2 fs-5 fw-bold text-dark">Save Clue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Category Rename Modal --}}
<div class="modal fade" id="categoryEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg jeopardy-modal-content">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-black text-white h4">Rename Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 px-4">
                <form id="edit-category-form" onsubmit="saveJeopardyCategory(event)">
                    <div class="mb-4">
                        <label class="form-label text-light-50 fw-semibold fs-5">Category Name</label>
                        <input type="text" id="edit-category-name" class="form-control bg-dark border-secondary text-white rounded-3 p-3 fs-5" required placeholder="Enter name...">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info rounded-3 px-4 fw-bold text-dark">Rename</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .jeopardy-edit-shell { 
        background: linear-gradient(135deg, #07111f 0%, #101a30 45%, #1d1842 100%); 
        font-size: 1.2rem;
    }
    .jeopardy-edit-card { 
        background: rgba(9, 14, 28, 0.88); 
        border: 1px solid rgba(148, 163, 184, 0.16); 
    }
    .jeopardy-edit-header { 
        background: linear-gradient(135deg, #111827 0%, #1f2937 45%, #312e81 100%); 
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .jeopardy-board-wrapper {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
    .control-sidebar {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #e2e8f0 !important;
    }
    .control-sidebar h3, .control-sidebar h4, .control-sidebar h5 {
        color: #ffffff !important;
    }
    .control-sidebar p, .control-sidebar span {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .control-sidebar .text-light-50 {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    .jeopardy-grid-scroll-wrapper {
        overflow-x: auto;
        width: 100%;
        padding-bottom: 12px;
    }

    /* Grid Styling */
    #jeopardy-grid-container {
        display: grid;
        grid-template-columns: repeat(var(--cols, 5), 1fr);
        gap: 12px;
        width: 100%;
        min-width: calc(var(--cols, 5) * 160px);
    }

    .jeopardy-cell { 
        background: linear-gradient(135deg, #1e1b4b, #0f172a); 
        color: #38bdf8; 
        border: 1px dashed #38bdf8; 
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        transition: all 0.2s ease; 
        min-height: 85px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    .jeopardy-cell:hover {
        background: rgba(56, 189, 248, 0.12);
        border-color: #0ea5e9;
        transform: translateY(-2px);
    }

    .jeopardy-category { 
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.15), #111827);
        border: 1px solid #38bdf8; 
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.2rem;
        font-weight: 800;
        color: #38bdf8 !important;
        text-align: center;
        text-transform: uppercase;
        padding: 16px 8px;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 65px;
        position: relative;
    }
    .jeopardy-category::after {
        content: ' ✎';
        font-size: 0.85rem;
        color: #38bdf8;
    }
    .jeopardy-category:hover {
        background: rgba(56, 189, 248, 0.25);
    }

    .round-icon-bg {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }
    .bg-indigo { background: #4f46e5; }
    .bg-indigo-subtle { background: rgba(79, 70, 229, 0.12); color: #c7d2fe; }
    .text-indigo { color: #a5b4fc; }
    .text-light-50 { color: rgba(255, 255, 255, 0.6) !important; }
    .fw-black { font-weight: 900 !important; }
    .font-grotesk { font-family: 'Space Grotesk', sans-serif; }
    
    .jeopardy-modal-content {
        background: #0f172a;
        border: 1px solid rgba(255,255,255,0.1);
    }
</style>

<script>
    // ── DEFAULT 10x10 JEOPARDY DATA ──
    const defaultJeopardyData = {
        categories: ["Addition", "Subtraction", "Multiplication", "Division", "Algebra", "Geometry", "Fractions", "Decimals", "Word Problems", "Measurement"],
        board: {
            "Addition": {
                "100": { "q": "What is 8 + 7?", "a": "15" },
                "200": { "q": "What is 15 + 18?", "a": "33" },
                "300": { "q": "What is 34 + 27?", "a": "61" },
                "400": { "q": "What is 56 + 39?", "a": "95" },
                "500": { "q": "What is 125 + 87?", "a": "212" },
                "600": { "q": "What is 340 + 290?", "a": "630" },
                "700": { "q": "What is 450 + 550?", "a": "1000" },
                "800": { "q": "What is 789 + 121?", "a": "910" },
                "900": { "q": "What is 1,234 + 5,678?", "a": "6,912" },
                "1000": { "q": "What is 9,999 + 9,999?", "a": "19,998" }
            },
            "Subtraction": {
                "100": { "q": "What is 12 - 5?", "a": "7" },
                "200": { "q": "What is 35 - 17?", "a": "18" },
                "300": { "q": "What is 62 - 28?", "a": "34" },
                "400": { "q": "What is 90 - 43?", "a": "47" },
                "500": { "q": "What is 143 - 76?", "a": "67" },
                "600": { "q": "What is 500 - 185?", "a": "315" },
                "700": { "q": "What is 820 - 460?", "a": "360" },
                "800": { "q": "What is 1000 - 345?", "a": "655" },
                "900": { "q": "What is 4,321 - 1,234?", "a": "3,087" },
                "1000": { "q": "What is 10,000 - 9,999?", "a": "1" }
            },
            "Multiplication": {
                "100": { "q": "What is 6 x 4?", "a": "24" },
                "200": { "q": "What is 8 x 7?", "a": "56" },
                "300": { "q": "What is 12 x 6?", "a": "72" },
                "400": { "q": "What is 14 x 5?", "a": "70" },
                "500": { "q": "What is 15 x 9?", "a": "135" },
                "600": { "q": "What is 20 x 30?", "a": "600" },
                "700": { "q": "What is 25 x 4?", "a": "100" },
                "800": { "q": "What is 12 x 12?", "a": "144" },
                "900": { "q": "What is 11 x 90?", "a": "990" },
                "1000": { "q": "What is 50 x 20?", "a": "1000" }
            },
            "Division": {
                "100": { "q": "What is 16 / 4?", "a": "4" },
                "200": { "q": "What is 42 / 6?", "a": "7" },
                "300": { "q": "What is 72 / 8?", "a": "9" },
                "400": { "q": "What is 96 / 12?", "a": "8" },
                "500": { "q": "What is 108 / 9?", "a": "12" },
                "600": { "q": "What is 150 / 5?", "a": "30" },
                "700": { "q": "What is 240 / 6?", "a": "40" },
                "800": { "q": "What is 360 / 12?", "a": "30" },
                "900": { "q": "What is 720 / 90?", "a": "8" },
                "1000": { "q": "What is 1000 / 250?", "a": "4" }
            },
            "Algebra": {
                "100": { "q": "Find x: x + 5 = 12", "a": "7" },
                "200": { "q": "Find y: 3y = 18", "a": "6" },
                "300": { "q": "Find a: 2a - 4 = 10", "a": "7" },
                "400": { "q": "Find n: n / 4 = 9", "a": "36" },
                "500": { "q": "Find x: 5x + 3 = 28", "a": "5" },
                "600": { "q": "Find x: 2x - 8 = x + 2", "a": "10" },
                "700": { "q": "Find m: 6m / 2 = 18", "a": "6" },
                "800": { "q": "Find k: 4(k - 2) = 24", "a": "8" },
                "900": { "q": "Find x: x^2 = 81 (positive root)", "a": "9" },
                "1000": { "q": "Find z: z/3 + 7 = 15", "a": "24" }
            },
            "Geometry": {
                "100": { "q": "How many sides does a hexagon have?", "a": "6" },
                "200": { "q": "What is the perimeter of a square with a 5cm side?", "a": "20 cm" },
                "300": { "q": "What do you call an angle that is exactly 90 degrees?", "a": "Right angle" },
                "400": { "q": "Find the area of a rectangle with length 8cm and width 6cm.", "a": "48 sq cm" },
                "500": { "q": "What is the sum of the inner angles of a triangle?", "a": "180 degrees" },
                "600": { "q": "How many degrees are in a full circle?", "a": "360" },
                "700": { "q": "Find the area of a right triangle with base 10cm and height 6cm.", "a": "30 sq cm" },
                "800": { "q": "What do you call a triangle with all three sides equal?", "a": "Equilateral" },
                "900": { "q": "How many vertices does a pentagonal prism have?", "a": "10" },
                "1000": { "q": "If radius of circle is 7, find circumference (use pi = 22/7).", "a": "44" }
            },
            "Fractions": {
                "100": { "q": "What is 1/4 + 2/4?", "a": "3/4" },
                "200": { "q": "Simplify the fraction 8/12.", "a": "2/3" },
                "300": { "q": "Which is larger: 2/3 or 3/5?", "a": "2/3" },
                "400": { "q": "What is 1/2 of 50?", "a": "25" },
                "500": { "q": "What is 3/4 - 1/8?", "a": "5/8" },
                "600": { "q": "What is 2/5 of 100?", "a": "40" },
                "700": { "q": "Convert 13/4 into a mixed number.", "a": "3 1/4" },
                "800": { "q": "What is 2/3 x 3/4?", "a": "1/2" },
                "900": { "q": "What is 1/2 divided by 3?", "a": "1/6" },
                "1000": { "q": "What is the sum of 1/2, 1/3, and 1/6?", "a": "1" }
            },
            "Decimals": {
                "100": { "q": "What is 0.5 + 0.3?", "a": "0.8" },
                "200": { "q": "Which is larger: 0.09 or 0.1?", "a": "0.1" },
                "300": { "q": "What is 1.5 x 2?", "a": "3" },
                "400": { "q": "Convert 3/10 to a decimal.", "a": "0.3" },
                "500": { "q": "What is 4.5 - 1.25?", "a": "3.25" },
                "600": { "q": "What is 0.75 + 1.50?", "a": "2.25" },
                "700": { "q": "Convert 0.25 to a simplified fraction.", "a": "1/4" },
                "800": { "q": "What is 12.5 / 5?", "a": "2.5" },
                "900": { "q": "What is 0.1 x 0.1?", "a": "0.01" },
                "1000": { "q": "What is 100 x 0.05?", "a": "5" }
            },
            "Word Problems": {
                "100": { "q": "Sam has 12 apples and eats 4. How many are left?", "a": "8" },
                "200": { "q": "A box holds 8 pencils. How many pencils are in 5 boxes?", "a": "40" },
                "300": { "q": "Lily has $20. She buys a book for $7.50. How much change does she get?", "a": "$12.50" },
                "400": { "q": "A train travels at 60 mph. How far does it go in 3 hours?", "a": "180 miles" },
                "500": { "q": "If 4 pencils cost $2.00, how much do 10 pencils cost?", "a": "$5.00" },
                "600": { "q": "Class has 24 students. 1/3 are girls. How many are boys?", "a": "16" },
                "700": { "q": "A rectangle pool is 10m long and 5m wide. What is its perimeter?", "a": "30m" },
                "800": { "q": "Ben is 12 years old. His sister is half his age. How old will she be in 4 years?", "a": "10" },
                "900": { "q": "A baker made 48 cookies. He sells 3/4 of them. How many are left?", "a": "12" },
                "1000": { "q": "Two numbers sum to 15, and their difference is 3. What are they?", "a": "9 and 6" }
            },
            "Measurement": {
                "100": { "q": "How many centimeters are in 1 meter?", "a": "100" },
                "200": { "q": "How many hours are in 2 days?", "a": "48" },
                "300": { "q": "How many milliliters are in 1.5 liters?", "a": "1500" },
                "400": { "q": "How many grams are in 2.5 kilograms?", "a": "2500" },
                "500": { "q": "How many feet are in 3 yards?", "a": "9" },
                "600": { "q": "A movie starts at 2:15 PM and lasts 1h 45m. When does it end?", "a": "4:00 PM" },
                "700": { "q": "How many ounces are in 1 pound?", "a": "16" },
                "800": { "q": "Convert 5000 meters to kilometers.", "a": "5" },
                "900": { "q": "How many cups are in 1 gallon?", "a": "16" },
                "1000": { "q": "Subtract: 3 hours 15 mins minus 1 hour 45 mins.", "a": "1 hour 30 mins" }
            }
        }
    };

    let jeopardyData = JSON.parse(localStorage.getItem('eduplay_jeopardy_data')) || defaultJeopardyData;
    let currentSelectedCell = null;
    let categoryToEditIndex = null;

    // Helper to ensure data structure is perfect up to 10 columns and 10 rows
    function ensureJeopardyDataStructure(data) {
        if (!data.categories) {
            data.categories = [...defaultJeopardyData.categories];
        }
        
        // Ensure 10 categories exist
        while (data.categories.length < 10) {
            data.categories.push(defaultJeopardyData.categories[data.categories.length] || `Category ${data.categories.length + 1}`);
        }

        if (!data.board) {
            data.board = {};
        }

        if (typeof data.numCategories === "undefined") {
            data.numCategories = 5;
        }
        if (typeof data.numRows === "undefined") {
            data.numRows = 5;
        }

        // Populate clue matrices
        data.categories.forEach(cat => {
            if (!data.board[cat]) {
                data.board[cat] = {};
            }
            for (let r = 1; r <= 10; r++) {
                const points = r * 100;
                if (!data.board[cat][points]) {
                    const defaultClue = defaultJeopardyData.board[cat] && defaultJeopardyData.board[cat][points];
                    data.board[cat][points] = defaultClue ? { ...defaultClue } : {
                        q: `Solve a mathematical problem about ${cat} for $${points}.`,
                        a: "Answer"
                    };
                }
            }
        });
    }

    // Run on startup
    document.addEventListener("DOMContentLoaded", () => {
        ensureJeopardyDataStructure(jeopardyData);
        localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));

        // Sync layout configuration dropdown values
        document.getElementById('config-categories').value = jeopardyData.numCategories;
        document.getElementById('config-rows').value = jeopardyData.numRows;

        renderJeopardyGrid();
    });

    // Render the grid using CSS Grid
    function renderJeopardyGrid() {
        const container = document.getElementById('jeopardy-grid-container');
        if (!container) return;

        const numCats = jeopardyData.numCategories;
        const numRows = jeopardyData.numRows;

        // Apply dynamic grid layout columns
        container.style.setProperty('--cols', numCats);
        container.innerHTML = "";

        // Row 0: Category Headers
        for (let c = 0; c < numCats; c++) {
            const cat = jeopardyData.categories[c];
            const header = document.createElement('div');
            header.className = "jeopardy-category shadow-sm";
            header.textContent = cat;
            header.onclick = () => openCategoryEditModal(c);
            container.appendChild(header);
        }

        // Rows 1-10: Clues
        for (let r = 1; r <= numRows; r++) {
            const points = r * 100;
            for (let c = 0; c < numCats; c++) {
                const cat = jeopardyData.categories[c];
                
                const btn = document.createElement('button');
                btn.type = "button";
                btn.className = "btn jeopardy-cell";
                btn.textContent = `$${points}`;
                btn.onclick = () => openClueEditModal(cat, points);

                container.appendChild(btn);
            }
        }
    }

    // Adjust grid size via dropdowns
    function adjustGridSize() {
        const numCats = parseInt(document.getElementById('config-categories').value);
        const numRows = parseInt(document.getElementById('config-rows').value);
        
        jeopardyData.numCategories = numCats;
        jeopardyData.numRows = numRows;
        
        ensureJeopardyDataStructure(jeopardyData);
        localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));
        renderJeopardyGrid();
    }

    let currentUploadedClueImageBase64 = null;

    function previewClueImage(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(evt) {
            currentUploadedClueImageBase64 = evt.target.result;
            const previewImg = document.getElementById('edit-image-preview');
            const previewWrap = document.getElementById('edit-image-preview-wrapper');
            previewImg.src = currentUploadedClueImageBase64;
            previewWrap.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    function removeClueImagePreview() {
        currentUploadedClueImageBase64 = null;
        document.getElementById('edit-question-image-file').value = "";
        document.getElementById('edit-image-preview-wrapper').classList.add('d-none');
        document.getElementById('edit-image-preview').src = "";
    }

    // Edit Clues Modal
    function openClueEditModal(category, points) {
        currentSelectedCell = { category, points };
        const clue = jeopardyData.board[category][points];

        document.getElementById('edit-modal-category').textContent = `${category} — $${points}`;
        document.getElementById('edit-question').value = clue.q;
        document.getElementById('edit-answer').value = clue.a;

        // Reset file input and preview
        document.getElementById('edit-question-image-file').value = "";
        currentUploadedClueImageBase64 = clue.img || null;

        const previewImg = document.getElementById('edit-image-preview');
        const previewWrap = document.getElementById('edit-image-preview-wrapper');
        if (currentUploadedClueImageBase64) {
            previewImg.src = currentUploadedClueImageBase64;
            previewWrap.classList.remove('d-none');
        } else {
            previewImg.src = "";
            previewWrap.classList.add('d-none');
        }

        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function saveJeopardyClue(e) {
        e.preventDefault();
        if (!currentSelectedCell) return;

        const q = document.getElementById('edit-question').value.trim();
        const a = document.getElementById('edit-answer').value.trim();

        jeopardyData.board[currentSelectedCell.category][currentSelectedCell.points] = {
            q: q,
            a: a,
            img: currentUploadedClueImageBase64
        };
        localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));

        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        renderJeopardyGrid();
    }

    // Category Edit Modal
    function openCategoryEditModal(index) {
        categoryToEditIndex = index;
        document.getElementById('edit-category-name').value = jeopardyData.categories[index];
        new bootstrap.Modal(document.getElementById('categoryEditModal')).show();
    }

    function saveJeopardyCategory(e) {
        e.preventDefault();
        if (categoryToEditIndex === null) return;

        const oldName = jeopardyData.categories[categoryToEditIndex];
        const newName = document.getElementById('edit-category-name').value.trim();

        if (newName && newName !== oldName) {
            jeopardyData.categories[categoryToEditIndex] = newName;
            
            // Move references in board object
            jeopardyData.board[newName] = jeopardyData.board[oldName];
            delete jeopardyData.board[oldName];

            localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));
            renderJeopardyGrid();
        }
        bootstrap.Modal.getInstance(document.getElementById('categoryEditModal')).hide();
    }

    // Restore to Defaults
    function resetJeopardyToDefaults() {
        confirmAction("Restore the board back to the default math cards with 5 Categories and 5 Rows?", "Reset to Defaults").then(result => {
            if (result) {
                jeopardyData = JSON.parse(JSON.stringify(defaultJeopardyData));
                jeopardyData.numCategories = 5;
            jeopardyData.numRows = 5;
            
            ensureJeopardyDataStructure(jeopardyData);
            localStorage.setItem('eduplay_jeopardy_data', JSON.stringify(jeopardyData));

            document.getElementById('config-categories').value = 5;
            document.getElementById('config-rows').value = 5;

            renderJeopardyGrid();
            }
        });
    }
</script>
@endsection
