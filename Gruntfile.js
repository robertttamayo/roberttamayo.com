
module.exports = function(grunt) {

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

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
        
        copy: {
            blog_assets: {
                expand: true,
                flatten: true,
                filter: 'isFile',
                src: 'vendor/rioseo/ps-lib-hours/assets/js/*',
                dest: 'assets/js/vendor/'
                
            }, 
            blog_core: {
                expand: true,
                flatten: false,
                filter: 'isFile',
                cwd: 'vendor/b'
            },
            hours_model: {
                expand: true,
                flatten: false,
                filter: 'isFile',
                cwd: 'vendor/rioseo/ps-lib-hours/includes/model/',
                src: '**',
                dest: 'includes/model/vendor/'
            }, 
            hours_view: {
                expand: true,
                flatten: false,
                filter: 'isFile',
                cwd: 'vendor/rioseo/ps-lib-hours/includes/view/',
                src: '**',
                dest: 'includes/view/vendor/'
            }
        },

        //run tasks again on changes in js and css files
        watch: {
          files: ['Gruntfile.js', 'assets/css/*.css', 'assets/css/*.scss', 'assets/css/sass/*.scss'],
          tasks: ['uglify', 'sass', 'cssmin', 'copy']
        },

    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['uglify', 'sass', 'cssmin', 'copy', 'watch']);

};
