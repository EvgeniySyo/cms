module.exports = function(grunt) {
//	require('load-grunt-tasks')(grunt);
	domen = 'http://test2.ru/';
	template = 'templates/waterlightssite/';
	base = domen + 'main/'; //http://profstroy32-ru/templates/prof/index.html

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
			watch: {
			css: {
				files: [template + 'css/*.scss'],
				tasks: '',
				options: {
					spawn: false,
				}
			}
			},

		browserSync: {
			dev: {
				bsFiles: {
					src : [template + 'css/*.css', base, template + 'js/*.js']
				},
				options: {
					watchTask: true,
					proxy: base,
				}
			}
		}
	
});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-browser-sync');

//	grunt.registerTask('default', ['concat', 'uglify']);
	grunt.registerTask('css', ['sass', 'autoprefixer', 'cssmin']);
	grunt.registerTask('default', ['browserSync', 'watch']);
};