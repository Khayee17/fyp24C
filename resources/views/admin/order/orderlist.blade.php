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
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
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

        <!-- Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table ">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-orders"></th>
                                <th>Order ID</th>
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
                                    <td><input type="checkbox" class="select-order" value="{{ $order->id }}"></td>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->customer_count }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#view_order_{{ $order->id }}"><i class="fa fa-pencil m-r-5"></i> View</a>

                                                <a class="dropdown-item" data-toggle="modal" data-target="#delete_order_{{ $order->id }}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- //view modal  -->
                                <div class="modal fade" id="view_order_{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOrderLabel_{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="viewOrderLabel_{{ $order->id }}">
                                                    <i class="fas fa-file-invoice"></i> Order Details (ID: {{ $order->id }})
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6><i class="fas fa-user"></i> Customer Info</h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Phone:</strong> {{ $order->phone }}</li>
                                                            <li><strong>Customer Count:</strong> {{ $order->customer_count }}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6><i class="fas fa-calendar-check"></i> Order Info</h6>
                                                        <ul class="list-unstyled">
                                                            <li><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h6><i class="fas fa-box"></i> Order Items</h6>
                                                @if($order->items)
                                                    <ul class="list-group">
                                                        @foreach($order->items as $item)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $item['name'] }} (x{{ $item['quantity'] }}) 
                                                                <span class="badge badge-secondary">RM{{ number_format($item['price'], 2) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>No items found.</p>
                                                @endif
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p><strong>Subtotal:</strong> RM{{ number_format($order->subtotal, 2) }}</p>
                                                        <p><strong>SST:</strong> RM{{ number_format($order->sst, 2) }}</p>
                                                        <p><strong>Rounding:</strong> RM{{ number_format($order->rounding, 2) }}</p>
                                                    </div>
                                                    <div>
                                                        <p><strong>Total:</strong> <span class="h4 text-success">RM{{ number_format($order->total, 2) }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            @endforeach
                        </tbody>
                    </table>
                    <button id="delete-selected-orders" class="btn btn-danger" style="display: none;">Delete Selected</button>

                    <!-- Display number of entries -->
                    <div class="d-flex justify-content-between">
                        <span>Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries</span>
                    </div>

                    <!-- Custom Pagination -->
                    <div id="pagination" class="d-flex justify-content-between">
                        <a href="{{ $orders->previousPageUrl() }}" class="btn btn-light" {{ $orders->onFirstPage() ? 'disabled' : '' }}>Previous</a>
                        <span>Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>
                        <a href="{{ $orders->nextPageUrl() }}" class="btn btn-light" {{ $orders->hasMorePages() ? '' : 'disabled' }}>Next</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="paginationInfo"></div>
    <!-- /Page Content -->
</div>
<!-- /Page Wrapper -->


<!-- Delete Order Modal -->
@foreach($orders as $order)
    <div class="modal custom-modal fade" id="delete_order_{{ $order->id }}" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Order <span style="color: red;">{{ $order->id }}</span></h3>
                        <p>Are you sure you want to delete this order?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <form action="{{ route('ordersDelete', $order->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                </form>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
<!-- /Delete Order Modal -->

<!-- Delete Selected Orders Modal -->
<div class="modal custom-modal fade" id="delete_selected_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Selected Orders</h3>
                    <p>Are you sure you want to delete the selected orders?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button id="confirm-delete-selected" class="btn btn-primary continue-btn">Delete</button>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Selected Orders Modal -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.getElementById('search_button').addEventListener('click', function() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                const url = new URL(window.location.href);
                url.searchParams.set('start_date', startDate);
                url.searchParams.set('end_date', endDate);
                window.location.href = url;
            });

            //delete selected
            const selectAllOrdersCheckbox = document.getElementById('select-all-orders');
            const orderCheckboxes = document.querySelectorAll('.select-order');
            const deleteOrdersButton = document.getElementById('delete-selected-orders');
            let selectedIds = [];

            function updateDeleteOrdersButtonVisibility() {
                selectedIds = Array.from(document.querySelectorAll('.select-order'))
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                
                // 只有勾选2个以上的订单时，才显示删除按钮
                deleteOrdersButton.style.display = selectedIds.length > 1 ? 'block' : 'none';
            }

            selectAllOrdersCheckbox.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllOrdersCheckbox.checked;
                });
                updateDeleteOrdersButtonVisibility();
            });

            orderCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDeleteOrdersButtonVisibility);
            });

            deleteOrdersButton.addEventListener('click', function() {
                if (selectedIds.length > 1) {
                    $('#delete_selected_modal').modal('show');
                } else {
                    alert('Please select at least two orders to delete.');
                }
            });

            
            document.getElementById('confirm-delete-selected').addEventListener('click', function() {
                fetch('/orders/delete-multiple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete orders.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

        });

        

    </script>

@endsection
