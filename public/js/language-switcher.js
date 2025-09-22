/**
 * Language Switcher Component for XpertBid
 * Handles language switching and localization
 */

class LanguageSwitcher {
    constructor(options = {}) {
        this.apiBaseUrl = options.apiBaseUrl || '/api/admin';
        this.currentLanguage = options.currentLanguage || 'en';
        this.defaultLanguage = options.defaultLanguage || 'en';
        this.languages = options.languages || [];
        this.onLanguageChange = options.onLanguageChange || null;
        this.storageKey = options.storageKey || 'xpertbid_language';
        
        this.init();
    }

    init() {
        // Load saved language from localStorage
        const savedLanguage = localStorage.getItem(this.storageKey);
        if (savedLanguage && savedLanguage !== this.currentLanguage) {
            this.currentLanguage = savedLanguage;
        }

        // Load available languages if not provided
        if (this.languages.length === 0) {
            this.loadLanguages();
        }

        // Apply current language
        this.applyLanguage(this.currentLanguage);
    }

    /**
     * Load available languages from API
     */
    async loadLanguages() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/languages/active/list`);
            const result = await response.json();
            
            if (result.success) {
                this.languages = result.data;
                this.renderLanguageSwitcher();
            }
        } catch (error) {
            console.error('Failed to load languages:', error);
        }
    }

    /**
     * Render language switcher UI
     */
    renderLanguageSwitcher(containerId = 'language-switcher') {
        const container = document.getElementById(containerId);
        if (!container) return;

        const currentLang = this.languages.find(lang => lang.code === this.currentLanguage);
        
        container.innerHTML = `
            <div class="language-switcher dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    ${currentLang ? `<img src="${currentLang.flag_url}" width="20" height="15" class="me-2">` : ''}
                    ${currentLang ? currentLang.native_name : 'Language'}
                </button>
                <ul class="dropdown-menu">
                    ${this.languages.map(lang => `
                        <li>
                            <a class="dropdown-item ${lang.code === this.currentLanguage ? 'active' : ''}" 
                               href="#" onclick="languageSwitcher.switchLanguage('${lang.code}')">
                                <img src="${lang.flag_url}" width="20" height="15" class="me-2">
                                ${lang.native_name}
                                ${lang.is_default ? '<span class="badge bg-primary ms-2">Default</span>' : ''}
                            </a>
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
    }

    /**
     * Switch to a different language
     */
    async switchLanguage(languageCode) {
        if (languageCode === this.currentLanguage) return;

        try {
            // Save language preference
            localStorage.setItem(this.storageKey, languageCode);
            this.currentLanguage = languageCode;

            // Apply language changes
            this.applyLanguage(languageCode);

            // Callback
            if (this.onLanguageChange) {
                this.onLanguageChange(languageCode);
            }

            // Reload page to apply new language
            window.location.reload();

        } catch (error) {
            console.error('Failed to switch language:', error);
        }
    }

    /**
     * Apply language to the page
     */
    applyLanguage(languageCode) {
        // Update HTML lang attribute
        document.documentElement.lang = languageCode;

        // Update page direction for RTL languages
        const language = this.languages.find(lang => lang.code === languageCode);
        if (language && language.is_rtl) {
            document.documentElement.dir = 'rtl';
        } else {
            document.documentElement.dir = 'ltr';
        }

        // Update meta tags
        this.updateMetaTags(languageCode);

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { languageCode, language }
        }));
    }

    /**
     * Update meta tags for SEO
     */
    updateMetaTags(languageCode) {
        // Update hreflang attributes
        const links = document.querySelectorAll('link[hreflang]');
        links.forEach(link => {
            if (link.getAttribute('hreflang') === languageCode) {
                link.setAttribute('rel', 'alternate');
            } else {
                link.removeAttribute('rel');
            }
        });
    }

    /**
     * Get current language info
     */
    getCurrentLanguage() {
        return this.languages.find(lang => lang.code === this.currentLanguage);
    }

    /**
     * Translate text using loaded translations
     */
    translate(key, params = {}) {
        // This would typically use a translation service
        // For now, return the key as fallback
        return key;
    }

    /**
     * Format currency based on current language
     */
    formatCurrency(amount, currencyCode = 'USD') {
        const language = this.getCurrentLanguage();
        if (!language) return amount;

        try {
            return new Intl.NumberFormat(language.code, {
                style: 'currency',
                currency: currencyCode
            }).format(amount);
        } catch (error) {
            return amount;
        }
    }

    /**
     * Format date based on current language
     */
    formatDate(date, options = {}) {
        const language = this.getCurrentLanguage();
        if (!language) return date;

        try {
            return new Intl.DateTimeFormat(language.code, options).format(new Date(date));
        } catch (error) {
            return date;
        }
    }

    /**
     * Format number based on current language
     */
    formatNumber(number, options = {}) {
        const language = this.getCurrentLanguage();
        if (!language) return number;

        try {
            return new Intl.NumberFormat(language.code, options).format(number);
        } catch (error) {
            return number;
        }
    }
}

/**
 * Currency Switcher Component
 */
class CurrencySwitcher {
    constructor(options = {}) {
        this.apiBaseUrl = options.apiBaseUrl || '/api/admin';
        this.currentCurrency = options.currentCurrency || 'USD';
        this.defaultCurrency = options.defaultCurrency || 'USD';
        this.currencies = options.currencies || [];
        this.onCurrencyChange = options.onCurrencyChange || null;
        this.storageKey = options.storageKey || 'xpertbid_currency';
        
        this.init();
    }

    init() {
        // Load saved currency from localStorage
        const savedCurrency = localStorage.getItem(this.storageKey);
        if (savedCurrency && savedCurrency !== this.currentCurrency) {
            this.currentCurrency = savedCurrency;
        }

        // Load available currencies if not provided
        if (this.currencies.length === 0) {
            this.loadCurrencies();
        }

        // Apply current currency
        this.applyCurrency(this.currentCurrency);
    }

    /**
     * Load available currencies from API
     */
    async loadCurrencies() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/currencies/active/list`);
            const result = await response.json();
            
            if (result.success) {
                this.currencies = result.data;
                this.renderCurrencySwitcher();
            }
        } catch (error) {
            console.error('Failed to load currencies:', error);
        }
    }

    /**
     * Render currency switcher UI
     */
    renderCurrencySwitcher(containerId = 'currency-switcher') {
        const container = document.getElementById(containerId);
        if (!container) return;

        const currentCurrency = this.currencies.find(currency => currency.code === this.currentCurrency);
        
        container.innerHTML = `
            <div class="currency-switcher dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    ${currentCurrency ? `<img src="${currentCurrency.icon_url}" width="20" height="20" class="me-2">` : ''}
                    ${currentCurrency ? currentCurrency.code : 'Currency'}
                </button>
                <ul class="dropdown-menu">
                    ${this.currencies.map(currency => `
                        <li>
                            <a class="dropdown-item ${currency.code === this.currentCurrency ? 'active' : ''}" 
                               href="#" onclick="currencySwitcher.switchCurrency('${currency.code}')">
                                <img src="${currency.icon_url}" width="20" height="20" class="me-2">
                                ${currency.code} - ${currency.name}
                                ${currency.is_default ? '<span class="badge bg-primary ms-2">Default</span>' : ''}
                            </a>
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
    }

    /**
     * Switch to a different currency
     */
    async switchCurrency(currencyCode) {
        if (currencyCode === this.currentCurrency) return;

        try {
            // Save currency preference
            localStorage.setItem(this.storageKey, currencyCode);
            this.currentCurrency = currencyCode;

            // Apply currency changes
            this.applyCurrency(currencyCode);

            // Callback
            if (this.onCurrencyChange) {
                this.onCurrencyChange(currencyCode);
            }

            // Update all price displays
            this.updatePriceDisplays();

        } catch (error) {
            console.error('Failed to switch currency:', error);
        }
    }

    /**
     * Apply currency to the page
     */
    applyCurrency(currencyCode) {
        // Update currency meta tag
        const metaCurrency = document.querySelector('meta[name="currency"]');
        if (metaCurrency) {
            metaCurrency.setAttribute('content', currencyCode);
        }

        // Trigger custom event
        window.dispatchEvent(new CustomEvent('currencyChanged', {
            detail: { currencyCode }
        }));
    }

    /**
     * Update all price displays on the page
     */
    updatePriceDisplays() {
        const priceElements = document.querySelectorAll('[data-price]');
        priceElements.forEach(element => {
            const originalPrice = parseFloat(element.dataset.price);
            const convertedPrice = this.convertPrice(originalPrice);
            element.textContent = this.formatPrice(convertedPrice);
        });
    }

    /**
     * Convert price from default currency to current currency
     */
    convertPrice(amount) {
        const currentCurrency = this.currencies.find(currency => currency.code === this.currentCurrency);
        if (!currentCurrency) return amount;

        return currentCurrency.convertFromDefault(amount);
    }

    /**
     * Format price with currency symbol
     */
    formatPrice(amount) {
        const currentCurrency = this.currencies.find(currency => currency.code === this.currentCurrency);
        if (!currentCurrency) return amount;

        return currentCurrency.formatAmount(amount);
    }

    /**
     * Get current currency info
     */
    getCurrentCurrency() {
        return this.currencies.find(currency => currency.code === this.currentCurrency);
    }
}

// Global instances for easy access
let languageSwitcher = null;
let currencySwitcher = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize language switcher
    languageSwitcher = new LanguageSwitcher({
        currentLanguage: 'en',
        onLanguageChange: (languageCode) => {
            console.log('Language changed to:', languageCode);
        }
    });

    // Initialize currency switcher
    currencySwitcher = new CurrencySwitcher({
        currentCurrency: 'USD',
        onCurrencyChange: (currencyCode) => {
            console.log('Currency changed to:', currencyCode);
        }
    });
});
