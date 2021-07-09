var htmlmin = require("gulp-htmlmin");
var gulp    = require("gulp");

gulp.task("compress", function () {
    var opts = {
        collapseWhitespace   : true,
        removeAttributeQuotes: true,
        removeComments       : true,
        minifyJS             : false,
    };

    return gulp.src("./storage/framework/views/*")
        .pipe(htmlmin(opts))
        .pipe(gulp.dest("./storage/framework/views/"));
});