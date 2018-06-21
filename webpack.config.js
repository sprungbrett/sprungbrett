/* eslint-disable */
const path = require('path');
const webpackConfig = require('./vendor/sulu/sulu/webpack.config.js');

module.exports = (env, argv) => {
    const config = webpackConfig(env, argv);
    config.entry.unshift(path.resolve(__dirname, 'src/Bundle/CourseBundle/Resources/js/index.js'));

    config.output.path = path.resolve('public');

    return config;
};

