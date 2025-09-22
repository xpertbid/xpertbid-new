<!-- Translation Manager Component -->
<div class="translation-manager" data-model="{{ $model }}" data-id="{{ $id }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="fas fa-globe"></i> Multi-Language Content
        </h5>
        <button class="btn btn-primary btn-sm" onclick="openTranslationModal()">
            <i class="fas fa-plus"></i> Add Translation
        </button>
    </div>
    
    <div class="row" id="translationsList">
        <!-- Translations will be loaded here -->
    </div>

    <!-- Translation Modal -->
    <div class="modal fade" id="translationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Translation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="translationForm">
                        <input type="hidden" id="translationId" name="id">
                        <input type="hidden" id="translationLocale" name="locale">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Language</label>
                                    <select class="form-select" id="languageSelect" name="locale" required>
                                        <option value="">Select Language</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="isTranslated" checked>
                                        <label class="form-check-label" for="isTranslated">
                                            Translation Complete
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name/Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="translationName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea class="form-control" id="translationShortDescription" name="short_description" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="translationDescription" name="description" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="translationMetaTitle" name="meta_title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" id="translationMetaKeywords" name="meta_keywords">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea class="form-control" id="translationMetaDescription" name="meta_description" rows="2"></textarea>
                        </div>

                        <!-- Dynamic fields based on model type -->
                        <div id="dynamicFields">
                            <!-- Features, Specifications, etc. will be added here -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveTranslation()">Save Translation</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let translationManager = {
    model: '{{ $model }}',
    id: {{ $id }},
    translations: [],
    availableLanguages: [],

    init() {
        this.loadAvailableLanguages();
        this.loadTranslations();
    },

    async loadAvailableLanguages() {
        // Static language data for now
        this.availableLanguages = [
            { code: 'en', name: 'English', native_name: 'English', is_translated: false },
            { code: 'es', name: 'Spanish', native_name: 'Español', is_translated: false },
            { code: 'fr', name: 'French', native_name: 'Français', is_translated: false },
            { code: 'de', name: 'German', native_name: 'Deutsch', is_translated: false },
            { code: 'ar', name: 'Arabic', native_name: 'العربية', is_translated: false },
            { code: 'zh', name: 'Chinese', native_name: '中文', is_translated: false }
        ];
        this.populateLanguageSelect();
    },

    async loadTranslations() {
        try {
            const response = await fetch(`/api/admin/translations/${this.model}/${this.id}`);
            const result = await response.json();
            
            if (result.success) {
                this.translations = result.data;
                this.renderTranslations();
            }
        } catch (error) {
            console.error('Failed to load translations:', error);
        }
    },

    populateLanguageSelect() {
        const select = document.getElementById('languageSelect');
        select.innerHTML = '<option value="">Select Language</option>';
        
        this.availableLanguages.forEach(lang => {
            const option = document.createElement('option');
            option.value = lang.code;
            option.textContent = `${lang.native_name} (${lang.name})`;
            if (lang.is_translated) {
                option.textContent += ' - Already Translated';
                option.disabled = true;
            }
            select.appendChild(option);
        });
    },

    renderTranslations() {
        const container = document.getElementById('translationsList');
        container.innerHTML = '';

        this.translations.forEach(translation => {
            const language = this.availableLanguages.find(lang => lang.code === translation.locale);
            const card = document.createElement('div');
            card.className = 'col-md-6 mb-3';
            card.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title">
                                    <img src="/images/flags/${translation.locale}.png" width="20" height="15" class="me-2">
                                    ${language ? language.native_name : translation.locale.toUpperCase()}
                                </h6>
                                <p class="card-text">${translation.name}</p>
                                <small class="text-muted">${translation.description ? translation.description.substring(0, 100) + '...' : 'No description'}</small>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="translationManager.editTranslation('${translation.locale}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="translationManager.deleteTranslation('${translation.locale}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    },

    async editTranslation(locale) {
        const translation = this.translations.find(t => t.locale === locale);
        if (!translation) return;

        // Populate form
        document.getElementById('translationId').value = translation.id;
        document.getElementById('translationLocale').value = translation.locale;
        document.getElementById('translationName').value = translation.name;
        document.getElementById('translationDescription').value = translation.description || '';
        document.getElementById('translationShortDescription').value = translation.short_description || '';
        document.getElementById('translationMetaTitle').value = translation.meta_title || '';
        document.getElementById('translationMetaDescription').value = translation.meta_description || '';
        document.getElementById('translationMetaKeywords').value = translation.meta_keywords || '';

        // Disable language select for editing
        document.getElementById('languageSelect').disabled = true;

        $('#translationModal').modal('show');
    },

    async deleteTranslation(locale) {
        if (!confirm('Are you sure you want to delete this translation?')) return;

        try {
            const response = await fetch(`/api/admin/translations/${this.model}/${this.id}/${locale}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            
            if (result.success) {
                Swal.fire('Success!', 'Translation deleted successfully', 'success');
                this.loadTranslations();
                this.loadAvailableLanguages();
            } else {
                Swal.fire('Error!', result.message, 'error');
            }
        } catch (error) {
            console.error('Failed to delete translation:', error);
            Swal.fire('Error!', 'Failed to delete translation', 'error');
        }
    },

    async saveTranslation() {
        const formData = new FormData(document.getElementById('translationForm'));
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(`/api/admin/translations/${this.model}/${this.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                Swal.fire('Success!', 'Translation saved successfully', 'success');
                $('#translationModal').modal('hide');
                this.loadTranslations();
                this.loadAvailableLanguages();
                this.resetForm();
            } else {
                Swal.fire('Error!', result.message, 'error');
            }
        } catch (error) {
            console.error('Failed to save translation:', error);
            Swal.fire('Error!', 'Failed to save translation', 'error');
        }
    },

    resetForm() {
        document.getElementById('translationForm').reset();
        document.getElementById('languageSelect').disabled = false;
        document.getElementById('translationId').value = '';
        document.getElementById('translationLocale').value = '';
    }
};

function openTranslationModal() {
    translationManager.resetForm();
    $('#translationModal').modal('show');
}

function saveTranslation() {
    translationManager.saveTranslation();
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    translationManager.init();
});
</script>
