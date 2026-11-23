
@extends('layouts.app')

@section('content')

<section class="container">

    <div class="section-heading">
        <h1>Manage Food Items</h1>
        <p>Add, update and remove food items from the system.</p>
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
        <h2>Add Food Item</h2>

        <form action="{{ route('admin.food-items.store') }}" method="POST">
            @csrf

            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" required>
                <option value="">Select a category</option>

                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label for="name">Food Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >

            <label for="description">Description</label>
            <textarea
                name="description"
                id="description"
                rows="3"
            >{{ old('description') }}</textarea>

            <button type="submit" class="btn">
                Add Food Item
            </button>
        </form>
    </div>

    <div class="section-heading">
        <h2>Existing Food Items</h2>
    </div>

    @if($foodItems->count() > 0)

        <div class="food-grid">

            @foreach($foodItems as $foodItem)

                <div class="food-card">

                    <img
                        src="https://placehold.co/600x400?text={{ urlencode($foodItem->name) }}"
                        alt="{{ $foodItem->name }}"
                        class="food-image"
                    >

                    <div class="food-card-content">

                        <h3>{{ $foodItem->name }}</h3>

                        <p>
                            {{ $foodItem->description ?? 'No description available.' }}
                        </p>

                        <p class="food-category">
                            {{ $foodItem->category?->name ?? 'No Category' }}
                        </p>

                        <hr>

                        <h4>Update Food Item</h4>

                        <form
                            action="{{ route('admin.food-items.update', $foodItem->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('PUT')

                            <label>Category</label>
                            <select name="category_id" required>
                                <option value="">Select a category</option>

                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ $foodItem->category_id == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <label>Food Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ $foodItem->name }}"
                                required
                            >

                            <label>Description</label>
                            <textarea
                                name="description"
                                rows="3"
                            >{{ $foodItem->description }}</textarea>

                            <button type="submit" class="btn">
                                Update
                            </button>
                        </form>

                        <form
                            action="{{ route('admin.food-items.destroy', $foodItem->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this food item?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">
                                Delete
                            </button>
                        </form>

                    </div>
                </div>

            @endforeach

        </div>

    @else

        <div class="empty-state">
            <h3>No Food Items Yet</h3>
            <p>Add food items using the form above.</p>
        </div>

    @endif

</section>

@endsection
