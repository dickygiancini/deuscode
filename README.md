## Tutorial Reproduce
Anda dapat reproduce Repo ini dengan melakukan :
1. 
```bash
$ git clone https://github.com/dickygiancini/deuscode
$ composer install
```

2. Kemudian copy file `.env.example` dan paste ke root direktori Anda, dan ubah menjadi file statis `.env`
3. Kemudian lakukan konfigurasi environment seperti database, username dan password
4. Lalu, lakukan perintah berikut
```bash
$ php artisan key:generate
$ php artisan migrate
$ php artisan serve
```
