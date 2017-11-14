'use strict';

module.exports = function(grunt) {
    var DIST_FOLDER = './dist/';
    var SCRIPT_SRC_FOLDER = './script/';
    var SCRIPT_DIST_FOLDER = DIST_FOLDER + 'script/';
    var STYLE_DIST_FOLDER = DIST_FOLDER + 'style/';
    var IMAGES_DIST_FOLDER = DIST_FOLDER + 'images/';

    require('load-grunt-tasks')(grunt, { pattern: ['grunt-*', '@*/grunt-*', 'gruntify-*'] });

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        autoprefixer: {
            options: {
                browsers: [
                    '> 1%',
                    'last 3 versions',
                    'last 4 Android versions',
                    'last 5 iOS versions'
                ]
            },
            multiple_files: {
                expand: true,
                flatten: true,
                src: STYLE_DIST_FOLDER + '*.min.css',
                dest: STYLE_DIST_FOLDER
            }
        },
        browserify: {
            dist: {
                options: {
                    transform: [
                        ['babelify', {
                            presets: 'es2015',
                            plugins: ['transform-object-rest-spread']
                        }]
                    ],
                    browserifyOptions: {
                        debug: true
                    }
                },
                src: SCRIPT_SRC_FOLDER + 'script.es6',
				dest: SCRIPT_DIST_FOLDER + 'script.min.js'
            }
        },
        clean: {
            scriptDev: SCRIPT_DIST_FOLDER + '*.min.*',
            scriptProd: SCRIPT_DIST_FOLDER + '*.min.*',
            styleDev: STYLE_DIST_FOLDER + '*.min.*',
            styleProd: STYLE_DIST_FOLDER + '*.min.*'
        },
        eslint: {
            src: ['./script/**/*.es6']
        },
        sass: {
            dev: {
                options: {
                    sourceMap: true,
                    outputStyle: 'expanded'
                },
                files: {
                    [STYLE_DIST_FOLDER + 'style.css']:   './scss/style.scss'
                }
            },
            prod: {
                options: {
                    sourceMap: false,
                    outputStyle: 'compressed'
                },
                files: {
                    [STYLE_DIST_FOLDER + 'style.min.css']: './scss/style.scss'
                }
            }
        },
        sasslint: {
            options: {
                configFile: './.scss-lint.yml',
                maxWarnings: 2000
            },
            target: ['./scss/**/*.scss']
        },
        /*sprite:{
            all: {
                src: IMAGES_DIST_FOLDER + 'sprites/*.png',
                dest: IMAGES_DIST_FOLDER + 'spritesheet.png',
                destCss: './scss/_generic/_sprites.scss',
                algorithm: 'binary-tree',
                padding: 20
            }
        },*/
       /* uglify: {
            dist: {
                files: {
                    [SCRIPT_DIST_FOLDER + 'script.min.js']: SCRIPT_DIST_FOLDER + 'script.dev.js'
                }
            }
        },*/
        watch: {
            options: {
                livereload: 35100
            },
            style: {
                files: ['./scss/**/*.scss'],
                tasks: ['style:dev']
            },
            script: {
                files: ['./script/**/*.es6'],
                tasks: ['script:dev']
            },
            php: {
                files: ['**/*.php']
            }
        }
    });

    grunt.registerTask('script:dev', ['eslint', 'clean:scriptDev', 'browserify']);
    grunt.registerTask('script:prod', ['eslint', 'clean:scriptProd', 'browserify', 'uglify']);
    grunt.registerTask('style:dev', ['sasslint', 'clean:styleDev', 'sass:dev', 'sass:prod']);
    grunt.registerTask('style:prod', ['sasslint', 'clean:styleProd', 'sass:prod', 'autoprefixer']);

    grunt.registerTask('build:dev', [/*'sprite',*/ 'style:dev', 'script:dev']);
    grunt.registerTask('build:prod', [/*'sprite',*/ 'style:prod', 'script:prod']);

    grunt.registerTask('default', ['build:dev']);
};
