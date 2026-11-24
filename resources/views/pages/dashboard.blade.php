@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- Page Header -->
    <div class="page-header-breadcrumb d-md-flex d-block align-items-center justify-content-between ">
        <h4 class="fw-medium mb-0">Dashboard</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </div>
    <!-- Page Header Close -->

    <!-- Start::app-content -->
    <div class="main-content app-content">
        <div class="container-fluid">

             <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xxl-3  col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold mb-1">Total Clicks</h6>
                                            <h3 class="fw-semibold">12.5K</h3>
                                            <span class="d-block text-success fs-12">+0.892 <i class="ti ti-trending-up ms-1"></i></span>
                                        </div>
                                        <div id="analytics-users"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3  col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold mb-1">Total Impressions</h6>
                                            <h3 class="fw-semibold">95.3K</h3>
                                            <span class="d-block text-success fs-12">+0.892 <i class="ti ti-trending-up ms-1"></i></span>
                                        </div>
                                        <div id="analytics-visitors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3  col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold mb-1">Avg. Position</h6>
                                            <h3 class="fw-semibold">8.09%</h3>
                                            <span class="d-block text-success fs-12">+0.892 <i class="ti ti-trending-up ms-1"></i></span>
                                        </div>
                                        <div id="Bounce-Rate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3  col-sm-6">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-semibold mb-1">Store Health Score</h6>
                                            <h3 class="fw-semibold">78</h3>
                                            <span class="d-block text-success fs-12">Good <i class="ti ti-trending-up ms-1"></i></span>
                                        </div>
                                        <div id="Page-Views"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End::row-1 -->

            <div class="row">
                <div class="col-xxl-6  col-xl-12">
                    <div class="row">
                        <div class="col-xxl-12 col-xl-12">
                            <div class="card custom-card">
                                <div class="card-header  justify-content-between">
                                    <div class="card-title">Performance Overview</div>
                                    <div class="dropdown d-flex">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-primary-light btn-wave waves-effect waves-light d-flex align-items-center me-2"><i class="ri-filter-3-line me-1"></i>Filter</a>
                                        <a href="javascript:void(0);" class="btn dropdown-toggle btn-sm btn-wave waves-effect waves-light btn-primary d-flex align-items-center" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-calendar-2-line me-1"></i>This Week</a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Last Month</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Share Report</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="earnings"></div>
                                </div>
                                <hr>
                                <div class="row p-3 g-3">
                                    <div class="col-3 text-info border-end border-primary">Clicks : <strong>12,543</strong></div>
                                    <div class="col-3 text-primary border-end border-primary">Impressions : <strong>95,320</strong></div>
                                    <div class="col-3 text-success border-end border-primary">Avg. Position : <strong>8.4</strong></div>
                                    <div class="col-3 text-secondary">Avg. Page Speed : <strong>2.8s</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xxl-5 col-lg-6">
                    <div class="card custom-card  overflow-hidden">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Top Keywords</div>
                            <div class="dropdown">
                                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-outline-light btn-icons btn-sm text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">Today</a></li>
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-4">Keyword</th>
                                            <th scope="col">Clicks</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="top-selling">
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-semibold">Running Shoes</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">3,410</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">5</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-success-transparent text-success">Up</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-semibold">Sports Apparel</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">2,150</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">7</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-success-transparent text-success">Up</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-semibold">Fitness Gear</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">1,890</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">9</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-success-transparent text-success">Down</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-lg-6">
                    <div class="card custom-card  overflow-hidden">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Competitor Insights
                            </div>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn-outline-light btn btn-sm text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                                    View All<i class="ri-arrow-down-s-line align-middle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">Today</a></li>
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-primary-transparent me-2">
                                                        <i class="ri-google-fill fs-18 text-primary"></i>
                                                    </span>
                                                    <div class="fw-semibold">shopmart.com</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span>78k</span>
                                            </td>
                                            <td>
                                                <span class="d-block text-success fs-14 text-center">visits <i class="ti ti-trending-up ms-1"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-secondary-transparent me-2">
                                                        <i class="ri-safari-line fs-18 text-secondary"></i>
                                                    </span>
                                                    <div class="fw-semibold">trendstore.in</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span>65k</span>
                                            </td>
                                            <td>
                                                <span class="d-block text-success fs-14 text-center">visits <i class="ti ti-trending-up ms-1"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-success-transparent me-2">
                                                        <i class="ri-opera-fill fs-18 text-success"></i>
                                                    </span>
                                                    <div class="fw-semibold">urbanstyle.co</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span>54k</span>
                                            </td>
                                            <td>
                                                <span class="d-block text-danger fs-14 text-center">visits <i class="ti ti-trending-down ms-1"></i></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12 col-xl-6 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Issues & Alerts</div>
                        </div>
                        <div class="card-body">
                            <ul class="task-list mb-0">
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-primary"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on
                                                    <a href="javascript:void(0)" class="fw-semibold text-primary"> AngularJS Template</a></span>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-info"></i>
                                        <h6 class="fw-semibold mb-0">Task Overdue</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Petey Cruiser finished</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-info"> Integrated management</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">29 June 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-warning"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-warning"> AngularJS Template</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-success"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-success"> AngularJS Template</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-0">
                                    <div class="">
                                        <i class="task-icon bg-secondary"></i>
                                        <h6 class="fw-semibold mb-0">New Comment</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-secondary"> Project Management</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">25 Aug 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-lg-6">
                    <div class="card custom-card  overflow-hidden">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Issues & Alerts
                            </div>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn-outline-light btn btn-sm text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                                    View All<i class="ri-arrow-down-s-line align-middle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">Today</a></li>
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap">
                                    <tbody>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-primary-transparent me-2">
                                                        <i class="ri-google-fill fs-18 text-primary"></i>
                                                    </span>
                                                    <div class="fw-semibold">5 Broken Links</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block text-success fs-14 text-center">View <i class="ti ti-trending-up ms-1"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-secondary-transparent me-2">
                                                        <i class="ri-safari-line fs-18 text-secondary"></i>
                                                    </span>
                                                    <div class="fw-semibold">3 Duplicate Content</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block text-success fs-14 text-center">View <i class="ti ti-trending-up ms-1"></i></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-rounded avatar-sm p-2 bg-success-transparent me-2">
                                                        <i class="ri-opera-fill fs-18 text-success"></i>
                                                    </span>
                                                    <div class="fw-semibold">Slow Page Speed</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block text-danger fs-14 text-center">View <i class="ti ti-trending-down ms-1"></i></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                Traffic Sources
                            </div>
                            <div class="dropdown">
                                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-outline-light btn-icons btn-sm text-muted" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">Today</a></li>
                                    <li class="border-bottom"><a class="dropdown-item" href="javascript:void(0);">This Week</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Last Week</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="salesDonut"></div>
                        </div>
                        <div class="card-body p-0">
                            <div class="row row-cols-12 border-top border-block-start-dashed">
                                <div class="col p-0">
                                    <div class="ps-4 py-3 pe-3 text-center border-end border-inline-end-dashed">
                                        <span class="text-muted fs-12 mb-1 crm-lead-legend mobile d-inline-block">Organic Search
                                        </span>
                                        <div><span class="text-md fw-semibold">58%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="p-3 text-center border-end border-inline-end-dashed">
                                        <span class="text-muted fs-12 mb-1 crm-lead-legend desktop d-inline-block">Paid Search
                                        </span>
                                        <div><span class="text-md fw-semibold">22%</span></div>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="p-3 text-center border-end border-inline-end-dashed">
                                        <span class="text-muted fs-12 mb-1 crm-lead-legend laptop d-inline-block">Direct
                                        </span>
                                        <div><span class="text-md fw-semibold">12%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col p-0">
                                    <div class="p-3 text-center">
                                        <span class="text-muted fs-12 mb-1 crm-lead-legend tablet d-inline-block">Referral
                                        </span>
                                        <div><span class="text-md fw-semibold">8%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-12 col-xl-6 col-lg-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">Competitor Insights</div>
                        </div>
                        <div class="card-body">
                            <ul class="task-list mb-0">
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-primary"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on
                                                    <a href="javascript:void(0)" class="fw-semibold text-primary"> AngularJS Template</a></span>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-info"></i>
                                        <h6 class="fw-semibold mb-0">Task Overdue</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Petey Cruiser finished</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-info"> Integrated management</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">29 June 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-warning"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-warning"> AngularJS Template</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="">
                                    <div class="">
                                        <i class="task-icon bg-success"></i>
                                        <h6 class="fw-semibold mb-0">Task Finished</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-success"> AngularJS Template</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">09 July 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-0">
                                    <div class="">
                                        <i class="task-icon bg-secondary"></i>
                                        <h6 class="fw-semibold mb-0">New Comment</h6>
                                        <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="fs-12 text-muted">Adam Berry finished task on</span>
                                                    <a href="javascript:void(0)" class="fw-semibold text-secondary"> Project Management</a>
                                            </div>
                                            <div class="min-w-fit-content ms-2 text-end">

                                                <p class="mb-0 text-muted fs-11">25 Aug 2021</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- End::app-content -->
     
@endsection