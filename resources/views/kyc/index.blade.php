<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KYC Verification - XpertBid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #43ACE9;
            --secondary-color: #606060;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
            --white: #ffffff;
            --border-radius: 12px;
            --shadow: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .kyc-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .kyc-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .kyc-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .kyc-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .kyc-header p {
            color: var(--secondary-color);
            margin-bottom: 0;
        }

        .status-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.2);
            color: #856404;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.2);
            color: #155724;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-rejected {
            background: rgba(220, 53, 69, 0.2);
            color: #721c24;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .status-under-review {
            background: rgba(23, 162, 184, 0.2);
            color: #0c5460;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .kyc-type-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .kyc-type-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            text-decoration: none;
            color: inherit;
        }

        .kyc-type-card.selected {
            border-color: var(--primary-color);
            background: rgba(67, 172, 233, 0.05);
        }

        .kyc-type-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #5ba3d4);
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .info-card {
            background: rgba(23, 162, 184, 0.1);
            border: 1px solid rgba(23, 162, 184, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .warning-card {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .success-card {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="kyc-container">
        <div class="kyc-card">
            <div class="kyc-header">
                <h1><i class="fas fa-id-card me-2"></i>KYC Verification</h1>
                <p>Complete your Know Your Customer verification to access all platform features</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($kycDocument)
                <!-- Existing KYC Document -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="info-card">
                            <h5><i class="fas fa-file-alt me-2"></i>Your KYC Status</h5>
                            <div class="d-flex align-items-center mb-3">
                                <span class="status-badge status-{{ $kycDocument->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $kycDocument->status)) }}
                                </span>
                                <span class="ms-3 text-muted">{{ $kycDocument->kyc_type_text }}</span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Submitted:</strong> {{ $kycDocument->created_at->format('M d, Y') }}</p>
                                    <p><strong>Documents:</strong> {{ $kycDocument->getDocumentCount() }} files</p>
                                </div>
                                <div class="col-md-6">
                                    @if($kycDocument->reviewed_at)
                                        <p><strong>Last Reviewed:</strong> {{ $kycDocument->reviewed_at->format('M d, Y') }}</p>
                                    @endif
                                    @if($kycDocument->rejection_reason)
                                        <p><strong>Rejection Reason:</strong> {{ $kycDocument->rejection_reason }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($kycDocument->status === 'rejected')
                            <div class="warning-card">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>KYC Rejected</h6>
                                <p class="mb-2">{{ $kycDocument->rejection_reason }}</p>
                                <p class="mb-0">Please review the feedback and resubmit your KYC document.</p>
                            </div>
                        @elseif($kycDocument->status === 'approved')
                            <div class="success-card">
                                <h6><i class="fas fa-check-circle me-2"></i>KYC Approved</h6>
                                <p class="mb-0">Congratulations! Your KYC verification has been approved. You now have full access to all platform features.</p>
                            </div>
                        @elseif($kycDocument->status === 'pending')
                            <div class="info-card">
                                <h6><i class="fas fa-clock me-2"></i>Under Review</h6>
                                <p class="mb-0">Your KYC document is currently being reviewed by our team. You will be notified once the review is complete.</p>
                            </div>
                        @elseif($kycDocument->status === 'under_review')
                            <div class="info-card">
                                <h6><i class="fas fa-eye me-2"></i>Under Detailed Review</h6>
                                <p class="mb-0">Your KYC document is under detailed review. This may take additional time.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="d-grid gap-2">
                            <a href="{{ route('kyc.show', $kycDocument) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                            
                            @if(in_array($kycDocument->status, ['pending', 'rejected']))
                                <a href="{{ route('kyc.edit', $kycDocument) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit KYC
                                </a>
                            @endif
                            
                            <a href="{{ route('kyc.show', $kycDocument) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-upload me-2"></i>Manage Documents
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- No KYC Document - Show Type Selection -->
                <div class="row">
                    <div class="col-12">
                        <div class="info-card">
                            <h5><i class="fas fa-info-circle me-2"></i>Choose Your Account Type</h5>
                            <p class="mb-0">Select the type of account you want to create. This will determine the required verification documents.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- E-commerce User -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('kyc.create', ['type' => 'user']) }}" class="kyc-type-card">
                            <div class="kyc-type-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5>E-commerce User</h5>
                            <p class="text-muted mb-2">For users who want to buy products</p>
                            <small class="text-success"><strong>Required:</strong> Name, Email, Phone</small>
                        </a>
                    </div>

                    <!-- Vendor/Business -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('kyc.create', ['type' => 'vendor']) }}" class="kyc-type-card">
                            <div class="kyc-type-icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <h5>Vendor/Business</h5>
                            <p class="text-muted mb-2">For businesses who want to sell products</p>
                            <small class="text-success"><strong>Required:</strong> Name, Email, Phone, Business Name, NTN, Business Address</small>
                        </a>
                    </div>

                    <!-- Property Seller -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('kyc.create', ['type' => 'property']) }}" class="kyc-type-card">
                            <div class="kyc-type-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h5>Property Seller</h5>
                            <p class="text-muted mb-2">For users who want to list properties</p>
                            <small class="text-success"><strong>Required:</strong> Name, Email, Phone</small>
                        </a>
                    </div>

                    <!-- Vehicle Seller -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('kyc.create', ['type' => 'vehicle']) }}" class="kyc-type-card">
                            <div class="kyc-type-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <h5>Vehicle Seller</h5>
                            <p class="text-muted mb-2">For users who want to sell vehicles</p>
                            <small class="text-success"><strong>Required:</strong> Name, Email, Phone</small>
                        </a>
                    </div>

                    <!-- Auction Seller -->
                    <div class="col-md-6 col-lg-4 mb-3">
                        <a href="{{ route('kyc.create', ['type' => 'auction']) }}" class="kyc-type-card">
                            <div class="kyc-type-icon">
                                <i class="fas fa-gavel"></i>
                            </div>
                            <h5>Auction Seller</h5>
                            <p class="text-muted mb-2">For users who want to list auction items</p>
                            <small class="text-success"><strong>Required:</strong> Name, Email, Phone</small>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
