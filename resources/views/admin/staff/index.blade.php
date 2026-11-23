
@extends('layouts.app')

@section('content')

<section class="container">

    <div class="section-heading">
        <h1>Manage Staff</h1>
        <p>Create and manage staff accounts.</p>
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

        <h2>Add New Staff Member</h2>

        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <label for="password_confirmation">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
            >

            <button type="submit" class="btn">
                Create Staff Account
            </button>
        </form>

    </div>

    <div class="section-heading">
        <h2>Staff Members</h2>
    </div>

    @if($staffMembers->count() > 0)

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($staffMembers as $staff)
                        <tr>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>{{ ucfirst($staff->role) }}</td>

                            <td>
                                <form
                                    action="{{ route('admin.staff.destroy', $staff->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to remove this staff member?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">
                                        Remove
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
            <h3>No Staff Members Found</h3>
            <p>Create a staff account using the form above.</p>
        </div>

    @endif

</section>

@endsection
