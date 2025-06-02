<x-layouts.backend :title="'Dashboard'">
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 bg-gradient-primary text-white">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col">
                                <h1 class="h3 mb-0 text-white">Dashboard Overview</h1>
                                <p class="mb-0 opacity-75">Selamat Datang Ini Dashboard Admin</p>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Types -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Types
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTypes }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-tags text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            System categories
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Brands -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Brands
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBrands }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Registered brands
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Blogs -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Blogs
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBlogs }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-info">
                                    <i class="fas fa-blog text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Published articles
                        </small>
                    </div>
                </div>
            </div>

            <!-- Sticky Articles -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Sticky Articles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStickyArticles }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-thumbtack text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Featured content
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Stats -->
        <div class="row g-3 mb-4">
            <!-- Total Specs -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Total Specs
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSpecs }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-secondary">
                                    <i class="fas fa-cogs text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Technical specifications
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total Vehicles -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Vehicles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVehicles }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-danger">
                                    <i class="fas fa-car text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Vehicle inventory
                        </small>
                    </div>
                </div>
            </div>

            <!-- Marketplaces -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Marketplaces
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMarketplaces }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-dark">
                                    <i class="fas fa-store text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Active marketplaces
                        </small>
                    </div>
                </div>
            </div>

            <!-- Comments -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                    Comments
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalComments }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-purple">
                                    <i class="fas fa-comments text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            User interactions
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row Stats -->
        <div class="row g-3 mb-4">
            <!-- Tips and Tricks -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-teal text-uppercase mb-1">
                                    Tips & Tricks
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTipsAndTrick }}</div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-teal">
                                    <i class="fas fa-lightbulb text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <small class="text-muted">
                            <i class="fas fa-arrow-up text-success me-1"></i>
                            Helpful guides
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS untuk styling tambahan -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .icon-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .text-purple {
            color: #6f42c1 !important;
        }
        
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        
        .text-teal {
            color: #20c997 !important;
        }
        
        .bg-teal {
            background-color: #20c997 !important;
        }
        
        .btn-outline-teal {
            color: #20c997;
            border-color: #20c997;
        }
        
        .btn-outline-teal:hover {
            color: #fff;
            background-color: #20c997;
            border-color: #20c997;
        }
        
        .text-gray-800 {
            color: #2d3748 !important;
        }
        
        .text-xs {
            font-size: 0.75rem;
        }
    </style>
</x-layouts.backend>