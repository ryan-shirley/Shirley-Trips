# About This Project
This projected started out as a way for my to keep a diary of photos realted to a travel experience that I went on. It then transitioned to share with parents to be able to view what I was up to away.

This project was built with [Laravel](https://laravel.com/) and [Vue.js](https://vuejs.org/). Laravel passport was also implemented to handle authentication with the Laravel API. It is a mobile optimised design and intended for use on mobile.

## Features
- Live flight details
- Private holidays
- View / Edit permissions
- Hotel Details
- Day by day view
- Daily optimised images using [TinyPNG](https://tinypng.com/)

## Database Design
Visual representation of datase. Photo from Instagram Higlights.

![Database Design](https://scontent-dub4-1.cdninstagram.com/vp/609034b70ca88b9aab217ce317c8f588/5DC4E796/t51.12442-15/sh0.08/e35/p640x640/57648947_630599804074251_7468587002889773271_n.jpg?_nc_ht=scontent-dub4-1.cdninstagram.com&_nc_cat=103 "Database Design")

## Run Project

``` bash
# install dependencies
$ composer update

# migrate database and seed with content
$ php artisan migrate --seed

# generate passport keys
$ php artisan passport:keys

# serve at localhost:8000
$ php artisan serve
```

For detailed explanation on how things work, checkout [Laravel docs](https://laravel.com/docs/5.8).
