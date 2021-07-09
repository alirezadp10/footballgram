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
        "resources/js/app.js",
        "resources/js/create-post.js"
    ], "public/js/create-post.js")
    .sass("resources/sass/create-post.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/preview-post.js"
    ], "public/js/preview-post.js")
    .sass("resources/sass/preview-post.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/show-post.js"
    ], "public/js/show-post.js")
    .sass("resources/sass/show-post.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/show-tweet.js"
    ], "public/js/show-tweet.js")
    .sass("resources/sass/show-tweet.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/tag.js"
    ], "public/js/tag.js")
    .sass("resources/sass/tag.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/user-page.js"
    ], "public/js/user-page.js")
    .sass("resources/sass/user-page.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/user-configuration.js"
    ], "public/js/user-configuration.js")
    .sass("resources/sass/user-configuration.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/login.js"
    ], "public/js/login.js")
    .sass("resources/sass/login.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/register.js"
    ], "public/js/register.js")
    .sass("resources/sass/register.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/error.js"
    ], "public/js/error.js")
    .sass("resources/sass/error.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/create-password.js"
    ], "public/js/create-password.js")
    .sass("resources/sass/create-password.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/reset-password.js"
    ], "public/js/reset-password.js")
    .sass("resources/sass/reset-password.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/email-password.js"
    ], "public/js/email-password.js")
    .sass("resources/sass/email-password.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/following.js"
    ], "public/js/following.js")
    .sass("resources/sass/following.scss", "public/css")

    .js([
        "resources/js/app.js",
        "resources/js/notifications.js"
    ], "public/js/notifications.js")
    .sass("resources/sass/notifications.scss", "public/css")

    .js("resources/js/index.js", "public/js")
    .sass("resources/sass/index.scss", "public/css")
    .scripts([
        'node_modules/persian-datepicker/persian-date.min.js',
    ], 'public/js/persian-date.min.js')

    .js([
        "resources/js/app.js",
        "resources/js/live-score.js"
    ], "public/js/live-score.js")
    .sass("resources/sass/live-score.scss", "public/css")

    // .browserSync('192.168.1.52:8000')

    .version();
;
