module.exports = function(grunt) {
    var package = require('./package.json'),
        options = package.options,
        moduleName = options.moduleName,
        version = package.version,
        pattern = new RegExp(/\$this->version = '(.*?)';/i),
        file = grunt.file.read('./' + moduleName + '.php');

        var matches = file.match(pattern);
        if (matches !== null && typeof matches[1] == 'string') {
            version = matches[1].trim();
        }

    grunt.initConfig({
        compress: {
            main: {
                options: {
                    archive: moduleName + '-v' + version + '.zip'
                },
                files: [
                    {src: ['controllers/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['classes/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['defaults/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['init/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['models/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['src/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['docs/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['override/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['logs/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['vendor/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['translations/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['upgrade/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['views/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: 'index.php', dest: moduleName + '/'},
                    {src: moduleName + '.php', dest: moduleName + '/'},
                    {src: 'logo.png', dest: moduleName + '/'},
                    {src: 'LICENSE.md', dest: moduleName + '/'},
                    {src: 'README.md', dest: moduleName + '/'},
                    {src: 'ajax.php', dest: moduleName + '/'},
                    // Legacy
                    {src: ['css/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['fonts/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['js/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: ['img/**'], dest: moduleName + '/', filter: 'isFile'},
                    {src: 'readme_en.txt', dest: moduleName + '/'}
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-compress');

    grunt.registerTask('default', ['compress']);
};