<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                        <h1 id="user-count">Loading...</h1>
                        <div id="country-stats-container">By country:
                            <div id="country-stats">Loading...</div>
                            
                        
                            </div>
                 <script>
                    fetch('http://localhost:3000/stats/usercount')
                        .then(res => res.json())
                        .then(data => {
                        document.getElementById('user-count').textContent = 'Total users: ' + data.total_users;
                        });

                    fetch('http://localhost:3000/stats/usercountbycountry')
                        .then(res => res.json())
                        .then(data => {
                    const container = document.getElementById('country-stats');
                    container.innerHTML = data.map(row => 
                    `<p>${row.country}: ${row.total}</p>`
                    ).join('');
                    });
                </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
