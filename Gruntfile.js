
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
                    'assets/js/min/core.min.js': ['assets/js/*.js']
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
                    'assets/css/core.css': 'assets/sass/core.scss'
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
            blog_core: {
                expand: true,
                flatten: false,
                cwd: 'vendor/rtamayo/bobblog/',
                src: '**',
                dest: 'blog/'
            }
        },

        //run tasks again on changes in js and css files
        watch: {
          files: ['Gruntfile.js', 'assets/css/*.css', 'assets/sass/*.scss', 'assets/css/sass/*.scss'],
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
