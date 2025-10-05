<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - OptiCodex</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: { extend: { fontFamily: { sans:['Inter','sans-serif'], heading:['Poppins','sans-serif'] } } }
    }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
        }

        .active-link {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            color: white;
        }

        /* Toast styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 250px;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            z-index: 9999;
            color: #fff;
            animation: slideIn 0.5s ease;
        }

        .toast-success {
            background-color: #16a34a;
        }

        .toast-error {
            background-color: #dc2626;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-8">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-code text-white"></i> </div> <span
                        class="text-2xl font-bold font-heading">OptiCodex</span>
                </div>
                <nav class="space-y-2"> <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'active-link' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-dashboard w-5"></i> <span class="ml-3 font-medium">Dashboard</span> </a> <a
                        href="{{ route('admin.categories.index') }}"
                        class="flex items-center py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'active-link' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-folder w-5"></i> <span class="ml-3 font-medium">Categories</span> </a> <a
                        href="{{ route('admin.blogs.index') }}"
                        class="flex items-center py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.blogs.*') ? 'active-link' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-newspaper w-5"></i> <span class="ml-3 font-medium">Blogs</span> </a> <a
                        href="{{ route('admin.users.index') }}"
                        class="flex items-center py-3 px-4 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'active-link' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-users w-5"></i> <span class="ml-3 font-medium">Users</span> </a>
                    <hr class="my-4 border-gray-700"> <a href="{{ route('home') }}" target="_blank"
                        class="flex items-center py-3 px-4 rounded-lg hover:bg-gray-800 transition"> <i
                            class="fas fa-external-link-alt w-5"></i> <span class="ml-3 font-medium">View Website</span>
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm z-10">
                <div class="flex justify-between items-center px-8 py-4">
                    <h2 class="text-2xl font-bold font-heading text-gray-800">@yield('page-title','Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600"><i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name
                            }}</span>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        $.ajaxSetup({ headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

function showToast(type, message) {
    const existing = document.querySelector('.toast'); if(existing) existing.remove();
    const toast = document.createElement('div');
    toast.classList.add('toast', type==='success'?'toast-success':'toast-error');
    toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':'fa-exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(()=>toast.remove(), 3000);
}

// Show Laravel session messages
@if(session()->has('success')) showToast('success',"{{ session('success') }}"); @elseif(session()->has('error')) showToast('error',"{{ session('error') }}"); @endif

// AJAX delete with toast
function ajaxDelete(url, row=null){
    if(confirm('Are you sure you want to delete this?')){
        $.post(url,{_method:'DELETE'}, function(){
            showToast('success','Deleted successfully!');
            if(row) $(row).fadeOut(300,function(){ $(this).remove(); });
        }).fail(function(){ showToast('error','Error occurred while deleting!'); });
    }
}
    </script>

    @stack('scripts')
</body>

</html>
