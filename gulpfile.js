var elixir = require('laravel-elixir');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function (mix) {
    mix.less('app.less')
            .styles(['flags.css', 'app.css'], 'public/css/all.css', 'public/css')
            .scripts(['radio_btn.js'], "public/js/radio_btn.js")
            .scripts(['leagues.js'], "public/js/leagues.js")
            .scripts(['all.js'], "public/js/all.js")
            .version(["public/css/all.css", "public/js/radio_btn.js", "public/js/leagues.js", "public/js/all.js"]);
});
