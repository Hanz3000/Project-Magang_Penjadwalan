<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>To-Do List</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <!-- Frappe Gantt CSS -->
    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt/dist/frappe-gantt.css">

     <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    @stack('styles')
   

<!-- Then load FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
</head>

<body class="bg-gray-50">

    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>

    <!-- Lokalisasi Bahasa Indonesia -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.min.js"></script>

    <!-- Frappe Gantt JS -->
    <script src="https://unpkg.com/frappe-gantt/dist/frappe-gantt.min.js"></script>

    @stack('scripts')
</body>

</html>
