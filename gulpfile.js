// Load plugins
const gulp = require('gulp'),
    glob = require('glob'),
    source = require('vinyl-source-stream'),
    buffer = require('vinyl-buffer'),
    sass = require('gulp-sass'),
    tildeImporter = require('node-sass-tilde-importer'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    browserify = require('browserify'),
    babelify = require('babelify'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    livereload = require('gulp-livereload'),
    lr = require('tiny-lr'),
    server = lr();

var sassPaths = ['./node_modules'];

// Styles
gulp.task('styles', function () {
    return gulp.src('resources/styles/**/*.scss')
    .pipe(concat('app.css'))
    .pipe(sass({ style: 'expanded', includePaths: sassPaths, importer: tildeImporter}))
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
    return browserify({entries: glob.sync('resources/scripts/**/*.js')})
    .transform(babelify)
    .bundle()
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(gulp.dest('public/dist/scripts'))
    .pipe(uglify())
    .pipe(rename({'suffix': '.min'}))
    .pipe(livereload(server))
    .pipe(gulp.dest('public/dist/scripts'))
    .pipe(notify({message: 'Scripts task complete'}));
});

// Fonts
gulp.task('fonts', function () {
    return gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/**/*')
    .pipe(gulp.dest('public/dist/fonts'))
});

// CKEditor 5
gulp.task('ckeditor', function() {
    return gulp.src('node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')
    .pipe(gulp.dest('public/dist/scripts/'))
    .pipe(rename({'suffix': '.min'}))
    .pipe(gulp.dest('public/dist/scripts/'))
})

// Clean
gulp.task('clean', function() {
    return gulp.src(['public/dist/styles', 'public/dist/scripts', 'public/dist/fonts'], {read: false, allowEmpty: true})
    .pipe(clean());
});

// Default task
gulp.task('default', gulp.series('clean', 'styles', 'scripts', 'fonts'));

// Watch
gulp.task('watch', function () {

    // Listen on port 35729
    server.listen(35729, function (err) {
        if (err) {
            return console.log(err)
        }

        // Watch .scss files
        gulp.watch('resources/styles/**/*.scss', gulp.series(() => {
            console.log('Rebuilding styles...');
        }, 'styles'));

        // Watch .js files
        gulp.watch('resources/scripts/**/*.js', gulp.series(() => {
            console.log('Rebuilding styles...');
        }, 'scripts'));
    });
});
