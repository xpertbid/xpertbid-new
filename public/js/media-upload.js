/**
 * Media Upload Component for XpertBid
 * Handles file uploads with progress, preview, and thumbnail generation
 */

class MediaUploader {
    constructor(options = {}) {
        this.apiBaseUrl = options.apiBaseUrl || '/api';
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.maxFileSize = options.maxFileSize || 100 * 1024 * 1024; // 100MB
        this.allowedTypes = options.allowedTypes || {
            images: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            videos: ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'],
            documents: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        };
    }

    /**
     * Upload files to a model
     */
    async uploadFiles(modelType, modelId, collection, files, onProgress = null) {
        const formData = new FormData();
        
        // Add files to form data
        Array.from(files).forEach(file => {
            formData.append('files[]', file);
        });

        // Add other parameters
        formData.append('model_type', modelType);
        formData.append('model_id', modelId);
        formData.append('collection', collection);

        try {
            const response = await fetch(`${this.apiBaseUrl}/media/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Upload failed');
            }

            return result;
        } catch (error) {
            console.error('Upload error:', error);
            throw error;
        }
    }

    /**
     * Get media files for a model
     */
    async getMedia(modelType, modelId, collection = null) {
        const params = new URLSearchParams({
            model_type: modelType,
            model_id: modelId
        });

        if (collection) {
            params.append('collection', collection);
        }

        try {
            const response = await fetch(`${this.apiBaseUrl}/media?${params}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Failed to fetch media');
            }

            return result.data;
        } catch (error) {
            console.error('Get media error:', error);
            throw error;
        }
    }

    /**
     * Delete a media file
     */
    async deleteMedia(mediaId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Failed to delete media');
            }

