@extends('admin.layouts.app')

@section('title', 'Cache Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cache Management</h1>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" onclick="warmUpCache()">
                <i class="fas fa-fire me-1"></i> Warm Up Cache
            </button>
            <button type="button" class="btn btn-warning" onclick="clearApplicationCache()">
                <i class="fas fa-broom me-1"></i> Clear App Cache
            </button>
            <button type="button" class="btn btn-danger" onclick="clearAllCache()">
                <i class="fas fa-trash me-1"></i> Clear All Cache
            </button>
        </div>
    </div>

    <!-- Cache Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-memory"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="usedMemory">{{ $cacheStats['used_memory'] ?? 'N/A' }}</h2>
                        <h6>Used Memory</h6>
                        <div class="stat-change">
                            <i class="fas fa-server"></i> Redis Cache
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="connectedClients">{{ $cacheStats['connected_clients'] ?? 0 }}</h2>
                        <h6>Connected Clients</h6>
                        <div class="stat-change">
                            <i class="fas fa-plug"></i> Active Connections
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="hitRate">{{ $cacheStats['hit_rate'] ?? 0 }}%</h2>
                        <h6>Hit Rate</h6>
                        <div class="stat-change">
                            <i class="fas fa-chart-line"></i> Cache Efficiency
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="totalCommands">{{ $cacheStats['total_commands_processed'] ?? 0 }}</h2>
                        <h6>Total Commands</h6>
                        <div class="stat-change">
                            <i class="fas fa-terminal"></i> Processed
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Cache Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-primary text-white me-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <h5 class="card-title mb-0 text-primary">Products Cache</h5>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Keys:</span>
                            <strong id="productsTotalKeys">{{ $moduleStats['products']['total_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Keys:</span>
                            <strong id="productsActiveKeys">{{ $moduleStats['products']['active_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Size:</span>
                            <strong id="productsSize">{{ $moduleStats['products']['total_size'] ?? '0 B' }}</strong>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="clearModuleCache('products')">
                        <i class="fas fa-trash me-1"></i> Clear Cache
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-warning text-white me-3">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <h5 class="card-title mb-0 text-warning">Auctions Cache</h5>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Keys:</span>
                            <strong id="auctionsTotalKeys">{{ $moduleStats['auctions']['total_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Keys:</span>
                            <strong id="auctionsActiveKeys">{{ $moduleStats['auctions']['active_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Size:</span>
                            <strong id="auctionsSize">{{ $moduleStats['auctions']['total_size'] ?? '0 B' }}</strong>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="clearModuleCache('auctions')">
                        <i class="fas fa-trash me-1"></i> Clear Cache
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-success text-white me-3">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5 class="card-title mb-0 text-success">Properties Cache</h5>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Keys:</span>
                            <strong id="propertiesTotalKeys">{{ $moduleStats['properties']['total_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Keys:</span>
                            <strong id="propertiesActiveKeys">{{ $moduleStats['properties']['active_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Size:</span>
                            <strong id="propertiesSize">{{ $moduleStats['properties']['total_size'] ?? '0 B' }}</strong>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="clearModuleCache('properties')">
                        <i class="fas fa-trash me-1"></i> Clear Cache
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-circle bg-info text-white me-3">
                            <i class="fas fa-car"></i>
                        </div>
                        <h5 class="card-title mb-0 text-info">Vehicles Cache</h5>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Keys:</span>
                            <strong id="vehiclesTotalKeys">{{ $moduleStats['vehicles']['total_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Keys:</span>
                            <strong id="vehiclesActiveKeys">{{ $moduleStats['vehicles']['active_keys'] ?? 0 }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Size:</span>
                            <strong id="vehiclesSize">{{ $moduleStats['vehicles']['total_size'] ?? '0 B' }}</strong>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="clearModuleCache('vehicles')">
                        <i class="fas fa-trash me-1"></i> Clear Cache
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Operations -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Cache Keys Explorer
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" id="keyPattern" class="form-control" placeholder="Enter pattern (e.g., products:*, auctions:*, *)" value="*">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary w-100" onclick="searchKeys()">
                                <i class="fas fa-search me-1"></i> Search Keys
                            </button>
                        </div>
                    </div>
                    <div id="keysList" class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>TTL</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="keysTableBody">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Click "Search Keys" to load cache keys</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Redis Information
                    </h5>
                </div>
                <div class="card-body">
                    <div id="redisInfo">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading Redis information...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, #5ba3d4 100%);
    border-radius: var(--border-radius);
    padding: 28px;
    color: var(--white);
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-hover);
}

.stat-card:hover::before {
    transform: scale(1.2);
}

.stat-card h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    position: relative;
    z-index: 1;
}

.stat-card h6 {
    opacity: 0.9;
    margin-bottom: 0;
    font-weight: 500;
    position: relative;
    z-index: 1;
}

.stat-card .stat-change {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
    opacity: 0.8;
    position: relative;
    z-index: 1;
}

.card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.card:hover {
    box-shadow: var(--shadow-hover);
}

.card-header {
    background: var(--white);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
    font-weight: 600;
    color: var(--text-dark);
}

.card-body {
    padding: 24px;
}

.table th {
    background: var(--primary-color);
    color: var(--white);
    border: none;
    padding: 12px;
    font-weight: 600;
    font-size: 14px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
    border-color: var(--border-color);
}

.table tbody tr:hover {
    background-color: rgba(67, 172, 233, 0.04);
}

.btn {
    border-radius: var(--border-radius);
    font-weight: 500;
    transition: var(--transition);
}

.btn:hover {
    transform: translateY(-2px);
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}
</style>

<script>
// Cache management functions
function clearAllCache() {
    if (confirm('Are you sure you want to clear ALL cache? This action cannot be undone.')) {
        fetch('/admin/cache/clear-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error clearing cache: ' + error.message);
        });
    }
}

function clearModuleCache(module) {
    if (confirm(`Are you sure you want to clear ${module} cache?`)) {
        fetch('/admin/cache/clear-module', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ module: module })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error clearing cache: ' + error.message);
        });
    }
}

function warmUpCache() {
    fetch('/admin/cache/warm-up', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Error warming up cache: ' + error.message);
    });
}

function clearApplicationCache() {
    if (confirm('Are you sure you want to clear application cache (config, route, view)?')) {
        fetch('/admin/cache/clear-application', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error clearing application cache: ' + error.message);
        });
    }
}

function searchKeys() {
    const pattern = document.getElementById('keyPattern').value;
    const tbody = document.getElementById('keysTableBody');
    
    tbody.innerHTML = '<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>';
    
    fetch('/admin/cache/keys?pattern=' + encodeURIComponent(pattern))
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            tbody.innerHTML = '';
            if (data.data.keys.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No keys found</td></tr>';
            } else {
                data.data.keys.forEach(key => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><code>${key.key}</code></td>
                        <td>${key.ttl === -1 ? '∞' : key.ttl + 's'}</td>
                        <td><span class="badge bg-info">${key.type}</span></td>
                        <td>${formatBytes(key.size)}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteKey('${key.key}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error: ' + data.message + '</td></tr>';
        }
    })
    .catch(error => {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error: ' + error.message + '</td></tr>';
    });
}

function deleteKey(key) {
    if (confirm('Are you sure you want to delete this cache key?')) {
        fetch('/admin/cache/delete-key', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key: key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                searchKeys(); // Refresh the list
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error deleting key: ' + error.message);
        });
    }
}

function loadRedisInfo() {
    fetch('/admin/cache/redis-info')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const redisInfoDiv = document.getElementById('redisInfo');
            redisInfoDiv.innerHTML = `
                <div class="mb-3">
                    <strong>Redis Version:</strong> ${data.data.redis_version}
                </div>
                <div class="mb-3">
                    <strong>Used Memory:</strong> ${data.data.used_memory}
                </div>
                <div class="mb-3">
                    <strong>Peak Memory:</strong> ${data.data.used_memory_peak}
                </div>
                <div class="mb-3">
                    <strong>Connected Clients:</strong> ${data.data.connected_clients}
                </div>
                <div class="mb-3">
                    <strong>Commands Processed:</strong> ${data.data.total_commands_processed.toLocaleString()}
                </div>
                <div class="mb-3">
                    <strong>Uptime:</strong> ${formatUptime(data.data.uptime_in_seconds)}
                </div>
            `;
        } else {
            document.getElementById('redisInfo').innerHTML = '<div class="text-danger">Error loading Redis info: ' + data.message + '</div>';
        }
    })
    .catch(error => {
        document.getElementById('redisInfo').innerHTML = '<div class="text-danger">Error loading Redis info: ' + error.message + '</div>';
    });
}

function formatBytes(bytes, precision = 2) {
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    let i = 0;
    while (bytes > 1024 && i < units.length - 1) {
        bytes /= 1024;
        i++;
    }
    return Math.round(bytes * Math.pow(10, precision)) / Math.pow(10, precision) + ' ' + units[i];
}

function formatUptime(seconds) {
    const days = Math.floor(seconds / 86400);
    const hours = Math.floor((seconds % 86400) / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    
    if (days > 0) {
        return `${days}d ${hours}h ${minutes}m`;
    } else if (hours > 0) {
        return `${hours}h ${minutes}m`;
    } else {
        return `${minutes}m`;
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert at the top of the page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Load Redis info on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRedisInfo();
});
</script>
@endsection
