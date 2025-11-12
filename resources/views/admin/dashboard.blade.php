
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Header -->
        @include('admin.dashboardcomponents.header')

        <!-- Overview Cards -->
        @include('admin.dashboardcomponents.overview-cards')

        <!-- Charts and Main Content -->
            <!-- Sales Chart -->
<div class="mb-[20px]">
    @include('admin.dashboardcomponents.sales-chart')
</div>

            <!-- Recent Activities -->

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            @include('admin.dashboardcomponents.recent-orders')

            <!-- Recent Service Bookings -->
            @include('admin.dashboardcomponents.recent-service-bookings')
        </div>

        <!-- Bottom Stats -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"> --}}
            <!-- Employee Stats -->
            <div class="mb-[20px]">

            @include('admin.dashboardcomponents.employee-distribution')

            <!-- OK Credit Summary -->
            {{-- @include('admin.dashboardcomponents.credit-summary') --}}

            
        </div>
                    @include('admin.dashboardcomponents.profitrevenueturnover')

    </div>

    <style>
        .circular-chart {
            display: block;
            margin: 10px auto;
            max-width: 80%;
            max-height: 250px;
        }
    </style>
@endsection
