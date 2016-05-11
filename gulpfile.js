var gulp = require('gulp');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var cleanCss = require('gulp-clean-css');

var scripts =  [
    './node_modules/datatables.net/js/jquery.dataTables.js',
    './node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
    './assets-src/scripts/init.js'
];

var conf = {
    bootstrap: ['./node_modules/datatables.net-bs/css/*.css', './node_modules/datatables.net-bs/css/*.scss'],
    fontAwesome: ['./assets-src/sass/datatables_font_awesome.scss']
};

gulp.task('scripts', function () {
    return gulp.src(scripts, {base: '.'})
        .pipe(concat('datatables.js'))
        .pipe(gulp.dest('./assets-src/scripts/'))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .pipe(gulp.dest('./src/Phpro/DatatablesBundle/Resources/public/js/'));
});

gulp.task('sass-bootstrap', function(done){
    gulp.src(conf.bootstrap)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('datatables-bootstrap.css'))
        .pipe(cleanCss())
        .pipe(rename({extname: '.min.css'}))
        .pipe(gulp.dest('./src/Phpro/DatatablesBundle/Resources/public/css/'))
        .on('end', done)
});

gulp.task('sass-font-awesome', function(done){
    gulp.src(conf.fontAwesome)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('datatables-font-awesome.css'))
        .pipe(cleanCss())
        .pipe(rename({extname: '.min.css'}))
        .pipe(gulp.dest('./src/Phpro/DatatablesBundle/Resources/public/css/'))
        .on('end', done)
});

gulp.task('default', ['scripts', 'sass-bootstrap', 'sass-font-awesome']);
