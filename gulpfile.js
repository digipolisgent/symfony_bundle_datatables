var gulp = require('gulp');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var scripts =  [
    './node_modules/datatables.net/js/jquery.dataTables.js',
    './node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
    './Resources/scripts/init.js'
];

gulp.task('scripts', function () {
    return gulp.src(scripts, {base: '.'})
        .pipe(concat('datatables.js'))
        .pipe(gulp.dest('./Resources/scripts/'))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .pipe(gulp.dest('./Resources/Public/js/'));
});

gulp.task('default', ['scripts']);
