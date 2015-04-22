module.exports = (grunt) ->

	# Project configuration
	grunt.initConfig
		pkg: grunt.file.readJSON 'package.json'

		watch:
			options:
				livereload: true

#			styles:
#				files: ['css/less/*']
#				tasks: ['less']
#
#			coffeescript:
#				files: ['js/coffee/*']
#				tasks: ['coffee']
#
#			html:
#				files: ['index.html', 'cookies.html']
#
			all:
				files: ['css/less/**', '!node_modules/**', '**.php']
				tasks: ['less:development']

		less:
			development:
				options:
					paths: ['less']
					cleancss: false
					sourceMap: true
					sourceMapFilename: 'css/map.map'
					sourceMapRootpath: '/filmsoc/'

				files:
					'css/main.css': 'css/less/main.less'

			build:
				options:
					paths: ['less']
					cleancss: true
					sourceMap: false

				files:
					'build/css/main.css': 'css/less/main.less'

		copy:
			build:
				files: [
					{
						expand:true
						src: ['**', '!build/**', '!package.json', '!Gruntfile.coffee', '!js/coffee/**', '!*.bat', '!node_modules/**', '!css/', '!css/**', '!posters/**', '!branding/**']
						dest: 'build/'
						filter: 'isFile'
					}
				]

		'ftp-deploy':
			# build:
			# 	auth:
			# 		host: 'ftp.dur.ac.uk'
			# 		port: 21
			# 		authKey: 'testing'
			# 	src: 'build'
			# 	dest: '/home/hudson/ug/chqx69/public_html/filmsoc'
			# 	exclusions: ['.DS_Store']
			deploy:
				auth:
					host: 'ftp.dur.ac.uk'
					port: 21
					authKey: 'deployment'
				src: 'build'
				dest: '/home/hudson/misc/dhb8hbf/public_html'
				exclusions: ['.DS_Store', '**/editThisFileToChangePassword.txt', 'branding/**']




	grunt.loadNpmTasks 'grunt-contrib-less'
	grunt.loadNpmTasks 'grunt-contrib-watch'
	grunt.loadNpmTasks 'grunt-contrib-concat'
	grunt.loadNpmTasks 'grunt-contrib-copy'
	grunt.loadNpmTasks 'grunt-ftp-deploy'

	# Default task(s)
	grunt.registerTask 'default', ['watch']
	grunt.registerTask 'build', ['less:build', 'copy:build', 'ftp-deploy:build']
	grunt.registerTask 'deploy', ['less:build', 'copy:build', 'ftp-deploy:deploy']
