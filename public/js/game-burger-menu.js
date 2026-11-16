/**
 * EduPlay Game Burger Menu
 * Provides a compact burger menu for game navigation controls
 */

class GameBurgerMenu {
    constructor(options = {}) {
        this.isOpen = false;
        this.options = options;
        this.createMenu();
        this.attachEventListeners();
    }
    
    createMenu() {
        // Check if menu already exists
        if (document.getElementById('game-burger-menu')) {
            return;
        }
        
        const menuContainer = document.createElement('div');
        menuContainer.id = 'game-burger-menu';
        menuContainer.className = 'game-burger-container';
        
        menuContainer.innerHTML = `
            <button id="burger-toggle-btn" class="burger-toggle-btn" title="Menu" aria-label="Toggle menu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
            
            <div id="burger-menu-panel" class="burger-menu-panel">
                <div class="burger-menu-content">
                    <div class="burger-menu-header">
                        <span class="burger-menu-title">Game Menu</span>
                        <button id="burger-close-btn" class="burger-close-btn" aria-label="Close menu">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="burger-menu-items" id="burger-menu-items">
                        <!-- Menu items will be injected here -->
                    </div>
                </div>
            </div>
            
            <div id="burger-overlay" class="burger-overlay"></div>
        `;
        
        document.body.appendChild(menuContainer);
        this.injectStyles();
        this.populateMenuItems();
    }
    
    populateMenuItems() {
        const menuItems = document.getElementById('burger-menu-items');
        if (!menuItems) return;
        
        // Clear existing items
        menuItems.innerHTML = '';
        
        // Build menu items array
        const items = [];
        
        // Pause button
        const pauseBtn = document.querySelector('[data-burger-menu="pause"]');
        if (pauseBtn) {
            items.push({
                icon: 'bi-pause-circle-fill',
                label: 'Pause Game',
                id: 'burger-pause',
                action: () => pauseBtn.click()
            });
        }
        
        // Sound button
        const soundBtn = document.querySelector('[data-burger-menu="sound"]');
        if (soundBtn) {
            items.push({
                icon: 'bi-volume-up-fill',
                label: 'Toggle Sound',
                id: 'burger-sound',
                action: () => soundBtn.click()
            });
        }
        
        // Tutorial/Help button
        const helpBtn = document.querySelector('[data-burger-menu="help"]');
        if (helpBtn) {
            items.push({
                icon: 'bi-question-circle-fill',
                label: 'How to Play',
                id: 'burger-help',
                action: () => helpBtn.click()
            });
        }
        
        // Game Map link
        const mapLink = document.querySelector('[data-burger-menu="map"]');
        if (mapLink) {
            items.push({
                icon: 'bi-map-fill',
                label: 'Game Map',
                id: 'burger-map',
                href: mapLink.href
            });
        }
        
        // Exit link
        const exitLink = document.querySelector('[data-burger-menu="exit"]');
        if (exitLink) {
            items.push({
                icon: 'bi-box-arrow-right',
                label: 'Exit Game',
                id: 'burger-exit',
                href: exitLink.href
            });
        }
        
        // Create menu item elements
        items.forEach(item => {
            const menuItem = document.createElement(item.href ? 'a' : 'button');
            menuItem.className = 'burger-menu-item';
            menuItem.id = item.id;
            
            if (item.href) {
                menuItem.href = item.href;
            } else if (item.action) {
                menuItem.addEventListener('click', () => {
                    item.action();
                    this.closeMenu();
                });
            }
            
            menuItem.innerHTML = `
                <i class="bi ${item.icon}"></i>
                <span>${item.label}</span>
            `;
            
            menuItems.appendChild(menuItem);
        });
    }
    
