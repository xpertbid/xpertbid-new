@extends('admin.layouts.app')

@section('title', 'Multi-Language Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-globe"></i> Multi-Language Dashboard
        </h1>
        <div>
            <!-- Language Switcher -->
            <div class="dropdown d-inline-block me-2">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-language"></i> <span id="currentLanguage">English</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('en')">
                        <img src="/images/flags/en.png" width="20" height="15" class="me-2"> English
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('es')">
                        <img src="/images/flags/es.png" width="20" height="15" class="me-2"> Español
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('fr')">
                        <img src="/images/flags/fr.png" width="20" height="15" class="me-2"> Français
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('de')">
                        <img src="/images/flags/de.png" width="20" height="15" class="me-2"> Deutsch
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('ar')">
                        <img src="/images/flags/ar.png" width="20" height="15" class="me-2"> العربية
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('zh')">
                        <img src="/images/flags/zh.png" width="20" height="15" class="me-2"> 中文
                    </a></li>
                </ul>
            </div>
            
            <!-- Currency Switcher -->
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-success dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-dollar-sign"></i> <span id="currentCurrency">USD</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="switchCurrency('USD')">
                        <i class="fas fa-dollar-sign me-2"></i> USD - US Dollar
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchCurrency('EUR')">
                        <i class="fas fa-euro-sign me-2"></i> EUR - Euro
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchCurrency('GBP')">
                        <i class="fas fa-pound-sign me-2"></i> GBP - British Pound
                    </a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchCurrency('JPY')">
                        <i class="fas fa-yen-sign me-2"></i> JPY - Japanese Yen
                    </a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                <span data-translate="products">Products</span>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalProducts">{{ $stats['products'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Properties -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                <span data-translate="properties">Properties</span>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalProperties">{{ $stats['properties'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vehicles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                <span data-translate="vehicles">Vehicles</span>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalVehicles">{{ $stats['vehicles'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Auctions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                <span data-translate="auctions">Auctions</span>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalAuctions">{{ $stats['auctions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gavel fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Translation Status Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe"></i> <span data-translate="translation_status">Translation Status</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary" id="productsTranslated">0</h4>
                                <p class="text-muted"><span data-translate="products_translated">Products Translated</span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success" id="propertiesTranslated">0</h4>
                                <p class="text-muted"><span data-translate="properties_translated">Properties Translated</span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info" id="vehiclesTranslated">0</h4>
                                <p class="text-muted"><span data-translate="vehicles_translated">Vehicles Translated</span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning" id="auctionsTranslated">0</h4>
                                <p class="text-muted"><span data-translate="auctions_translated">Auctions Translated</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Items -->
    <div class="row">
        <!-- Recent Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <span data-translate="recent_products">Recent Products</span>
                    </h6>
                    <a href="/admin/products" class="btn btn-sm btn-primary">
                        <span data-translate="view_all">View All</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><span data-translate="name">Name</span></th>
                                    <th><span data-translate="type">Type</span></th>
                                    <th><span data-translate="status">Status</span></th>
                                    <th><span data-translate="translations">Translations</span></th>
                                </tr>
                            </thead>
                            <tbody id="recentProductsTable">
                                @foreach($recentData['products'] as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($product->product_type ?? 'simple') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary" id="productTranslations{{ $product->id }}">0</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Properties -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">
                        <span data-translate="recent_properties">Recent Properties</span>
                    </h6>
                    <a href="/admin/properties" class="btn btn-sm btn-success">
                        <span data-translate="view_all">View All</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><span data-translate="title">Title</span></th>
                                    <th><span data-translate="type">Type</span></th>
                                    <th><span data-translate="status">Status</span></th>
                                    <th><span data-translate="translations">Translations</span></th>
                                </tr>
                            </thead>
                            <tbody id="recentPropertiesTable">
                                @foreach($recentData['properties'] as $property)
                                <tr>
                                    <td>{{ $property->title }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($property->property_type ?? 'house') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->property_status === 'available' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($property->property_status ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success" id="propertyTranslations{{ $property->id }}">0</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Vehicles & Auctions -->
    <div class="row">
        <!-- Recent Vehicles -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">
                        <span data-translate="recent_vehicles">Recent Vehicles</span>
                    </h6>
                    <a href="/admin/vehicles" class="btn btn-sm btn-info">
                        <span data-translate="view_all">View All</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><span data-translate="title">Title</span></th>
                                    <th><span data-translate="make">Make</span></th>
                                    <th><span data-translate="year">Year</span></th>
                                    <th><span data-translate="translations">Translations</span></th>
                                </tr>
                            </thead>
                            <tbody id="recentVehiclesTable">
                                @foreach($recentData['vehicles'] as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->title }}</td>
                                    <td>{{ $vehicle->make }}</td>
                                    <td>{{ $vehicle->year }}</td>
                                    <td>
                                        <span class="badge bg-info" id="vehicleTranslations{{ $vehicle->id }}">0</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Auctions -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <span data-translate="recent_auctions">Recent Auctions</span>
                    </h6>
                    <a href="/admin/auctions" class="btn btn-sm btn-warning">
                        <span data-translate="view_all">View All</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><span data-translate="title">Title</span></th>
                                    <th><span data-translate="type">Type</span></th>
                                    <th><span data-translate="status">Status</span></th>
                                    <th><span data-translate="translations">Translations</span></th>
                                </tr>
                            </thead>
                            <tbody id="recentAuctionsTable">
                                @foreach($recentData['auctions'] as $auction)
                                <tr>
                                    <td>{{ $auction->title }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($auction->auction_type ?? 'english') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $auction->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($auction->status ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning" id="auctionTranslations{{ $auction->id }}">0</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentLanguage = 'en';
let currentCurrency = 'USD';

// Translation data
const translations = {
    en: {
        products: 'Products',
        properties: 'Properties',
        vehicles: 'Vehicles',
        auctions: 'Auctions',
        translation_status: 'Translation Status',
        products_translated: 'Products Translated',
        properties_translated: 'Properties Translated',
        vehicles_translated: 'Vehicles Translated',
        auctions_translated: 'Auctions Translated',
        recent_products: 'Recent Products',
        recent_properties: 'Recent Properties',
        recent_vehicles: 'Recent Vehicles',
        recent_auctions: 'Recent Auctions',
        view_all: 'View All',
        name: 'Name',
        title: 'Title',
        type: 'Type',
        status: 'Status',
        translations: 'Translations',
        make: 'Make',
        year: 'Year'
    },
    es: {
        products: 'Productos',
        properties: 'Propiedades',
        vehicles: 'Vehículos',
        auctions: 'Subastas',
        translation_status: 'Estado de Traducción',
        products_translated: 'Productos Traducidos',
        properties_translated: 'Propiedades Traducidas',
        vehicles_translated: 'Vehículos Traducidos',
        auctions_translated: 'Subastas Traducidas',
        recent_products: 'Productos Recientes',
        recent_properties: 'Propiedades Recientes',
        recent_vehicles: 'Vehículos Recientes',
        recent_auctions: 'Subastas Recientes',
        view_all: 'Ver Todo',
        name: 'Nombre',
        title: 'Título',
        type: 'Tipo',
        status: 'Estado',
        translations: 'Traducciones',
        make: 'Marca',
        year: 'Año'
    },
    fr: {
        products: 'Produits',
        properties: 'Propriétés',
        vehicles: 'Véhicules',
        auctions: 'Enchères',
        translation_status: 'Statut de Traduction',
        products_translated: 'Produits Traduits',
        properties_translated: 'Propriétés Traduites',
        vehicles_translated: 'Véhicules Traduits',
        auctions_translated: 'Enchères Traduites',
        recent_products: 'Produits Récents',
        recent_properties: 'Propriétés Récentes',
        recent_vehicles: 'Véhicules Récents',
        recent_auctions: 'Enchères Récentes',
        view_all: 'Voir Tout',
        name: 'Nom',
        title: 'Titre',
        type: 'Type',
        status: 'Statut',
        translations: 'Traductions',
        make: 'Marque',
        year: 'Année'
    },
    de: {
        products: 'Produkte',
        properties: 'Immobilien',
        vehicles: 'Fahrzeuge',
        auctions: 'Auktionen',
        translation_status: 'Übersetzungsstatus',
        products_translated: 'Übersetzte Produkte',
        properties_translated: 'Übersetzte Immobilien',
        vehicles_translated: 'Übersetzte Fahrzeuge',
        auctions_translated: 'Übersetzte Auktionen',
        recent_products: 'Aktuelle Produkte',
        recent_properties: 'Aktuelle Immobilien',
        recent_vehicles: 'Aktuelle Fahrzeuge',
        recent_auctions: 'Aktuelle Auktionen',
        view_all: 'Alle Anzeigen',
        name: 'Name',
        title: 'Titel',
        type: 'Typ',
        status: 'Status',
        translations: 'Übersetzungen',
        make: 'Marke',
        year: 'Jahr'
    },
    ar: {
        products: 'المنتجات',
        properties: 'العقارات',
        vehicles: 'المركبات',
        auctions: 'المزادات',
        translation_status: 'حالة الترجمة',
        products_translated: 'المنتجات المترجمة',
        properties_translated: 'العقارات المترجمة',
        vehicles_translated: 'المركبات المترجمة',
        auctions_translated: 'المزادات المترجمة',
        recent_products: 'المنتجات الحديثة',
        recent_properties: 'العقارات الحديثة',
        recent_vehicles: 'المركبات الحديثة',
        recent_auctions: 'المزادات الحديثة',
        view_all: 'عرض الكل',
        name: 'الاسم',
        title: 'العنوان',
        type: 'النوع',
        status: 'الحالة',
        translations: 'الترجمات',
        make: 'الماركة',
        year: 'السنة'
    },
    zh: {
        products: '产品',
        properties: '房产',
        vehicles: '车辆',
        auctions: '拍卖',
        translation_status: '翻译状态',
        products_translated: '已翻译产品',
        properties_translated: '已翻译房产',
        vehicles_translated: '已翻译车辆',
        auctions_translated: '已翻译拍卖',
        recent_products: '最近产品',
        recent_properties: '最近房产',
        recent_vehicles: '最近车辆',
        recent_auctions: '最近拍卖',
        view_all: '查看全部',
        name: '名称',
        title: '标题',
        type: '类型',
        status: '状态',
        translations: '翻译',
        make: '品牌',
        year: '年份'
    }
};

$(document).ready(function() {
    // Load saved preferences
    loadPreferences();
    
    // Load translation counts
    loadTranslationCounts();
    
    // Apply current language
    applyLanguage(currentLanguage);
});

function loadPreferences() {
    const savedLanguage = localStorage.getItem('xpertbid_language');
    const savedCurrency = localStorage.getItem('xpertbid_currency');
    
    if (savedLanguage) currentLanguage = savedLanguage;
    if (savedCurrency) currentCurrency = savedCurrency;
}

function switchLanguage(languageCode) {
    currentLanguage = languageCode;
    localStorage.setItem('xpertbid_language', languageCode);
    applyLanguage(languageCode);
}

function switchCurrency(currencyCode) {
    currentCurrency = currencyCode;
    localStorage.setItem('xpertbid_currency', currencyCode);
    applyCurrency(currencyCode);
}

function applyLanguage(languageCode) {
    // Update HTML lang attribute
    document.documentElement.lang = languageCode;
    
    // Update page direction for RTL languages
    const isRTL = ['ar'].includes(languageCode);
    document.documentElement.dir = isRTL ? 'rtl' : 'ltr';
    
    // Update current language display
    const languageNames = {
        'en': 'English',
        'es': 'Español',
        'fr': 'Français',
        'de': 'Deutsch',
        'ar': 'العربية',
        'zh': '中文'
    };
    document.getElementById('currentLanguage').textContent = languageNames[languageCode];
    
    // Apply translations
    const translationData = translations[languageCode] || translations.en;
    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translationData[key]) {
            element.textContent = translationData[key];
        }
    });
}

function applyCurrency(currencyCode) {
    // Update current currency display
    document.getElementById('currentCurrency').textContent = currencyCode;
    
    // Update price displays
    updatePriceDisplays();
}

function updatePriceDisplays() {
    // This would typically update all price displays on the page
    // For now, just log the currency change
    console.log('Currency changed to:', currentCurrency);
}

function loadTranslationCounts() {
    // Load translation counts for each item
    loadItemTranslationCounts('product', 'productTranslations');
    loadItemTranslationCounts('property', 'propertyTranslations');
    loadItemTranslationCounts('vehicle', 'vehicleTranslations');
    loadItemTranslationCounts('auction', 'auctionTranslations');
}

function loadItemTranslationCounts(model, prefix) {
    // This would typically load translation counts from the API
    // For now, set random counts for demonstration
    document.querySelectorAll(`[id^="${prefix}"]`).forEach(element => {
        const id = element.id.replace(prefix, '');
        const count = Math.floor(Math.random() * 6); // Random count 0-5
        element.textContent = count;
        element.className = `badge bg-${count > 0 ? 'success' : 'secondary'}`;
    });
}
</script>
@endsection
