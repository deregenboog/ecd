const Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    .enableVersioning(true)
    .copyFiles([
        {from: 'node_modules/ckeditor4/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: 'node_modules/ckeditor4/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: 'node_modules/ckeditor4/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: 'node_modules/ckeditor4/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: 'node_modules/ckeditor4/skins', to: 'ckeditor/skins/[path][name].[ext]'},
        {from: 'node_modules/ckeditor4/vendor', to: 'ckeditor/vendor/[path][name].[ext]'},
    ])
    // Uncomment the following line if you are using Webpack Encore <= 0.24
    // .addLoader({test: /\.json$/i, include: [require('path').resolve(__dirname, 'node_modules/ckeditor')], loader: 'raw-loader', type: 'javascript/auto'})

    //copy images
    .copyFiles({
        from: './assets/images/',
        to: 'public/images/[path][name].[ext]'
    })

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
    .addEntry('signin','./assets/css/signin.css')

    .addEntry('inloop-registraties-index','./assets/js/inloop/registraties/index.js')
    .addEntry('inloop-registraties-active','./assets/js/inloop/registraties/active.js')
    .addEntry('inloop-registraties-history','./assets/js/inloop/registraties/history.js')

    .addEntry('oekraine-registraties-index','./assets/js/oekraine/registraties/index.js')
    .addEntry('oekraine-registraties-active','./assets/js/oekraine/registraties/active.js')
    .addEntry('oekraine-registraties-history','./assets/js/oekraine/registraties/history.js')

    .splitEntryChunks()

    .addEntry('calendar', './assets/js/calendar/index.js')

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // enables Sass/SCSS support
    // .enableSassLoader()
    // .enableLessLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
