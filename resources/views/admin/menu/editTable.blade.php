@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>Edit Table</h3>

    <form action="{{ route('tableUpdate', $table->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Table Name</label>
            <input type="text" class="form-control" name="name" value="{{ $table->name }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" class="form-control" name="price" value="{{ $table->price }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
