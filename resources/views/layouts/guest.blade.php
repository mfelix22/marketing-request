<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Prevent FOUC: hide body until Alpine has initialised all x-show directives -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            opacity: 0;
        }
    </style>
    <title>{{ config('app.name', 'MarCom Request') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-[#1D3557]">
    <div class="min-h-screen flex">
        <!-- Left Panel -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 bg-[#162840]">
            <div>
                <div class="flex flex-col items-start space-y-2 mb-4">
                    <img src="/hartonogroup.png" alt="Hartono Group Logo" class="h-12 w-auto drop-shadow-lg">
                    <div class="flex items-baseline space-x-2">
                        {{-- <span class="text-white font-bold text-3xl tracking-wider">HARTONO</span> --}}
                        {{-- <span class="text-blue-300 font-light text-xl">GROUP</span> --}}
                    </div>
                </div>
                {{-- <p class="text-blue-300 text-sm mt-1">Mercedes-Benz Dealer</p> --}}
            </div>
            <div>
                <h2 class="text-white text-3xl font-light leading-tight">Marketing Request<br><span
                        class="font-semibold">Management System</span></h2>
                <p class="text-blue-300 mt-4 text-sm leading-relaxed">Submit and track your marketing requests
                    digitally. Get approvals faster with our online workflow system.</p>
            </div>
            <p class="text-blue-400 text-xs">&copy; {{ date('Y') }} Hartono Group. All rights reserved.</p>
        </div>

        <!-- Right Panel -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-8 text-center">
                    <span class="text-white font-bold text-2xl tracking-wider">HARTONO</span>
                    <span class="text-blue-300 font-light text-lg ml-1">GROUP</span>
                </div>
                <div class="bg-white rounded-2xl shadow-2xl p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
