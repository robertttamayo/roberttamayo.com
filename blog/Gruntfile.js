
module.exports = function(grunt) {

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        //check javascript
        jshint: {
            options: {
                "bitwise": true,
                "browser": true,
                "curly": true,
                "eqeqeq": true,
                "eqnull": true,
                "esnext": true,
                "immed": true,
                "jquery": true,
                "latedef": true,
                "newcap": true,
                "noarg": true,
                "node": true,
                "strict": false,
                "trailing": true,
                "undef": true,
                "globals": {
                    "jQuery": true,
                    "alert": true
                }
            },
            all: [
                'assets/js/*.js'
            ]
        },

        //concat js files into 2 files, one for desktop, one for mobile
        concat: {
            options: {
                separator: ';'
            },
            core: {
                src: ['assets/js/*.js'],
                dest: 'temp/concat.js',
            }
        },

        //minify js and output 2 .min.js files
        uglify: {
            options: {
                banner: '/*RedCodeBlueCode*/'
            },
            dist: {
                files: {
                    'assets/js/min/core.min.js': ['temp/concat.js']
                }
            }
        },

        //compile sass
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'assets/css/core.css': 'assets/css/core.scss'
                }
            }
        },

        //minify css
        cssmin: {
            dist: {
                options: {
                    banner: 'RedCodeBlueCode'
                },
                files: {
                    'assets/css/min/core.min.css': ['assets/css/*.css'],
                }
            }
        },

        //run tasks again on changes in js and css files
        watch: {
          files: ['<%= concat.core.src %>', 'Gruntfile.js', 'assets/css/*.css', 'assets/css/*.scss', 'assets/css/sass/*.scss'],
          tasks: ['concat', 'uglify', 'sass', 'cssmin']
        },

        //check php, manually executed
        phpcs: {
            application: {
                dir: '/includes/model/*.php'
            },
            options: {
                bin: '/usr/bin/phpcs'
            }
        },
    });

    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['concat', 'uglify', 'sass', 'cssmin','watch']);

};
