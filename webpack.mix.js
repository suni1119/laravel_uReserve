const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')

// バージョニングを使用する場合（キャッシュバスティング）
if (mix.inProduction()) {
    mix.version();
}
