/*
Author: DAISY
Version: 1.0.0





*/

/** gulp */
const { src, dest, watch, series, parallel } = require("gulp");

/** HTML plugin */
const htmlbeautify = require("gulp-html-beautify");
var beautifyOptions = {
  indent_size: 2,
  preserve_newlines: false,
  indent_with_tabs: false,
};

/** sass plugin */
const sass = require("gulp-sass")(require("sass"));
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const mqpacker = require("css-mqpacker");
const cssdeclsort = require("css-declaration-sorter");
const cssnano = require("gulp-cssnano");

/** javascript plugin */
const babel = require("gulp-babel");
const uglify = require("gulp-uglify");

/** template plugin */
const nunjucksRender = require("gulp-nunjucks-render");
const data = require("gulp-data");
const path = require('path');
const replace = require('gulp-replace');

/** 相対パスで書き換える（src|href|action） */
const enableRewrite = true;

/** etc plugin */
const rename = require("gulp-rename");
const browserSync = require("browser-sync");

/** exports setting */
const paths = {
  src: "src/app/assets",
  assets: "src/app/assets",
};

/** task --------------------------------------*/

const scss = () => {
    return src([paths.src + "/sass/**/*.scss", "!" + paths.src + "/sass/**/_*.scss"])
    .pipe(sass({ outputStyle: "expanded" }).on('error', sass.logError))
    .pipe(postcss([
        autoprefixer(),
        mqpacker()
    ]))
    .pipe(dest(paths.assets + "/css"));
};

const watchFunc = () => {
    watch(paths.src + "/sass/**/*.scss", series(scss));
};

exports.default = series(
    series(scss),
    parallel(watchFunc)
);
