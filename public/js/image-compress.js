/**
 * MARKAZ - Image Compressor v2 (Fast)
 * Compresses images immediately when selected (not on submit)
 * Uses URL.createObjectURL instead of FileReader (10x faster)
 * 
 * Config: max 1024px, JPEG quality 0.7
 * Result: 5MB photo → ~100-200KB in <500ms
 */
(function() {
    'use strict';

    const CONFIG = {
        maxWidth: 1024,
        maxHeight: 1024,
        quality: 0.7,
        minSizeKB: 100,
    };

    // Compress using createObjectURL (much faster than FileReader/base64)
    function compressImage(file) {
        return new Promise((resolve, reject) => {
            if (!file.type.startsWith('image/')) {
                resolve(file);
                return;
            }
            if (file.size < CONFIG.minSizeKB * 1024) {
                resolve(file);
                return;
            }

            const url = URL.createObjectURL(file);
            const img = new Image();

            img.onload = function() {
                URL.revokeObjectURL(url); // cleanup immediately

                let width = img.naturalWidth;
                let height = img.naturalHeight;

                if (width > CONFIG.maxWidth || height > CONFIG.maxHeight) {
                    const ratio = Math.min(
                        CONFIG.maxWidth / width,
                        CONFIG.maxHeight / height
                    );
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');

                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, width, height);
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob(
                    (blob) => {
                        if (!blob) {
                            resolve(file);
                            return;
                        }
                        const originalName = file.name.replace(/\.[^/.]+$/, '');
                        const compressed = new File([blob], originalName + '.jpg', {
                            type: 'image/jpeg',
                            lastModified: Date.now(),
                        });
                        resolve(compressed);
                    },
                    'image/jpeg',
                    CONFIG.quality
                );
            };

            img.onerror = function() {
                URL.revokeObjectURL(url);
                resolve(file); // fallback to original
            };

            img.src = url;
        });
    }

    // Compress immediately when user selects a file (not on submit)
    function setupInstantCompression() {
        document.querySelectorAll('input[type="file"][accept*="image"]').forEach(input => {
            if (input._markazCompressBound) return;
            input._markazCompressBound = true;

            input.addEventListener('change', async function() {
                if (!this.files || this.files.length === 0) return;

                // Check if any files are images and need compression
                let needsCompress = false;
                for (const f of this.files) {
                    if (f.type.startsWith('image/') && f.size >= CONFIG.minSizeKB * 1024) {
                        needsCompress = true;
                        break;
                    }
                }

                if (!needsCompress) return;

                // Show progress on the file label/drop zone
                const dropZone = this.closest('.border-dashed') || this.closest('label');
                let originalHTML = '';
                if (dropZone) {
                    originalHTML = dropZone.innerHTML;
                    const label = dropZone.querySelector('span.text-sm');
                    if (label) {
                        label.dataset.original = label.innerHTML;
                        label.innerHTML = '⏳ Mengompres...';
                    }
                }

                const compressedFiles = [];
                for (const file of this.files) {
                    try {
                        const compressed = await compressImage(file);
                        compressedFiles.push(compressed);
                    } catch (err) {
                        compressedFiles.push(file);
                    }
                }

                // Replace files in input
                const dt = new DataTransfer();
                compressedFiles.forEach(f => dt.items.add(f));
                this.files = dt.files;

                // Restore label
                if (dropZone) {
                    const label = dropZone.querySelector('span.text-sm');
                    if (label && label.dataset.original) {
                        const saved = label.dataset.original;
                        delete label.dataset.original;
                        label.innerHTML = saved;

                        // Show compression result
                        const sizeInfo = dropZone.querySelector('.compress-info');
                        if (sizeInfo) sizeInfo.remove();

                        const originalSize = compressedFiles.reduce((s, f) => s + f.size, 0);
                        const info = document.createElement('div');
                        info.className = 'compress-info text-xs text-green-600 mt-1';
                        info.textContent = '✅ Terkompres: ' + (originalSize / 1024).toFixed(0) + ' KB';
                        dropZone.appendChild(info);
                    }
                }
            });
        });
    }

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupInstantCompression);
    } else {
        setupInstantCompression();
    }

    // Re-scan after dynamic content
    window.addEventListener('load', () => {
        setTimeout(setupInstantCompression, 100);
    });

    window.MarkazImageCompress = { compress: compressImage, setup: setupInstantCompression };
})();
