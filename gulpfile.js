// Load plugins
const gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    livereload = require('gulp-livereload'),
    lr = require('tiny-lr'),
    server = lr();

// Styles
gulp.task('styles', function () {
    return gulp.src('resources/styles/**/*.sass')
    .pipe(sass({ style: 'expanded', }))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest('public/dist/styles'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(livereload(server))
    .pipe(gulp.dest('public/dist/styles'))
    .pipe(notify({ message: 'Styles task complete' }));
});

// Scripts
gulp.task('scripts', function () {
    return gulp.src('resources/scripts/**/*.js')
    .pipe(concat('main.js'))
    .pipe(gulp.dest('public/dist/scripts'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(livereload(server))
    .pipe(gulp.dest('public/dist/scripts'))
    .pipe(notify({message: 'Scripts task complete'}));
});

// Clean
gulp.task('clean', function() {
    return gulp.src(['public/dist/styles', 'public/dist/scripts', /*'public/dist/images'*/], {read: false, allowEmpty: true})
    .pipe(clean());
});

// Default task
gulp.task('default', gulp.series('clean', 'styles', 'scripts'));

// Watch
gulp.task('watch', function() {

    // Listen on port 35729
    server.listen(35729, function (err) {
        if (err) {
            return console.log(err)
        };

        // Watch .scss files
        gulp.watch('resources/styles/**/*.scss', gulp.series(() => { console.log('Rebuilding styles...') }, 'styles'));

        // Watch .js files
        gulp.watch('resources/scripts/**/*.js', gulp.series(() => { console.log('Rebuilding styles...') }, 'scripts'));
    });
});
