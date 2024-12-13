@extends('admin.layouts.app')

@section('content')
<table class="table table-striped custom-table datatable">
    <thead>
        <tr>
            <th>Category Name</th>
            <th>Category ID</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
            <tr>
                <td>
                    <h2 class="table-avatar">
                        <a href="#" class="avatar">
                            @if($category->ctgImg)
                                <img src="{{ asset('storage/' . $category->ctgImg) }}" alt="{{ $category->name }}">
                            @else
                                <img src="{{ asset('assets/img/default-avatar.png') }}" alt="Default Image">
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
                <td colspan="3" class="text-center">No categories found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination-container">
    {{ $categories->links() }} <!-- 显示分页链接 -->
</div>
@endsection
