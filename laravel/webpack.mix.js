let mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/create-post.js"
    ], "public/js/create-post.js")
    .sass("resources/assets/sass/create-post.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/preview-post.js"
    ], "public/js/preview-post.js")
    .sass("resources/assets/sass/preview-post.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/show-news.js"
    ], "public/js/show-news.js")
    .sass("resources/assets/sass/show-news.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/show-user-content.js"
    ], "public/js/show-user-content.js")
    .sass("resources/assets/sass/show-user-content.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/show-tweet.js"
    ], "public/js/show-tweet.js")
    .sass("resources/assets/sass/show-tweet.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/tag.js"
    ], "public/js/tag.js")
    .sass("resources/assets/sass/tag.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/user-page.js"
    ], "public/js/user-page.js")
    .sass("resources/assets/sass/user-page.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/user-configuration.js"
    ], "public/js/user-configuration.js")
    .sass("resources/assets/sass/user-configuration.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/login.js"
    ], "public/js/login.js")
    .sass("resources/assets/sass/login.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/register.js"
    ], "public/js/register.js")
    .sass("resources/assets/sass/register.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/error.js"
    ], "public/js/error.js")
    .sass("resources/assets/sass/error.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/create-password.js"
    ], "public/js/create-password.js")
    .sass("resources/assets/sass/create-password.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/reset-password.js"
    ], "public/js/reset-password.js")
    .sass("resources/assets/sass/reset-password.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/email-password.js"
    ], "public/js/email-password.js")
    .sass("resources/assets/sass/email-password.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/following.js"
    ], "public/js/following.js")
    .sass("resources/assets/sass/following.scss", "public/css")

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/notifications.js"
    ], "public/js/notifications.js")
    .sass("resources/assets/sass/notifications.scss", "public/css")

    .js("resources/assets/js/welcome.js", "public/js")
    .sass("resources/assets/sass/welcome.scss", "public/css")
    .scripts([
        'node_modules/persian-datepicker/assets/persian-date.min.js',
    ], 'public/js/persian-date.min.js')

    .js([
        "resources/assets/js/app.js",
        "resources/assets/js/live-score.js"
    ], "public/js/live-score.js")
    .sass("resources/assets/sass/live-score.scss", "public/css")

    // .browserSync('192.168.1.52:8000')

    .version();
;
