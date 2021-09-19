const mix = require("laravel-mix");
const path = require('path');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath("public")
    .setResourceRoot("../") // Turns assets paths in css relative to css file
    // .options({
    //     processCssUrls: false,
    // })
    .sass("resources/sass/core/core.scss", "css/core.css")
    .sass("node_modules/dropzone/src/dropzone.scss", "css/dropzone.css")
    .sass("resources/sass/_global.scss", "css/fontawesome.css")
    .js("resources/js/mainApp.js", "js/core.js").vue()
    .extract([
        // Extract packages from node_modules to vendor.js
        "jquery",
        "bootstrap",
        "popper.js",
        "axios",
        "sweetalert2",
        "lodash"
    ])
    .sourceMaps();
mix.webpackConfig({
    resolve: {
        alias: {
            "@app": path.resolve(__dirname, "resources/js/crm/"),
            "@core": path.resolve(__dirname, "resources/js/core/")
        }
    }
});
if (mix.inProduction()) {
    mix.version().options({
        // Optimize JS minification process
        terser: {}
    });
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: "inline-source-map",
    });
}
