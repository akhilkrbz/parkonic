#Project Setup

Laravel version used : 12.39.0
PHP version : 8.2.4
MySQL version: 10.4.28-MariaDB


Setup instructions : 
1. Create database named: parking_reports_demo
2. Import the SQL file. (I created migration files also for a reference).
3. Now I directly imported the sql file because it was small sized file. For large sized files we can use MySQL CLI for import.
4. Clone the git project and do composer install
5. Update the .env file with databasename, username and password. Providing my local connection env details:
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=parking_reports_demo
        DB_USERNAME=root
        DB_PASSWORD=
6. Open terminal and do 'php artisan key:generate' for new application key encryption generation.
7. Open terminal and run below artisan commands:
    1. php artisan optimize (For clear and generate caches for config, events, views and routes).
    2. php artisan serve (For run the laravel app. Use http://127.0.0.1:8000 in browser to see the platform).

    
Assumptions made:

1. Filters apply based on in_time
2. I used an html template to do the UIs.
3. Chart Js used to create charts in the dashboard. Chart Js library was used in the template. So I used chart js library file instead of chart js CDN.
4. In filters 7 Days period will be selected by default. And results will be shown by defaults.
5. In sessions report: Export to CSV option is added.
6. Pagination is added for session report. Pagination links will be visible if there is more than 25 rows.
7. In dashboard, total sessions per location and building will not be based on filter. It is the total sessions count in each location / building.


The URL(s) to access:
1. Main Dashboard: http://127.0.0.1:8000 or http://127.0.0.1:8000/dashboard
2. Sessions Report: http://127.0.0.1:8000/sessions_report


Tasks confused when working:
1. 6.3. Optional Chart – Flow by Access Point : I started doing. But got confused about what it mean by 'flow by access point'. 
