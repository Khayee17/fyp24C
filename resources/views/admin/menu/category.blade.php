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
                <h3 class="page-title">Categories</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="amdashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ul>
            </div>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_category"><i class="fa fa-plus"></i> Add Category</a>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
    
    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3">  
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" id="searchInput" placeholder="Search by Category ID or Name">
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
                                <th><input type="checkbox" id="select-all-categories"></th>
                                <th>Category Name</th>
                                <th>Category ID</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            @forelse($categories as $category)
                                <tr>
                                    <td><input type="checkbox" class="select-category" value="{{ $category->id }}"></td>
                                    <td>
                                        <h2 class="table-avatar">
                                            <a href="#" class="avatar" data-toggle="modal" data-target="#imageModal">
                                                @if($category->ctgImg)
                                                    <img src="{{ asset('storage/' . $category->ctgImg) }}" alt="{{ $category->name }}" class="img-fluid">
                                                @else
                                                    <img src="{{ asset('assets/images/default.png') }}" alt="Default Image" class="img-fluid">
                                                @endif
                                            </a>
                                            <a href=" ">{{ $category->name }}</a>
                                        </h2>
                                    </td>
                                    <td>{{ $category->category_id }}</td>
                                    <td class="text-right">
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_category_{{ $category->id }}">
                                                    <i class="fa fa-pencil m-r-5"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_category_{{ $category->id }}">
                                                    <i class="fa fa-trash-o m-r-5"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No categories found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button id="delete-selected-categories" class="btn btn-danger" style="display: none;">Delete Selected</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- image pop up modal -->
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
    <!-- /image pop up modal -->

<!-- Add Category Modal -->
<div id="add_category" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categorieStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Category Photo</label>
                                <input class="form-control" type="file" name="ctgImg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" required>
                                
                            </div>
                        </div>
                        
                        <div class="col-md-6">  
                            <div class="form-group">
                                <label class="col-form-label">Category ID <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_id" required>
                                
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
<!-- /Add Category Modal -->

<!-- Edit Category Modal -->
@foreach($categories as $category)
<div id="edit_category_{{ $category->id }}" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category <span style="color: red;">{{ $category->name }}</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('categoriesUpdate', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Category Photo</label>
                                <input class="form-control" type="file" name="ctgImg">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Category Name <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" value="{{ old('name', $category->name) }}" type="text" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">  
                            <div class="form-group">
                                <label class="col-form-label">Category ID <span class="text-danger">*</span></label>
                                <input class="form-control" name="category_id" value="{{ old('category_id', $category->category_id) }}" type="text" required>
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
<!-- /Edit Category Modal -->

<!-- Delete Category Modal -->
@foreach($categories as $category)
<div class="modal custom-modal fade" id="delete_category_{{ $category->id }}" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Category <span style="color: red;">{{ $category->name }}</span></h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{ route('categoriesDelete', $category->id) }}" method="POST">
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
<!-- /Delete Category Modal -->

 <!-- Delete Selected Categories Modal -->
<div class="modal custom-modal fade" id="delete_selected_categories_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Selected Categories</h3>
                    <p>Are you sure you want to delete the selected categories?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <button id="confirm-delete-selected-categories" class="btn btn-primary continue-btn">Delete</button>
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
 <!-- Delete Selected Categories Modal -->


</div>
<!-- /Page Wrapper -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var avatarLinks = document.querySelectorAll('.avatar');
            var modalImage = document.getElementById('modalImage');

            avatarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    var imgSrc = this.querySelector('img').getAttribute('src');
                    modalImage.setAttribute('src', imgSrc);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
        const selectAllCategoriesCheckbox = document.getElementById('select-all-categories');
        const categoryCheckboxes = document.querySelectorAll('.select-category');
        const deleteCategoriesButton = document.getElementById('delete-selected-categories');
        let selectedCategoryIds = [];

        function updateDeleteCategoriesButtonVisibility() {
            selectedCategoryIds = Array.from(categoryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            deleteCategoriesButton.style.display = selectedCategoryIds.length > 1 ? 'block' : 'none';
        }

        selectAllCategoriesCheckbox.addEventListener('change', function() {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCategoriesCheckbox.checked;
            });
            updateDeleteCategoriesButtonVisibility();
        });

        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteCategoriesButtonVisibility);
        });

        deleteCategoriesButton.addEventListener('click', function() {
            if (selectedCategoryIds.length > 0) {
                $('#delete_selected_categories_modal').modal('show');
            } else {
                alert('No categories selected.');
            }
        });

        document.getElementById('confirm-delete-selected-categories').addEventListener('click', function() {
            fetch('/categories/delete-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedCategoryIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to delete categories.');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

//delete selected categories
let debounceTimer;

function performSearch() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const searchValue = document.getElementById('searchInput').value;
        const categoryValue = document.getElementById('searchCategory').value;

        fetch("{{ route('categories.search') }}", {
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
            const tableBody = document.getElementById('categoryTableBody');
            tableBody.innerHTML = '';

            if (data.categories.length > 0) {
                data.categories.forEach((category) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><input type="checkbox" class="select-category" value="${category.id}"></td>
                        <td>
                            <h2 class="table-avatar">
                                <a href="#" class="avatar" data-toggle="modal" data-target="#imageModal" data-img="${category.ctgImg ? '/storage/' + category.ctgImg : '/assets/img/default-avatar.png'}">
                                    <img src="${category.ctgImg ? '/storage/' + category.ctgImg : '/assets/img/default-avatar.png'}" alt="${category.name}" class="img-fluid">
                                </a>
                                <a href="#">${category.name}</a>
                            </h2>
                        </td>
                        <td>${category.category_id}</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_category_${category.id}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_category_${category.id}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                </div>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="4" class="text-center">No categories found.</td>`;
                tableBody.appendChild(row);
            }
        })
        .catch(error => console.error('Error:', error));
    }, 300); // 300ms debounce
}

document.getElementById('searchInput').addEventListener('input', performSearch);
document.getElementById('searchCategory').addEventListener('change', performSearch);
document.getElementById('searchButton').addEventListener('click', performSearch);
    </script>

@endsection
