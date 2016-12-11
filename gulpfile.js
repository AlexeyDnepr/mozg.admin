var gulp = require('gulp');
var elixir = require('laravel-elixir');
var mmq = require('gulp-merge-media-queries');

gulp.task('mmq', function () {
    gulp.src('public/css/*.css')
            .pipe(mmq())
            .pipe(gulp.dest('public/css'));
});

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
            .task('mmq')
            .scripts(['radio_btn.js'], "public/js/radio_btn.js")
            .scripts(['leagues.js'], "public/js/leagues.js")
            .scripts(['all.js'], "public/js/all.js")
            .version(["public/css/all.css", "public/js/radio_btn.js", "public/js/leagues.js", "public/js/all.js"]);
});