    injectStyles() {
        if (document.getElementById('game-burger-styles')) {
            return;
        }
        
        const style = document.createElement('style');
        style.id = 'game-burger-styles';
        style.textContent = `
            .game-burger-container {
                position: fixed;
                top: 16px;
                left: 16px;
                z-index: 999999;
            }
            
            .burger-toggle-btn {
                width: 50px;
                height: 50px;
                background: linear-gradient(165deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.95) 100%);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1.5px solid rgba(99, 102, 241, 0.4);
                border-radius: 14px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
                padding: 0;
            }
            
            .burger-toggle-btn:hover {
                background: linear-gradient(165deg, rgba(30, 41, 59, 0.98) 0%, rgba(15, 23, 42, 0.98) 100%);
                border-color: rgba(99, 102, 241, 0.6);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            }
            
            .burger-line {
                width: 24px;
                height: 3px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border-radius: 2px;
                transition: all 0.3s ease;
            }
            
            .burger-toggle-btn.active .burger-line:nth-child(1) {
                transform: translateY(8px) rotate(45deg);
            }
            
            .burger-toggle-btn.active .burger-line:nth-child(2) {
                opacity: 0;
            }
            
            .burger-toggle-btn.active .burger-line:nth-child(3) {
                transform: translateY(-8px) rotate(-45deg);
            }
            
            .burger-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                z-index: 999998;
            }
            
            .burger-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .burger-menu-panel {
                position: fixed;
                top: 0;
                left: 0;
                width: 320px;
                max-width: 85vw;
                height: 100%;
                background: linear-gradient(165deg, rgba(15, 23, 42, 0.98) 0%, rgba(10, 16, 32, 0.98) 100%);
                backdrop-filter: blur(30px);
                -webkit-backdrop-filter: blur(30px);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.5);
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 999999;
                overflow-y: auto;
            }
            
            .burger-menu-panel.active {
                transform: translateX(0);
            }
            
            .burger-menu-content {
                padding: 20px;
            }
            
            .burger-menu-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
                padding-bottom: 16px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .burger-menu-title {
                font-family: 'Space Grotesk', sans-serif;
                font-size: 1.4rem;
                font-weight: 800;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .burger-close-btn {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #94a3b8;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                padding: 0;
            }
            
            .burger-close-btn:hover {
                background: rgba(255, 255, 255, 0.1);
                color: #fff;
                border-color: rgba(255, 255, 255, 0.2);
            }
            
            .burger-menu-items {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .burger-menu-item {
                display: flex;
                align-items: center;
                gap: 14px;
                padding: 14px 16px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 12px;
                color: #e2e8f0;
                font-size: 0.95rem;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .burger-menu-item:hover {
                background: rgba(99, 102, 241, 0.15);
                border-color: rgba(99, 102, 241, 0.3);
                color: #fff;
                transform: translateX(4px);
            }
            
            .burger-menu-item i {
                font-size: 1.3rem;
                color: #818cf8;
                transition: color 0.2s ease;
            }
            
            .burger-menu-item:hover i {
                color: #a5b4fc;
            }
            
            /* Hide original navigation buttons */
            [data-burger-menu] {
                display: none !important;
            }
            
            /* Mobile adjustments */
            @media (max-width: 480px) {
                .game-burger-container {
                    top: 12px;
                    left: 12px;
                }
                
                .burger-toggle-btn {
                    width: 44px;
                    height: 44px;
                }
                
                .burger-line {
                    width: 20px;
                    height: 2.5px;
                }
                
                .burger-menu-panel {
                    width: 280px;
                }
                
                .burger-menu-title {
                    font-size: 1.2rem;
                }
                
                .burger-menu-item {
                    padding: 12px 14px;
                    font-size: 0.9rem;
                }
            }
            
            /* Accessibility */
            @media (prefers-reduced-motion: reduce) {
                .burger-toggle-btn,
                .burger-menu-panel,
                .burger-overlay,
                .burger-menu-item {
                    transition: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    attachEventListeners() {
        const toggleBtn = document.getElementById('burger-toggle-btn');
        const closeBtn = document.getElementById('burger-close-btn');
        const overlay = document.getElementById('burger-overlay');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleMenu());
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeMenu());
        }
        
        if (overlay) {
            overlay.addEventListener('click', () => this.closeMenu());
        }
        
        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeMenu();
            }
        });
    }
    
    toggleMenu() {
        if (this.isOpen) {
            this.closeMenu();
        } else {
            this.openMenu();
        }
    }
    
    openMenu() {
        this.isOpen = true;
        const toggleBtn = document.getElementById('burger-toggle-btn');
        const panel = document.getElementById('burger-menu-panel');
        const overlay = document.getElementById('burger-overlay');
        
        if (toggleBtn) toggleBtn.classList.add('active');
        if (panel) panel.classList.add('active');
        if (overlay) overlay.classList.add('active');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    closeMenu() {
        this.isOpen = false;
        const toggleBtn = document.getElementById('burger-toggle-btn');
        const panel = document.getElementById('burger-menu-panel');
        const overlay = document.getElementById('burger-overlay');
        
        if (toggleBtn) toggleBtn.classList.remove('active');
        if (panel) panel.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    destroy() {
        const container = document.getElementById('game-burger-menu');
        if (container) {
            container.remove();
        }
        
        // Show hidden navigation buttons
        document.querySelectorAll('[data-burger-menu]').forEach(el => {
            el.style.display = '';
        });
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
}

// Export for use in games
window.GameBurgerMenu = GameBurgerMenu;
