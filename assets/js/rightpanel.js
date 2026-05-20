/**
 * RightPanel.js - Slide-in Drawer Utility
 * Version 3.0 - Fixed overlay and scroll issues
 */

const RightPanel = (function() {
    'use strict';
    
    let panelStack = [];
    let panelCounter = 0;
    let overlay = null;
    let isClosing = false;
    
    // Create overlay element
    function createOverlay() {
        if (overlay) return overlay;
        
        overlay = document.createElement('div');
        overlay.className = 'rightpanel-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay && !isClosing) {
                closeTop();
            }
        });
        
        document.body.appendChild(overlay);
        return overlay;
    }
    
    // Lock body scroll
    function lockBodyScroll() {
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '0px';
    }
    
    // Unlock body scroll
    function unlockBodyScroll() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    // Create panel element
    function createPanel(id, options) {
        const panel = document.createElement('div');
        panel.id = id;
        panel.className = 'rightpanel-drawer';
        const width = options.width || '460px';
        
        panel.style.cssText = `
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: ${width};
            max-width: 90vw;
            background-color: #fff;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        `;
        
        if (window.innerWidth < 640) {
            panel.style.width = '100vw';
            panel.style.maxWidth = '100vw';
        }
        
        // Header
        const header = document.createElement('div');
        header.className = 'rightpanel-header';
        header.style.cssText = `
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        `;
        
        const iconSpan = document.createElement('span');
        iconSpan.className = 'rightpanel-icon';
        iconSpan.style.fontSize = '24px';
        iconSpan.innerHTML = options.icon || '📄';
        
        const titleSpan = document.createElement('span');
        titleSpan.className = 'rightpanel-title';
        titleSpan.style.cssText = `
            font-size: 18px;
            font-weight: 600;
            flex: 1;
        `;
        titleSpan.textContent = options.title || 'Panel';
        
        const closeBtn = document.createElement('button');
        closeBtn.className = 'rightpanel-close';
        closeBtn.innerHTML = '✕';
        closeBtn.style.cssText = `
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        `;
        closeBtn.onmouseover = () => closeBtn.style.backgroundColor = '#f0f0f0';
        closeBtn.onmouseout = () => closeBtn.style.backgroundColor = 'transparent';
        
        header.appendChild(iconSpan);
        header.appendChild(titleSpan);
        header.appendChild(closeBtn);
        
        // Body - sanitize HTML
        let htmlContent = options.html || '';
        htmlContent = htmlContent.replace(/value="null"/gi, 'value=""');
        htmlContent = htmlContent.replace(/value='null'/gi, "value=''");
        htmlContent = htmlContent.replace(/value="0000-00-00"/gi, 'value=""');
        
        const body = document.createElement('div');
        body.className = 'rightpanel-body';
        body.style.cssText = `
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        `;
        body.innerHTML = htmlContent;
        
        // Footer
        let footer = null;
        if (options.footer !== undefined && options.footer !== '') {
            footer = document.createElement('div');
            footer.className = 'rightpanel-footer';
            footer.style.cssText = `
                padding: 15px 20px;
                border-top: 1px solid #e0e0e0;
                flex-shrink: 0;
                background-color: #fff;
            `;
            footer.innerHTML = options.footer;
        }
        
        panel.appendChild(header);
        panel.appendChild(body);
        if (footer) panel.appendChild(footer);
        
        // Store callbacks
        panel._onClose = options.onClose;
        
        // Store close button reference
        panel._closeBtn = closeBtn;
        
        return panel;
    }
    
    // Show panel
    function showPanel(panel) {
        const overlayEl = createOverlay();
        document.body.appendChild(panel);
        overlayEl.style.display = 'block';
        
        // Force reflow
        panel.offsetHeight;
        
        overlayEl.style.opacity = '1';
        panel.style.transform = 'translateX(0)';
        lockBodyScroll();
    }
    
    // Hide panel
    function hidePanel(panel, skipCallback = false) {
        if (!panel) return;
        
        panel.style.transform = 'translateX(100%)';
        
        if (!skipCallback && panel._onClose && typeof panel._onClose === 'function') {
            try {
                panel._onClose();
            } catch(e) {
                console.error('Error in onClose callback:', e);
            }
        }
        
        setTimeout(() => {
            if (panel && panel.parentNode) {
                panel.parentNode.removeChild(panel);
            }
        }, 300);
    }
    
    // Close topmost panel
    function closeTop() {
        if (isClosing) return;
        if (panelStack.length === 0) return;
        
        isClosing = true;
        
        const topPanel = panelStack.pop();
        hidePanel(topPanel.element);
        
        // If no more panels, hide overlay and unlock scroll
        if (panelStack.length === 0) {
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    if (overlay) {
                        overlay.style.display = 'none';
                    }
                    unlockBodyScroll();
                    isClosing = false;
                }, 300);
            } else {
                unlockBodyScroll();
                isClosing = false;
            }
        } else {
            // Update z-index for remaining panels
            panelStack.forEach((panel, index) => {
                panel.element.style.zIndex = 9999 + index;
            });
            isClosing = false;
        }
    }
    
    // Close all panels
    function closeAll() {
        if (isClosing) return;
        isClosing = true;
        
        while (panelStack.length > 0) {
            const panel = panelStack.pop();
            hidePanel(panel.element);
        }
        
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => {
                if (overlay) {
                    overlay.style.display = 'none';
                }
                unlockBodyScroll();
                isClosing = false;
            }, 300);
        } else {
            unlockBodyScroll();
            isClosing = false;
        }
    }
    
    // Close specific panel by ID
    function close(id) {
        if (isClosing) return;
        
        const index = panelStack.findIndex(p => p.id === id);
        if (index !== -1) {
            isClosing = true;
            const panel = panelStack.splice(index, 1)[0];
            hidePanel(panel.element);
            
            if (panelStack.length === 0) {
                if (overlay) {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        if (overlay) {
                            overlay.style.display = 'none';
                        }
                        unlockBodyScroll();
                        isClosing = false;
                    }, 300);
                } else {
                    unlockBodyScroll();
                    isClosing = false;
                }
            } else {
                isClosing = false;
            }
        }
    }
    
    // Open new panel
    function open(options) {
        const id = 'panel_' + (++panelCounter);
        const panel = createPanel(id, options);
        
        panelStack.push({
            id: id,
            element: panel
        });
        
        // Update z-index for stacking
        panelStack.forEach((p, idx) => {
            p.element.style.zIndex = 9999 + idx;
        });
        
        showPanel(panel);
        
        // Set up close button handler
        if (panel._closeBtn) {
            panel._closeBtn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                closeTop();
            };
        }
        
        return id;
    }
    
    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && panelStack.length > 0 && !isClosing) {
            e.preventDefault();
            closeTop();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const isNarrow = window.innerWidth < 640;
        panelStack.forEach(panel => {
            if (isNarrow) {
                panel.element.style.width = '100vw';
                panel.element.style.maxWidth = '100vw';
            } else {
                const originalWidth = panel.element.style.width;
                if (originalWidth !== '100vw') {
                    // Keep original width
                }
            }
        });
    });
    
    // Public API
    return {
        open: open,
        closeTop: closeTop,
        closeAll: closeAll,
        close: close
    };
})();