            return result;
        } catch (error) {
            console.error('Delete media error:', error);
            throw error;
        }
    }

    /**
     * Update media order
     */
    async updateMediaOrder(mediaIds) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/media/order`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ media_ids: mediaIds })
            });

            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Failed to update media order');
            }

            return result;
        } catch (error) {
            console.error('Update order error:', error);
            throw error;
        }
    }

    /**
     * Get available collections for a model type
     */
    async getCollections(modelType) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/media/collections?model_type=${modelType}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Failed to fetch collections');
            }

            return result.data;
        } catch (error) {
            console.error('Get collections error:', error);
            throw error;
        }
    }

    /**
     * Validate file before upload
     */
    validateFile(file) {
        const errors = [];

        // Check file size
        if (file.size > this.maxFileSize) {
            errors.push(`File ${file.name} is too large. Maximum size is ${this.maxFileSize / (1024 * 1024)}MB`);
        }

        // Check file type
        const allAllowedTypes = Object.values(this.allowedTypes).flat();
        if (!allAllowedTypes.includes(file.type)) {
            errors.push(`File ${file.name} has an unsupported type: ${file.type}`);
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Create file preview
     */
    createPreview(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                const preview = {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    url: e.target.result,
                    isImage: file.type.startsWith('image/'),
                    isVideo: file.type.startsWith('video/'),
                };
                resolve(preview);
            };
            
            reader.readAsDataURL(file);
        });
    }

    /**
     * Format file size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

/**
 * Media Upload UI Component
 */
class MediaUploadUI {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.uploader = new MediaUploader(options);
        this.modelType = options.modelType;
        this.modelId = options.modelId;
        this.collection = options.collection;
        this.maxFiles = options.maxFiles || 10;
        this.onUploadComplete = options.onUploadComplete || null;
        this.onDeleteComplete = options.onDeleteComplete || null;
        
        this.init();
    }

    init() {
        this.createUploadArea();
        this.createMediaGrid();
        this.loadExistingMedia();
    }

    createUploadArea() {
        const uploadArea = document.createElement('div');
        uploadArea.className = 'media-upload-area';
        uploadArea.innerHTML = `
            <div class="upload-zone" id="upload-zone">
                <div class="upload-content">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4>Drag & Drop files here</h4>
                    <p>or <span class="upload-link">click to browse</span></p>
                    <input type="file" id="file-input" multiple accept="image/*,video/*,.pdf,.doc,.docx" style="display: none;">
                </div>
                <div class="upload-progress" id="upload-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <span class="progress-text" id="progress-text">0%</span>
                </div>
            </div>
        `;

        this.container.appendChild(uploadArea);
        this.setupUploadEvents();
    }

    createMediaGrid() {
        const mediaGrid = document.createElement('div');
        mediaGrid.className = 'media-grid';
        mediaGrid.id = 'media-grid';
        this.container.appendChild(mediaGrid);
    }

    setupUploadEvents() {
        const uploadZone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('file-input');

        // Click to upload
        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            this.handleFiles(e.target.files);
        });

        // Drag and drop
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            this.handleFiles(e.dataTransfer.files);
        });
    }

    async handleFiles(files) {
        const fileArray = Array.from(files);
        const validFiles = [];
        const errors = [];

        // Validate files
        fileArray.forEach(file => {
            const validation = this.uploader.validateFile(file);
            if (validation.isValid) {
                validFiles.push(file);
            } else {
                errors.push(...validation.errors);
            }
        });

        // Show errors
        if (errors.length > 0) {
            this.showErrors(errors);
        }

        // Upload valid files
        if (validFiles.length > 0) {
            await this.uploadFiles(validFiles);
        }
    }

    async uploadFiles(files) {
        const progressContainer = document.getElementById('upload-progress');
        const progressFill = document.getElementById('progress-fill');
        const progressText = document.getElementById('progress-text');

        progressContainer.style.display = 'block';

        try {
            const result = await this.uploader.uploadFiles(
                this.modelType,
                this.modelId,
                this.collection,
                files,
                (progress) => {
                    progressFill.style.width = `${progress}%`;
                    progressText.textContent = `${progress}%`;
                }
            );

            // Hide progress
            progressContainer.style.display = 'none';

            // Add uploaded media to grid
            result.data.forEach(media => {
                this.addMediaToGrid(media);
            });

            // Callback
            if (this.onUploadComplete) {
                this.onUploadComplete(result);
            }

            // Show success message
            this.showSuccess(`Successfully uploaded ${result.data.length} file(s)`);

        } catch (error) {
            progressContainer.style.display = 'none';
            this.showError('Upload failed: ' + error.message);
        }
    }

    async loadExistingMedia() {
        try {
            const media = await this.uploader.getMedia(this.modelType, this.modelId, this.collection);
            media.forEach(item => {
                this.addMediaToGrid(item);
            });
        } catch (error) {
            console.error('Failed to load existing media:', error);
        }
    }

    addMediaToGrid(media) {
        const mediaGrid = document.getElementById('media-grid');
        const mediaItem = document.createElement('div');
        mediaItem.className = 'media-item';
        mediaItem.dataset.mediaId = media.id;

        const isImage = media.mime_type.startsWith('image/');
        const isVideo = media.mime_type.startsWith('video/');

        mediaItem.innerHTML = `
            <div class="media-preview">
                ${isImage ? `<img src="${media.thumb_url || media.url}" alt="${media.name}">` : ''}
                ${isVideo ? `<video><source src="${media.url}" type="${media.mime_type}"></video>` : ''}
                ${!isImage && !isVideo ? `<i class="fas fa-file"></i>` : ''}
            </div>
            <div class="media-info">
                <span class="media-name">${media.name}</span>
                <span class="media-size">${this.uploader.formatFileSize(media.size)}</span>
            </div>
            <div class="media-actions">
                <button class="btn btn-sm btn-outline-primary" onclick="mediaUploadUI.viewMedia(${media.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="mediaUploadUI.deleteMedia(${media.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        mediaGrid.appendChild(mediaItem);
    }

    async deleteMedia(mediaId) {
        if (!confirm('Are you sure you want to delete this media?')) {
            return;
        }

        try {
            await this.uploader.deleteMedia(mediaId);
            
            // Remove from grid
            const mediaItem = document.querySelector(`[data-media-id="${mediaId}"]`);
            if (mediaItem) {
                mediaItem.remove();
            }

            // Callback
            if (this.onDeleteComplete) {
                this.onDeleteComplete(mediaId);
            }

            this.showSuccess('Media deleted successfully');

        } catch (error) {
            this.showError('Failed to delete media: ' + error.message);
        }
    }

    viewMedia(mediaId) {
        // Implementation for viewing media in modal
        console.log('View media:', mediaId);
    }

    showSuccess(message) {
        // Implementation for success notification
        console.log('Success:', message);
    }

    showError(message) {
        // Implementation for error notification
        console.error('Error:', message);
    }

    showErrors(errors) {
        // Implementation for showing validation errors
        console.error('Validation errors:', errors);
    }
}

// Global instance for easy access
let mediaUploadUI = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Example usage:
    // mediaUploadUI = new MediaUploadUI('media-container', {
    //     modelType: 'product',
    //     modelId: 1,
    //     collection: 'gallery',
    //     maxFiles: 10,
    //     onUploadComplete: (result) => console.log('Upload complete:', result),
    //     onDeleteComplete: (mediaId) => console.log('Delete complete:', mediaId)
    // });
});
