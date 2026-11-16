/**
 * EduPlay Game Music Player
 * Provides background music with volume controls for games
 */

class GameMusicPlayer {
    constructor(musicPath) {
        this.audio = new Audio(musicPath);
        this.audio.loop = true;
        this.audio.volume = 0.3; // Default volume at 30%
        this.isPlaying = false;
        this.isMuted = false;
        
        // Load saved volume from localStorage
        const savedVolume = localStorage.getItem('eduplay_music_volume');
        if (savedVolume !== null) {
            this.audio.volume = parseFloat(savedVolume);
        }
        
        const savedMuted = localStorage.getItem('eduplay_music_muted');
        if (savedMuted === 'true') {
            this.isMuted = true;
            this.audio.muted = true;
        }
        
        this.createControls();
        this.attachEventListeners();
    }
    
    createControls() {
        // Check if controls already exist
        if (document.getElementById('game-music-controls')) {
            return;
        }
        
        const controls = document.createElement('div');
        controls.id = 'game-music-controls';
        controls.className = 'game-music-controls';
        controls.innerHTML = `
            <button id="music-toggle-btn" class="music-control-btn" title="Toggle Music">
                <i class="bi ${this.isMuted ? 'bi-volume-mute-fill' : 'bi-volume-up-fill'}"></i>
            </button>
            <div class="music-volume-container">
                <input type="range" id="music-volume-slider" class="music-volume-slider" 
                       min="0" max="100" value="${this.audio.volume * 100}" 
                       title="Volume Control">
                <span class="volume-indicator">${Math.round(this.audio.volume * 100)}%</span>
            </div>
        `;
        
        document.body.appendChild(controls);
        this.injectStyles();
    }
    
