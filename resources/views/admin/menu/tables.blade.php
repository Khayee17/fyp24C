@extends('admin.layouts.app')

@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
			
    <!-- Page Content -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Manage Tables</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Tables</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- 显示反馈信息 -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- 创建或编辑桌位的表单 -->
        <form id="tableForm" action="{{ route('tables.store') }}" method="POST">
            @csrf
            <input type="hidden" id="tableId" name="id"> <!-- 用于编辑操作 -->
            <div class="form-group">
                <label for="name">Table Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter table name" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" placeholder="Enter table capacity" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Save Table</button>
        </form>

        <hr>

        <!-- 显示桌位列表 -->
        <h4>Existing Tables</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tables as $table)
                    <tr>
                        <td>{{ $table->name }}</td>
                        <td>{{ $table->capacity }}</td>
                        <td>
                            <!-- 编辑按钮 -->
                            <button class="btn btn-warning btn-sm" onclick="editTable({{ $table }})">Edit</button>
                            <!-- 删除按钮 -->
                            <form action="{{ route('tables.destroy', $table->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this table?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

    

<script>
    // 编辑操作：将数据填入表单
    function editTable(table) {
        document.getElementById('tableId').value = table.id; // 隐藏字段设置桌位ID
        document.getElementById('name').value = table.name; // 设置桌位名称
        document.getElementById('capacity').value = table.capacity; // 设置桌位容量

        // 更改表单提交的URL为更新操作
        const form = document.getElementById('tableForm');
        form.action = "{{ url('/tables') }}/" + table.id;
        form.method = "POST";

        // 添加 PUT 方法
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }
</script>
@endsection
