@extends('layouts.app')

@section('content')
<section class="container">
    <div class="section-heading">
        <h1>Manage Categories</h1>
        <p>Create and manage food categories.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <h2>Add New Category</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <label for="name">Category Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >

            <label for="description">Description</label>
            <textarea
                id="description"
                name="description"
                rows="3"
            >{{ old('description') }}</textarea>

            <button type="submit" class="btn">
                Add Category
            </button>
        </form>
    </div>

    <div class="section-heading">
        <h2>Existing Categories</h2>
    </div>

    @if($categories->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Food Items</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                {{ $category->description ?? 'No description' }}
                            </td>
                            <td>
                                {{ $category->food_items_count }}
                            </td>
                            <td>
                                <form
                                    action="{{ route('admin.categories.update', $category->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PUT')

                                    <label>Category Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ $category->name }}"
                                        required
                                    >

                                    <label>Description</label>
                                    <textarea
                                        name="description"
                                        rows="2"
                                    >{{ $category->description }}</textarea>

                                    <button type="submit" class="btn">
                                        Update
                                    </button>
                                </form>

                                <form
                                    action="{{ route('admin.categories.destroy', $category->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <h3>No Categories Yet</h3>
            <p>Add your first food category using the form above.</p>
        </div>
    @endif
</section>
@endsection