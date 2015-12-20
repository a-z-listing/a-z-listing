require('es6-promise').polyfill();

var gulp = require('gulp');
var sourcemaps = require('gulp-sourcemaps');

var paths = {
	styles: ['css/*.scss']
};

gulp.task('styles', function () {
	var sass = require('gulp-sass');
	var postcss = require('gulp-postcss');

	return gulp.src(paths.styles)
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(postcss([ require('autoprefixer') ]))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('css/'));
});

gulp.task('default', ['styles']);
