@extends('layouts.sidebar')

@section('content')

    <!-- Main Content -->
    <main class="flex-1 p-8 ml-4"> <!-- Tambahkan margin kiri (ml-4) agar lebih dekat ke sidebar -->
        <!-- Header -->
        <div class="flex justify-between items-center">
            <!-- Breadcrumb -->
            <nav class="text-gray-500 text-sm flex items-center space-x-2">
                <a href="" class="flex items-center space-x-1 hover:text-gray-700">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span>Home</span>
                </a>
                <span>/</span>
                <span class="text-gray-900 font-semibold">User</span>
            </nav>

            <!-- Profile Button -->
            <div class="relative">
                <button id="profileMenu" class="rounded-full bg-orange-300 p-2">
                    <i data-lucide="lightbulb" class="w-6 h-6 text-white"></i>
                </button>
                <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-md rounded-lg p-2 hidden">
                    <a href="" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                    <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
        @if(session('success') && is_array(session('success')))
            @php
                $alert = session('success'); // This is now an array
            @endphp

            <div class="p-4 rounded-md mt-4 
                @if($alert['type'] == 'created') bg-green-500 @elseif($alert['type'] == 'deleted') bg-red-500 @endif text-white">
                {{ $alert['message'] }}
            </div>

            <script>
                // Automatically refresh the page after 3 seconds
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            </script>
        @endif

        <!-- User List -->
        <div class="bg-white p-8 shadow-md rounded-lg mt-6"> <!-- Perbesar padding untuk konten -->
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold flex items-center space-x-2"> <!-- Perbesar font -->
                    <i data-lucide="box"></i> <span>Users</span>
                </h2>
                <a href="{{ route('users.create') }}" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center space-x-2">
                    <i data-lucide="plus-circle"></i> <span>Create User</span>
                </a>
            </div>

            <table class="w-full mt-6 border-collapse border border-gray-300"> <!-- Tambah jarak ke tabel -->
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-3 text-left">#</th>
                        <th class="border p-3 text-left">Username</th>
                        <th class="border p-3 text-left">Email</th>
                        <th class="border p-3 text-left">Role</th>
                        <th class="border p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="border">
                        <td class="border p-3">{{ $loop->iteration }}</td>
                        <td class="border p-3">{{ $user->username }}</td>
                        <td class="border p-3">{{ $user->email }}</td>
                        <td class="border p-3">{{ $user->role }}</td>
                        <td class="border p-3 flex justify-center space-x-3">
                            <!-- Edit Button -->
                            <a href="{{ route('users.up', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i data-lucide="edit"></i>
                            </a>
                            <!-- Delete Form -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return handleDelete(event, '{{ $user->role }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-500 hover:text-red-700">
                                        <!-- @if($user->role === 'admin') disabled class="text-gray-400 cursor-not-allowed" @endif> -->
                                    <i data-lucide="trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

<script>
    lucide.createIcons();

    // Toggle Profile Menu
    document.getElementById('profileMenu').addEventListener('click', function () {
        document.getElementById('profileDropdown').classList.toggle('hidden');
    });
</script>
<script>
    function handleDelete(event, role) {
        if (role === 'admin') {
            alert("Cannot delete user admin.");
            event.preventDefault(); // cegah submit
            return false; // block form submit
        }
        return confirm('Delete this user?');
    }
</script>


@endsection
