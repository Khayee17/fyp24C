@extends('admin.layouts.app')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Display success message -->
        @if(session('success'))
            <div id="successMessage" class="alert alert-success" style="opacity: 1; transition: opacity 1s;">
                {{ session('success') }}
            </div>

            <!-- Add JavaScript to hide the message after 5 seconds -->
            <script>
                setTimeout(function() {
                    var message = document.getElementById('successMessage');
                    message.style.opacity = 0; // Make the message fade out
                    setTimeout(function() {
                        message.style.display = 'none'; // Hide the message completely after fade-out
                    }, 1000); // Wait for the opacity transition to complete
                }, 3000); // 5000 milliseconds = 5 seconds
            </script>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Order History</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href=" ">Dashboard</a></li>
                        <li class="breadcrumb-item active">Order History</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">  
                <div class="form-group form-focus">
                    <input type="date" name="start_date" id="start_date" class="form-control floating" value="{{ request('start_date') }}">
                    <label class="focus-label">Start Date</label>
                </div>
            </div>
        
            <div class="col-sm-6 col-md-3">  
                <div class="form-group form-focus">
                    <input type="date" name="end_date" id="end_date" class="form-control floating" value="{{ request('end_date') }}">
                    <label class="focus-label">End Date</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">  
                <a href="#" class="btn btn-success btn-block" id="search_button">Search</a>  
            </div>     
        </div>
        <!-- Search Filter -->

        <!-- Order History Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Phone</th>
                                <th>Customer Count</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->customer_count }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
					
                    <!-- Pagination -->
                    <div class="pagination">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- /Order History Table -->
    </div>
</div>

<script>
    document.getElementById('search_button').addEventListener('click', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const url = new URL(window.location.href);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);
        window.location.href = url;
    });
</script>

@endsection
