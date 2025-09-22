@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here's what's happening with your business today.</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-period="today">Today</button>
                <button type="button" class="btn btn-outline-primary" data-period="week">This Week</button>
                <button type="button" class="btn btn-outline-primary" data-period="month">This Month</button>
                <button type="button" class="btn btn-outline-primary" data-period="year">This Year</button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="totalRevenue">${{ number_format($stats['total_value'] ?? 0, 0) }}</h2>
                        <h6>Total Revenue</h6>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +12.5%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="totalOrders">{{ $stats['total_products'] + $stats['total_auctions'] + $stats['total_properties'] + $stats['total_vehicles'] }}</h2>
                        <h6>Total Items</h6>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +8.2%
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
                        <h2 id="totalUsers">{{ $stats['total_users'] ?? 0 }}</h2>
                        <h6>Total Users</h6>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i> +15.3%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h2 id="conversionRate">3.2%</h2>
                        <h6>Conversion Rate</h6>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i> -2.1%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-area me-2"></i>Revenue Analytics
                        </h5>
                        <div class="chart-controls">
                            <select class="form-select form-select-sm" id="revenueChartType">
                                <option value="line">Line Chart</option>
                                <option value="bar">Bar Chart</option>
                                <option value="area">Area Chart</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales Distribution -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Sales Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="row mb-4">
        <!-- User Growth Chart -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>User Growth
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card chart-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Top Performing Products
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="topProductsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row - Recent Activity & Quick Stats -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    @if($activity['type'] === 'product')
                                        <i class="fas fa-box"></i>
                                    @elseif($activity['type'] === 'auction')
                                        <i class="fas fa-gavel"></i>
                                    @elseif($activity['type'] === 'property')
                                        <i class="fas fa-home"></i>
                                    @elseif($activity['type'] === 'vehicle')
                                        <i class="fas fa-car"></i>
                                    @else
                                        <i class="fas fa-circle"></i>
                                    @endif
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{{ $activity['title'] }}</div>
                                    <div class="activity-desc">{{ $activity['description'] }}</div>
                                    <div class="activity-time">{{ $activity['created_at']->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="activity-item">
                                <div class="activity-content text-center text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>No recent activities found</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-stats">
                        <div class="quick-stat-item">
                            <div class="quick-stat-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">12,456</div>
                                <div class="quick-stat-label">Page Views Today</div>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="quick-stat-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">89</div>
                                <div class="quick-stat-label">Orders Today</div>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="quick-stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">4.8</div>
                                <div class="quick-stat-label">Average Rating</div>
                            </div>
                        </div>
                        <div class="quick-stat-item">
                            <div class="quick-stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="quick-stat-content">
                                <div class="quick-stat-number">2.3s</div>
                                <div class="quick-stat-label">Avg. Load Time</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Chart Cards */
.chart-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.chart-card:hover {
    box-shadow: var(--shadow-hover);
}

.chart-card .card-header {
    background: var(--white);
    border-bottom: 1px solid var(--border-color);
    padding: 20px 24px;
    font-weight: 600;
    color: var(--text-dark);
}

.chart-card .card-body {
    padding: 24px;
}

.chart-controls .form-select {
    border-radius: var(--border-radius);
    border: 2px solid var(--border-color);
    font-size: 12px;
    padding: 6px 12px;
}

/* Period Buttons */
.btn-group .btn {
    border-radius: var(--border-radius);
    font-size: 12px;
    padding: 8px 16px;
    font-weight: 500;
}

.btn-group .btn.active {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white);
}

/* Activity Feed */
.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid var(--border-color);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 16px;
    margin-right: 16px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.activity-desc {
    color: var(--text-light);
    font-size: 14px;
    margin-bottom: 4px;
}

.activity-time {
    color: var(--text-light);
    font-size: 12px;
}

/* Quick Stats */
.quick-stats {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.quick-stat-item {
    display: flex;
    align-items: center;
    padding: 16px;
    background: rgba(67, 172, 233, 0.04);
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.quick-stat-item:hover {
    background: rgba(67, 172, 233, 0.08);
    transform: translateX(4px);
}

.quick-stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 20px;
    margin-right: 16px;
}

.quick-stat-content {
    flex: 1;
}

.quick-stat-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.quick-stat-label {
    font-size: 14px;
    color: var(--text-light);
    font-weight: 500;
}

/* Stat Change Indicators */
.stat-change {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
}

.stat-change.positive {
    color: #28a745;
}

.stat-change.negative {
    color: #dc3545;
}

/* Custom Scrollbar */
.activity-feed::-webkit-scrollbar {
    width: 6px;
}

.activity-feed::-webkit-scrollbar-track {
    background: transparent;
}

.activity-feed::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.activity-feed::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: var(--border-radius);
        margin-bottom: 4px;
    }
    
    .chart-card .card-body {
        padding: 16px;
    }
    
    .quick-stat-item {
        padding: 12px;
    }
    
    .quick-stat-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .quick-stat-number {
        font-size: 20px;
    }
}
</style>

