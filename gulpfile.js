var gulp = require('gulp');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var cleanCss = require('gulp-clean-css');

var scripts =  [
    './node_modules/datatables.net/js/jquery.dataTables.js',
    './assets-src/scripts/datatables.manager.js',
    './assets-src/scripts/init.js'
];

var scripts_bootstrap = [
    './node_modules/datatables.net/js/jquery.dataTables.js',
    './node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
    './assets-src/scripts/datatables.manager.js',
    './assets-src/scripts/init.js'
];


var conf = {
    bootstrap: ['./node_modules/datatables.net-bs/css/*.css', './node_modules/datatables.net-bs/css/*.scss'],
    fontAwesome: ['./assets-src/sass/datatables_font_awesome.scss']
};

gulp.task('scripts', function () {
    return gulp.src(scripts, {base: '.'})
        .pipe(concat('datatables.js'))
        .pipe(gulp.dest('./assets-src/compiled/'))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .pipe(gulp.dest('./src/Resources/public/js/'));
});

gulp.task('scripts-bootstrap', function () {
    return gulp.src(scripts_bootstrap, {base: '.'})
        .pipe(concat('datatables-bootstrap.js'))
        .pipe(gulp.dest('./assets-src/compiled/'))
        .pipe(rename({extname: '.min.js'}))
        .pipe(uglify())
        .pipe(gulp.dest('./src/Resources/public/js/'));
});


gulp.task('sass-bootstrap', function(done){
    gulp.src(conf.bootstrap)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('datatables-bootstrap.css'))
        .pipe(cleanCss())
        .pipe(rename({extname: '.min.css'}))
        .pipe(gulp.dest('./src/Resources/public/css/'))
        .on('end', done)
});

gulp.task('sass-font-awesome', function(done){
    gulp.src(conf.fontAwesome)
        .pipe(sass().on('error', sass.logError))
        .pipe(concat('datatables-font-awesome.css'))
        .pipe(cleanCss())
        .pipe(rename({extname: '.min.css'}))
        .pipe(gulp.dest('./src/Resources/public/css/'))
        .on('end', done)
});

gulp.task('watch', function(){
    gulp.watch('./assets-src/scripts/*.js', ['scripts', 'scripts-bootstrap']);
    gulp.watch('./assets-src/scripts/*.scss', ['sass-bootstrap', 'sass-font-awesome'])
});

gulp.task('default', ['scripts', 'scripts-bootstrap', 'sass-bootstrap', 'sass-font-awesome']);