    injectStyles() {
        if (document.getElementById('game-music-styles')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'game-music-styles';
        style.textContent = `
            .game-music-controls {
                position: fixed;
                top: 80px;
                right: 16px;
                z-index: 999997;
                display: flex;
                align-items: center;
                gap: 12px;
                background: linear-gradient(165deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1.5px solid rgba(99, 102, 241, 0.4);
                border-radius: 16px;
                padding: 12px 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 20px rgba(99, 102, 241, 0.15);
                transition: all 0.3s ease;
            }
            
            .game-music-controls:hover {
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6), 0 0 30px rgba(99, 102, 241, 0.25);
                transform: translateY(-2px);
            }
            
            .music-control-btn {
                background: rgba(99, 102, 241, 0.2);
                border: 1.5px solid rgba(99, 102, 241, 0.4);
                color: #a5b4fc;
                border-radius: 12px;
                width: 42px;
                height: 42px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                font-size: 1.2rem;
            }
            
            .music-control-btn:hover {
                background: rgba(99, 102, 241, 0.35);
                border-color: #818cf8;
                color: #fff;
                transform: scale(1.05);
            }
            
            .music-control-btn:active {
                transform: scale(0.95);
            }
            
            .music-volume-container {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .music-volume-slider {
                width: 100px;
                height: 6px;
                -webkit-appearance: none;
                appearance: none;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
                outline: none;
                cursor: pointer;
            }
            
            .music-volume-slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 16px;
                height: 16px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border-radius: 50%;
                cursor: pointer;
                box-shadow: 0 0 10px rgba(99, 102, 241, 0.6);
                transition: all 0.2s ease;
            }
            
            .music-volume-slider::-webkit-slider-thumb:hover {
                transform: scale(1.2);
                box-shadow: 0 0 15px rgba(99, 102, 241, 0.8);
            }
            
            .music-volume-slider::-moz-range-thumb {
                width: 16px;
                height: 16px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border: none;
                border-radius: 50%;
                cursor: pointer;
                box-shadow: 0 0 10px rgba(99, 102, 241, 0.6);
                transition: all 0.2s ease;
            }
            
            .music-volume-slider::-moz-range-thumb:hover {
                transform: scale(1.2);
                box-shadow: 0 0 15px rgba(99, 102, 241, 0.8);
            }
            
            .volume-indicator {
                font-size: 0.85rem;
                font-weight: 700;
                color: #a5b4fc;
                min-width: 40px;
                text-align: right;
            }
            
            /* Mobile responsive - compact vertical layout */
            @media (max-width: 768px) {
                .game-music-controls {
                    top: 70px;
                    right: 10px;
                    flex-direction: column;
                    padding: 8px;
                    gap: 8px;
                    border-radius: 14px;
                }
                
                .music-control-btn {
                    width: 36px;
                    height: 36px;
                    font-size: 1rem;
                }
                
                .music-volume-container {
                    flex-direction: column;
                    gap: 6px;
                }
                
                .music-volume-slider {
                    width: 60px;
                    transform: rotate(-90deg);
                    transform-origin: center;
                }
                
                .volume-indicator {
                    font-size: 0.75rem;
                    min-width: auto;
                }
            }
            
            /* Very small mobile - icon only */
            @media (max-width: 480px) {
                .game-music-controls {
                    top: 65px;
                    right: 8px;
                    padding: 6px;
                }
                
                .music-control-btn {
                    width: 32px;
                    height: 32px;
                    font-size: 0.9rem;
                }
                
                .music-volume-container {
                    display: none;
                }
            }
            
            /* Landscape mobile - move to corner */
            @media (max-width: 768px) and (max-height: 500px) {
                .game-music-controls {
                    top: auto;
                    bottom: 10px;
                    right: 10px;
                    flex-direction: row;
                    padding: 6px 10px;
                    gap: 8px;
                }
                
                .music-volume-container {
                    flex-direction: row;
                    gap: 8px;
                }
                
                .music-volume-slider {
                    transform: none;
                    width: 60px;
                }
            }
            
            /* Accessibility */
            @media (prefers-reduced-motion: reduce) {
                .game-music-controls,
                .music-control-btn {
                    transition: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    attachEventListeners() {
        const toggleBtn = document.getElementById('music-toggle-btn');
        const volumeSlider = document.getElementById('music-volume-slider');
        const volumeIndicator = document.querySelector('.volume-indicator');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                this.toggleMute();
            });
        }
        
        if (volumeSlider) {
            volumeSlider.addEventListener('input', (e) => {
                const volume = e.target.value / 100;
                this.setVolume(volume);
                if (volumeIndicator) {
                    volumeIndicator.textContent = Math.round(volume * 100) + '%';
                }
            });
        }
        
        // Try to autoplay immediately; if blocked, play on first user interaction
        this.audio.play().then(() => {
            this.isPlaying = true;
        }).catch(() => {
            // Autoplay blocked — wait for first interaction
            const startOnInteraction = () => {
                if (!this.isPlaying) {
                    this.play();
                }
                document.removeEventListener('click', startOnInteraction);
                document.removeEventListener('keydown', startOnInteraction);
                document.removeEventListener('touchstart', startOnInteraction);
            };
            document.addEventListener('click', startOnInteraction);
            document.addEventListener('keydown', startOnInteraction);
            document.addEventListener('touchstart', startOnInteraction);
        });
    }
    
    play() {
        if (!this.isPlaying) {
            this.audio.play().catch(err => {
                console.log('Music autoplay prevented:', err);
            });
            this.isPlaying = true;
        }
    }
    
    pause() {
        if (this.isPlaying) {
            this.audio.pause();
            this.isPlaying = false;
        }
    }
    
    toggleMute() {
        this.isMuted = !this.isMuted;
        this.audio.muted = this.isMuted;
        
        const toggleBtn = document.getElementById('music-toggle-btn');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                icon.className = this.isMuted ? 'bi bi-volume-mute-fill' : 'bi bi-volume-up-fill';
            }
        }
        
        localStorage.setItem('eduplay_music_muted', this.isMuted);
    }
    
    setVolume(volume) {
        this.audio.volume = Math.max(0, Math.min(1, volume));
        localStorage.setItem('eduplay_music_volume', this.audio.volume);
        
        // Unmute if volume is adjusted above 0
        if (volume > 0 && this.isMuted) {
            this.toggleMute();
        }
    }
    
    destroy() {
        this.pause();
        const controls = document.getElementById('game-music-controls');
        if (controls) {
            controls.remove();
        }
    }
}

// Export for use in games
window.GameMusicPlayer = GameMusicPlayer;
