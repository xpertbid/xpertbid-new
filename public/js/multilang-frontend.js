/**
 * Multi-Language Frontend Component for XpertBid
 * Handles frontend display of multi-language content
 */

class MultiLangFrontend {
    constructor(options = {}) {
        this.currentLanguage = options.currentLanguage || 'en';
        this.currentCurrency = options.currentCurrency || 'USD';
        this.apiBaseUrl = options.apiBaseUrl || '/api/admin';
        this.onLanguageChange = options.onLanguageChange || null;
        this.onCurrencyChange = options.onCurrencyChange || null;
        
        this.init();
    }

    init() {
        // Load saved preferences
        this.loadPreferences();
        
        // Apply current language and currency
        this.applyLanguage(this.currentLanguage);
        this.applyCurrency(this.currentCurrency);
        
        // Initialize language and currency switchers
        this.initLanguageSwitcher();
        this.initCurrencySwitcher();
    }

    loadPreferences() {
        const savedLanguage = localStorage.getItem('xpertbid_language');
        const savedCurrency = localStorage.getItem('xpertbid_currency');
        
        if (savedLanguage) this.currentLanguage = savedLanguage;
        if (savedCurrency) this.currentCurrency = savedCurrency;
    }

    applyLanguage(languageCode) {
        // Update HTML lang attribute
        document.documentElement.lang = languageCode;
        
        // Update page direction for RTL languages
        const isRTL = this.isRTLLanguage(languageCode);
        document.documentElement.dir = isRTL ? 'rtl' : 'ltr';
        
        // Update body class for styling
        document.body.classList.remove('rtl', 'ltr');
        document.body.classList.add(isRTL ? 'rtl' : 'ltr');
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { languageCode, isRTL }
        }));
        
        // Callback
        if (this.onLanguageChange) {
            this.onLanguageChange(languageCode);
        }
    }

    applyCurrency(currencyCode) {
        // Update currency meta tag
        const metaCurrency = document.querySelector('meta[name="currency"]');
        if (metaCurrency) {
            metaCurrency.setAttribute('content', currencyCode);
        }
        
        // Update all price displays
        this.updatePriceDisplays();
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('currencyChanged', {
            detail: { currencyCode }
        }));
        
        // Callback
        if (this.onCurrencyChange) {
            this.onCurrencyChange(currencyCode);
        }
    }

    initLanguageSwitcher() {
        const switcher = document.getElementById('language-switcher');
        if (!switcher) return;

        // Load available languages
        this.loadLanguages().then(languages => {
            this.renderLanguageSwitcher(languages);
        });
    }

    initCurrencySwitcher() {
        const switcher = document.getElementById('currency-switcher');
        if (!switcher) return;

        // Load available currencies
        this.loadCurrencies().then(currencies => {
            this.renderCurrencySwitcher(currencies);
        });
    }

    async loadLanguages() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/languages/active/list`);
            const result = await response.json();
            return result.success ? result.data : [];
        } catch (error) {
            console.error('Failed to load languages:', error);
            return [];
        }
    }

    async loadCurrencies() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/currencies/active/list`);
            const result = await response.json();
            return result.success ? result.data : [];
        } catch (error) {
            console.error('Failed to load currencies:', error);
            return [];
        }
    }

    renderLanguageSwitcher(languages) {
        const container = document.getElementById('language-switcher');
        const currentLang = languages.find(lang => lang.code === this.currentLanguage);
        
        container.innerHTML = `
            <div class="language-switcher dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    ${currentLang ? `<img src="${currentLang.flag_url}" width="20" height="15" class="me-2">` : ''}
                    ${currentLang ? currentLang.native_name : 'Language'}
                </button>
                <ul class="dropdown-menu">
                    ${languages.map(lang => `
                        <li>
                            <a class="dropdown-item ${lang.code === this.currentLanguage ? 'active' : ''}" 
                               href="#" onclick="multilangFrontend.switchLanguage('${lang.code}')">
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

    renderCurrencySwitcher(currencies) {
        const container = document.getElementById('currency-switcher');
        const currentCurrency = currencies.find(currency => currency.code === this.currentCurrency);
        
        container.innerHTML = `
            <div class="currency-switcher dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    ${currentCurrency ? `<img src="${currentCurrency.icon_url}" width="20" height="20" class="me-2">` : ''}
                    ${currentCurrency ? currentCurrency.code : 'Currency'}
                </button>
                <ul class="dropdown-menu">
                    ${currencies.map(currency => `
                        <li>
                            <a class="dropdown-item ${currency.code === this.currentCurrency ? 'active' : ''}" 
                               href="#" onclick="multilangFrontend.switchCurrency('${currency.code}')">
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

    switchLanguage(languageCode) {
        if (languageCode === this.currentLanguage) return;
        
        this.currentLanguage = languageCode;
        localStorage.setItem('xpertbid_language', languageCode);
        
        this.applyLanguage(languageCode);
        
        // Reload page to apply new language
        window.location.reload();
    }

    switchCurrency(currencyCode) {
        if (currencyCode === this.currentCurrency) return;
        
        this.currentCurrency = currencyCode;
        localStorage.setItem('xpertbid_currency', currencyCode);
        
        this.applyCurrency(currencyCode);
    }

    updatePriceDisplays() {
        const priceElements = document.querySelectorAll('[data-price]');
        priceElements.forEach(element => {
            const originalPrice = parseFloat(element.dataset.price);
            const convertedPrice = this.convertPrice(originalPrice);
            element.textContent = this.formatPrice(convertedPrice);
        });
    }

    convertPrice(amount) {
        // This would typically use the currency conversion API
        // For now, return the amount as-is
        return amount;
    }

    formatPrice(amount) {
        // This would typically use the currency formatting API
        // For now, return basic formatting
        return new Intl.NumberFormat(this.currentLanguage, {
            style: 'currency',
            currency: this.currentCurrency
        }).format(amount);
    }

    isRTLLanguage(languageCode) {
        const rtlLanguages = ['ar', 'he', 'fa', 'ur'];
        return rtlLanguages.includes(languageCode);
    }

    // Product display methods
    async loadProduct(productId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/products/${productId}?locale=${this.currentLanguage}`);
            const result = await response.json();
            return result.success ? result.data : null;
        } catch (error) {
            console.error('Failed to load product:', error);
            return null;
        }
    }

    async loadProperty(propertyId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/properties/${propertyId}?locale=${this.currentLanguage}`);
            const result = await response.json();
            return result.success ? result.data : null;
        } catch (error) {
            console.error('Failed to load property:', error);
            return null;
        }
    }

    async loadVehicle(vehicleId) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/vehicles/${vehicleId}?locale=${this.currentLanguage}`);
            const result = await response.json();
            return result.success ? result.data : null;
        } catch (error) {
            console.error('Failed to load vehicle:', error);
            return null;
        }
    }

    // Render product card with translations
    renderProductCard(product) {
        const translation = product.translation || {};
        const name = translation.name || product.name;
        const description = translation.description || product.description;
        const price = this.formatPrice(product.price);
        
        return `
            <div class="product-card" data-product-id="${product.id}">
                <div class="product-image">
                    <img src="${product.thumbnail_image || '/images/no-image.png'}" alt="${name}">
                </div>
                <div class="product-info">
                    <h3 class="product-name">${name}</h3>
                    <p class="product-description">${description ? description.substring(0, 100) + '...' : ''}</p>
                    <div class="product-price">${price}</div>
                    <div class="product-actions">
                        <button class="btn btn-primary" onclick="viewProduct(${product.id})">View Details</button>
                    </div>
                </div>
            </div>
        `;
    }

    // Render property card with translations
    renderPropertyCard(property) {
        const translation = property.translation || {};
        const title = translation.title || property.title;
        const description = translation.description || property.description;
        const price = this.formatPrice(property.price);
        
        return `
            <div class="property-card" data-property-id="${property.id}">
                <div class="property-image">
                    <img src="${property.images?.[0] || '/images/no-image.png'}" alt="${title}">
                </div>
                <div class="property-info">
                    <h3 class="property-title">${title}</h3>
                    <p class="property-description">${description ? description.substring(0, 100) + '...' : ''}</p>
                    <div class="property-details">
                        <span class="bedrooms">${property.bedrooms} Bedrooms</span>
                        <span class="bathrooms">${property.bathrooms} Bathrooms</span>
                        <span class="area">${property.area_sqft} sq ft</span>
                    </div>
                    <div class="property-price">${price}</div>
                    <div class="property-actions">
                        <button class="btn btn-primary" onclick="viewProperty(${property.id})">View Details</button>
                    </div>
                </div>
            </div>
        `;
    }

    // Render vehicle card with translations
    renderVehicleCard(vehicle) {
        const translation = vehicle.translation || {};
        const title = translation.title || vehicle.title;
        const description = translation.description || vehicle.description;
        const price = this.formatPrice(vehicle.price);
        
        return `
            <div class="vehicle-card" data-vehicle-id="${vehicle.id}">
                <div class="vehicle-image">
                    <img src="${vehicle.images?.[0] || '/images/no-image.png'}" alt="${title}">
                </div>
                <div class="vehicle-info">
                    <h3 class="vehicle-title">${title}</h3>
                    <p class="vehicle-description">${description ? description.substring(0, 100) + '...' : ''}</p>
                    <div class="vehicle-details">
                        <span class="year">${vehicle.year}</span>
                        <span class="mileage">${vehicle.mileage} ${vehicle.mileage_unit}</span>
                        <span class="fuel-type">${vehicle.fuel_type}</span>
                    </div>
                    <div class="vehicle-price">${price}</div>
                    <div class="vehicle-actions">
                        <button class="btn btn-primary" onclick="viewVehicle(${vehicle.id})">View Details</button>
                    </div>
                </div>
            </div>
        `;
    }
}

// Global instance
let multilangFrontend = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    multilangFrontend = new MultiLangFrontend({
        currentLanguage: 'en',
        currentCurrency: 'USD',
        onLanguageChange: (languageCode) => {
            console.log('Language changed to:', languageCode);
        },
        onCurrencyChange: (currencyCode) => {
            console.log('Currency changed to:', currencyCode);
        }
    });
});

// Global functions for product viewing
function viewProduct(productId) {
    window.location.href = `/products/${productId}`;
}

function viewProperty(propertyId) {
    window.location.href = `/properties/${propertyId}`;
}

function viewVehicle(vehicleId) {
    window.location.href = `/vehicles/${vehicleId}`;
}
