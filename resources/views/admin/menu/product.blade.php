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
                    <h3 class="page-title">Products</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_product"><i class="fa fa-plus"></i> Add Product</a>
                    <div class="view-icons">
                        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        
        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">  
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" id="searchProduct" placeholder="Search by Product ID/Name/Category">
                    <label class="focus-label">Search</label>
                </div>
            </div>
        
            <div class="col-sm-6 col-md-3"> 
                <div class="form-group form-focus select-focus">
                    <select class="select floating" id="searchCategory"> 
                        <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                    </select>
                    <label class="focus-label">Category</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">  
                <a href="#" class="btn btn-success btn-block" id="searchButton"> Search </a>  
            </div>     
        </div>
        <!-- Search Filter -->

        <!-- Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-products"></th>
                                <th>Product Name</th>
                                <th>Product ID</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Stock Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            @if($product->in_stock)
                                <tr>
                                    <td><input type="checkbox" class="select-product" value="{{ $product->id }}"></td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar" data-toggle="modal" data-target="#imageModal" data-img="{{ asset('storage/' . $product->product_img) }}">
                                                @if($product->product_img)
                                                    <img src="{{ asset('storage/' . $product->product_img) }}" alt="{{ $product->name }}" class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/image/default.png') }}" alt="Default Image" class="img-fluid">
                                                @endif
                                            </a>
                                            <a href="#">{{ $product->product_name }}</a>
                                        </h2>
                                    </td>
                                    <td>{{ $product->product_id }}</td>
                                    <td>{{ $product->unit_price }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <div class="dropdown action-label" id="stock-status-{{ $product->id }}">
                                            <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-dot-circle-o {{ $product->in_stock ? 'text-success' : 'text-danger' }}"></i> 
                                                {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#" onclick="updateStockStatus({{ $product->id }}, 1)"><i class="fa fa-dot-circle-o text-success"></i> In Stock</a>
                                                <a class="dropdown-item" href="#" onclick="updateStockStatus({{ $product->id }}, 0)"><i class="fa fa-dot-circle-o text-danger"></i> Out of Stock</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_product_{{ $product->id }}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_product_{{ $product->id }}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="delete-selected-products" class="btn btn-danger" style="display: none;">Delete Selected</button>
                </div>
            </div>
        </div>
    </div>

    <div id="paginationInfo"></div>
    <!-- /Page Content -->

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">View Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="" id="modalImage" class="img-fluid" alt="Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="add_product" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('productsStore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Product Photo</label>
                                    <input class="form-control" type="file" name="product_img">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Product Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="product_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Product ID <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="product_id" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="product_des" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">Unit Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">RM</span>
                                        </div>
                                        <input class="form-control" name="unit_price" type="number" step="0.05" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category <span class="text-danger">*</span></label>
                                    <select class="form-control"  name="category_id" required>
                                        <option value="">Select a category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-form-label">In Stock <span class="text-danger">*</span></label>
                                    <select class="form-control" name="in_stock" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Variant (Optional)</label>
                                    <div id="optional-choices-add">
                                        <!-- 动态添加变体 -->
                                    </div>
                                    <button class="btn-s btn-dark add-choice" data-container-id="optional-choices-add" type="button">Add Variant</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Product Modal -->

    <!-- Edit Product Modal -->
    @foreach($products as $product)
    <div id="edit_product_{{ $product->id }}" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product <span style="color: red;">{{ $product->name }}</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="{{ route('productsUpdate', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-form-label">Product Photo</label>
                                    <input class="form-control" type="file" name="product_img">
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Product Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Product ID <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="product_id" value="{{ old('product_id', $product->product_id) }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="product_des"  required>{{ old('product_des', $product->product_des) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Unit Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RM</span>
                                    </div>
                                    <input class="form-control" name="unit_price" type="number" value="{{ old('unit_price', $product->unit_price) }}" step="0.05" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="category" name="category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">In Stock <span class="text-danger">*</span></label>
                                <select class="form-control" name="in_stock" required>
                                    <option value="1" {{ $product->in_stock ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$product->in_stock ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Variant (Optional)</label>
                                <div id="optional-choices-{{ $product->id }}">
                                    @foreach($product->variants as $variant)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="variantName[]" value="{{ $variant->variantName }}" placeholder="Name (eg:Temperature)" required>
                                        <input type="text" class="form-control" name="variantOpt[]" value="{{ $variant->variantOpt }}" placeholder="Option (eg:cold,hot)" required>
                                        <input type="text" class="form-control" name="variantPrice[]" value="{{ $variant->variantPrice }}" placeholder="Price (eg:0.50,0.20)" required>
                                        
                                        <!-- 确保 is_required 始终存在 -->
                                        <div class="input-group-append">
                                            <input type="checkbox" name="is_required[{{ $loop->index }}]" value="1" {{ $variant->is_required ? 'checked' : '' }}> Is Required
                                        </div>

                                        <div class="input-group-append">
                                            <button class="btn btn-danger remove-variant" type="button"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button class="btn-s btn-dark add-choice" data-container-id="optional-choices-{{ $product->id }}" type="button">Add Variant</button>
                            </div>
                        </div>
                        </div>
                        
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <!-- /Edit Product Modal -->

    <!-- Delete Product Modal -->
    @foreach($products as $product)
    <div class="modal custom-modal fade" id="delete_product_{{ $product->id }}" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Product <span style="color: red;">{{ $product->name }}</span></h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <form action="{{ route('productsDelete', $product->id) }}" method="POST">
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
    <!-- /Delete Product Modal -->

    <!-- Delete Selected Modal -->
    <div class="modal custom-modal fade" id="delete_selected_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Selected Products</h3>
                        <p>Are you sure you want to delete the selected products?</p>
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
    <!-- Delete Selected Modal -->


</div>
<!-- /Page Wrapper -->

    <script>
         document.addEventListener('DOMContentLoaded', function() {
            var avatarLinks = document.querySelectorAll('.avatar');
            var modalImage = document.getElementById('modalImage');

            avatarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    var imgSrc = this.getAttribute('data-img');
                    modalImage.setAttribute('src', imgSrc);
                });
            });
        });

        // document.addEventListener('DOMContentLoaded', function() {
        //     document.querySelectorAll('.add-choice').forEach(function(button) {
        //         button.addEventListener('click', function() {
        //             var productId = this.getAttribute('data-product-id');
        //             var choiceContainer = document.createElement('div');
        //             choiceContainer.className = 'input-group mb-2';
        //             choiceContainer.innerHTML = `
        //                 <input type="text" class="form-control" name="variantName[]" placeholder="Name (eg:Temperature)" required>
        //                 <input type="text" class="form-control" name="variantOpt[]" placeholder="Option (eg:cold,hot)" required>
        //                 <input type="text" class="form-control" name="variantPrice[]" placeholder="Price (eg:0.50,0.20)" required>
        //                 <div class="input-group-append">
        //                     <button class="btn btn-danger remove-variant" type="button"><i class="fa-solid fa-trash"></i></button>
        //                 </div>
        //             `;
        //             document.getElementById('optional-choices-' + productId).appendChild(choiceContainer);
        //         });
        //     });

        //     document.querySelectorAll('.modal').forEach(function(modal) {
        //         modal.addEventListener('click', function(e) {
        //             if (e.target && (e.target.classList.contains('remove-variant') || e.target.closest('.remove-variant'))) {
        //                 e.target.closest('.input-group').remove();
        //             }
        //         });
        //     });
        // });

        function updateStockStatus(productId, inStock) {
            fetch(`/products/${productId}/update-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ in_stock: inStock })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusElement = document.querySelector(`#stock-status-${productId} a`);
                    if (inStock) {
                        statusElement.innerHTML = '<i class="fa fa-dot-circle-o text-success"></i> In Stock';
                    } else {
                        statusElement.innerHTML = '<i class="fa fa-dot-circle-o text-danger"></i> Out of Stock';
                    }
                    console.log('Stock status updated successfully.');
                } else {
                    alert('Failed to update stock status.');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        //variant
        document.addEventListener('DOMContentLoaded', function () {
             // 处理所有的添加变体按钮
            document.querySelectorAll('.add-choice').forEach(function(button) {
                button.addEventListener('click', function() {
                    var containerId = this.getAttribute('data-container-id');
                    var choiceContainer = document.createElement('div');
                    choiceContainer.className = 'input-group mb-2';
                    choiceContainer.innerHTML = `
                        <input type="text" class="form-control" name="variantName[]" placeholder="Name (eg:Temperature)" required>
                        <input type="text" class="form-control" name="variantOpt[]" placeholder="Option (eg:cold,hot)" required>
                        <input type="text" class="form-control" name="variantPrice[]" placeholder="Price (eg:0.50,0.20)" required>
                        
                        <!-- Add the required checkbox for each variant -->
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_required[]" value="1">
                            <label class="form-check-label">Is Required</label>
                        </div>
                        
                        <div class="input-group-append">
                            <button class="btn btn-danger remove-variant" type="button"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    `;
                    document.getElementById(containerId).appendChild(choiceContainer);
                });
            });

            // 处理所有模态框中的删除变体按钮
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target && (e.target.classList.contains('remove-variant') || e.target.closest('.remove-variant'))) {
                        e.target.closest('.input-group').remove();
                    }
                });
            });
        });


        //delete selected
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllProductsCheckbox = document.getElementById('select-all-products');
            const productCheckboxes = document.querySelectorAll('.select-product');
            const deleteProductsButton = document.getElementById('delete-selected-products');
            let selectedIds = [];

            function updateDeleteProductsButtonVisibility() {
                selectedIds = Array.from(document.querySelectorAll('.select-product'))
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                deleteProductsButton.style.display = selectedIds.length > 0 ? 'block' : 'none';
            }

            selectAllProductsCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllProductsCheckbox.checked;
                });
                updateDeleteProductsButtonVisibility();
            });

            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateDeleteProductsButtonVisibility);
            });

            deleteProductsButton.addEventListener('click', function() {
                if (selectedIds.length > 0) {
                    $('#delete_selected_modal').modal('show');
                } else {
                    alert('No products selected.');
                }
            });

            document.getElementById('confirm-delete-selected').addEventListener('click', function() {
                fetch('/products/delete-multiple', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete products.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        //search function
        let debounceTimer;

        function performSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const searchValue = document.getElementById('searchProduct').value;
                const categoryValue = document.getElementById('searchCategory').value;

                fetch("{{ route('products.search') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ search: searchValue, category: categoryValue })
                })
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('.table tbody');
                    tableBody.innerHTML = '';

                    if (data.products.length > 0) {
                        data.products.forEach((product) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><input type="checkbox" class="select-product" value="${product.id}"></td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#" class="avatar" data-toggle="modal" data-target="#imageModal" data-img="${product.product_img ? '/storage/' + product.product_img : '/assets/img/default-avatar.png'}">
                                            <img src="${product.product_img ? '/storage/' + product.product_img : '/assets/img/default-avatar.png'}" alt="${product.product_name}" class="img-fluid">
                                        </a>
                                        <a href="#">${product.product_name}</a>
                                    </h2>
                                </td>
                                <td>${product.product_id}</td>
                                <td>${product.unit_price}</td>
                                <td>${product.category ? product.category.name : 'N/A'}</td>
                                <td>
                                    <div class="dropdown action-label" id="stock-status-${product.id}">
                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-dot-circle-o ${product.in_stock ? 'text-success' : 'text-danger'}"></i> 
                                            ${product.in_stock ? 'In Stock' : 'Out of Stock'}
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" onclick="updateStockStatus(${product.id}, 1)"><i class="fa fa-dot-circle-o text-success"></i> In Stock</a>
                                            <a class="dropdown-item" href="#" onclick="updateStockStatus(${product.id}, 0)"><i class="fa fa-dot-circle-o text-danger"></i> Out of Stock</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_product_${product.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_product_${product.id}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // 重新绑定事件监听器
                        const productCheckboxes = document.querySelectorAll('.select-product');
                        productCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateDeleteProductsButtonVisibility);
                        });

                        // 更新删除按钮的可见性
                        updateDeleteProductsButtonVisibility();
            
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="7" class="text-center">No products found.</td>`;
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => console.error('Error:', error));
            }, 300); // 300ms debounce
        }

        document.getElementById('searchProduct').addEventListener('input', performSearch);
        document.getElementById('searchCategory').addEventListener('change', performSearch);
        document.getElementById('searchButton').addEventListener('click', performSearch);

    </script>

@endsection
