var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('./build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .enableVersioning(true)
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')
    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('global','./assets/css/global.scss')
    .addEntry('signin','./assets/css/signin.css')

    .addEntry('inloopindex','./bundles/inloop/js/registraties/index.js')
    .addEntry('inloopactive','./bundles/inloop/js/registraties/active.js')
    .addEntry('inloophistory','./bundles/inloop/js/registraties/history.js')

    .addEntry('oekraineindex','./bundles/oekraine/js/registraties/index.js')
    .addEntry('oekraineactive','./bundles/oekraine/js/registraties/active.js')
    .addEntry('oekrainehistory','./bundles/oekraine/js/registraties/history.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment if you use Sass/SCSS files
.enableSassLoader()
.enableLessLoader()

// uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
    })
;

module.exports = Encore.getWebpackConfig();