<script>
// Chart.js Configuration
Chart.defaults.font.family = "'Inter', 'Poppins', sans-serif";
Chart.defaults.color = '#6c757d';

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue',
            data: [12000, 15000, 18000, 22000, 19000, 25000, 28000, 24000, 30000, 32000, 29000, 35000],
            borderColor: '#43ACE9',
            backgroundColor: 'rgba(67, 172, 233, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#43ACE9',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                },
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Sales Distribution Chart
const salesDistributionCtx = document.getElementById('salesDistributionChart').getContext('2d');
const salesDistributionChart = new Chart(salesDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Electronics', 'Clothing', 'Home & Garden', 'Sports', 'Books', 'Other'],
        datasets: [{
            data: [35, 25, 15, 10, 8, 7],
            backgroundColor: [
                '#43ACE9',
                '#5ba3d4',
                '#28a745',
                '#ffc107',
                '#dc3545',
                '#6c757d'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
const userGrowthChart = new Chart(userGrowthCtx, {
    type: 'bar',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
            label: 'New Users',
            data: [120, 150, 180, 220],
            backgroundColor: 'rgba(67, 172, 233, 0.8)',
            borderColor: '#43ACE9',
            borderWidth: 1,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Top Products Chart
const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
const topProductsChart = new Chart(topProductsCtx, {
    type: 'bar',
    data: {
        labels: ['iPhone 15 Pro', 'MacBook Pro M3', 'Nike Air Max', 'Samsung Galaxy', 'iPad Pro'],
        datasets: [{
            label: 'Sales',
            data: [450, 320, 280, 250, 200],
            backgroundColor: [
                'rgba(67, 172, 233, 0.8)',
                'rgba(91, 163, 212, 0.8)',
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ],
            borderColor: [
                '#43ACE9',
                '#5ba3d4',
                '#28a745',
                '#ffc107',
                '#dc3545'
            ],
            borderWidth: 1,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Period Button Functionality
document.addEventListener('DOMContentLoaded', function() {
    const periodButtons = document.querySelectorAll('[data-period]');
    
    periodButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            periodButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update charts based on selected period
            updateChartsForPeriod(this.dataset.period);
        });
    });
});

function updateChartsForPeriod(period) {
    // This function would update chart data based on selected period
    // For demo purposes, we'll just log the period
    console.log('Updating charts for period:', period);
    
    // In a real application, you would:
    // 1. Make an AJAX request to get data for the selected period
    // 2. Update all chart datasets with new data
    // 3. Update the stat cards with new values
}

// Chart Type Change Functionality
document.getElementById('revenueChartType').addEventListener('change', function() {
    const chartType = this.value;
    revenueChart.config.type = chartType;
    revenueChart.update();
});
</script>
@endsection
