
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
                    'assets/css/core.css': 'assets/sass/core.scss',
                    'assets/css/blog.css': 'assets/sass/blog.scss',
                    'assets/css/alt.css': 'assets/sass/alt.scss'
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
                    'assets/css/min/core.min.css': ['assets/css/core.css'],
                    'assets/css/min/blog.min.css': ['assets/css/blog.css'],
                    'assets/css/min/alt.min.css': ['assets/css/alt.css'],
                }
            }
        },
        
        // copy: {
        //     blog_core: {
        //         expand: true,
        //         flatten: false,
        //         cwd: 'vendor/rtamayo/bobblog/',
        //         src: '**',
        //         dest: 'blog/'
        //     }
        // },

        //run tasks again on changes in js and css files
        watch: {
          files: ['Gruntfile.js', 'assets/css/*.css', 'assets/sass/*.scss', 'assets/css/sass/*.scss', 'assets/js/*.js'],
          tasks: ['uglify', 'sass', 'cssmin']
        },

    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['uglify', 'sass', 'cssmin', 'watch']);

};
