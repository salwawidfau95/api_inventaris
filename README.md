- composer create-project --prefer-dist laravel/laravel:^10.0 ujikom

cd nama_project
- edit env 
- php artisan config:clear (buat env)
- php artisan make:model Post -mcr
- php artisan make:middleware IsLogin 
- masukin middleware ke kernel
- php artisan make:seeder UserSeeder (buat file seeder)
- php artisan db:seed --class=UserSeeder (buat data seeder)
- composer require barryvdh/laravel-dompdf (mau bikin pdf)
- composer require maatwebsite/excel (mau bikin excel)


npm install -D tailwindcss
npx tailwindcss init
npm install lucide-react
https://lucide.dev/icons/
php artisan storage:link

buat refresh
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache

composer dump-autoload
BUAT REFRESH ABIS APUS FILE

bintangmayra
Bintang4321_
