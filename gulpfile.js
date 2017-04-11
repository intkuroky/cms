var elixir = require('laravel-elixir');
var gulp = require('gulp');


/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

elixir(function (mix) {

  require('./app/CongCNN/Resources/Main/gulpfile.js').default(mix, gulp);
  require('./app/CongCNN/Resources/Account/gulpfile.js').default(mix, gulp);
  require('./app/CongCNN/Resources/Index/gulpfile.js').default(mix, gulp);

  mix.scripts([
    '../bower_components/jquery/dist/jquery.min.js',
    '../bower_components/angular/angular.min.js',
    '../bower_components/angular-aria/angular-aria.min.js',
    '../bower_components/angular-animate/angular-animate.min.js',
    '../bower_components/angular-route/angular-route.min.js',
    '../bower_components/angular-resource/angular-resource.min.js',
    // '../bower_components/angular-mocks/angular-mocks.js',
    // '../bower_components/angular-loading-bar/build/loading-bar.min.js',
    '../bower_components/bootstrap/dist/js/bootstrap.min.js',
    '../bower_components/AdminLTE/dist/js/app.min.js',
    '../bower_components/angular-ui-router/release/angular-ui-router.min.js',
    '../bower_components/angular-material/angular-material.min.js',
    '../bower_components/angular-local-storage/dist/angular-local-storage.min.js',
    '../bower_components/angular-smart-table/dist/smart-table.min.js',
    '../bower_components/fullpage.js/dist/jquery.fullpage.min.js',
    '../bower_components/angular-fullpage.js/angular-fullpage.js',
    '../bower_components/angular-ui-notification/dist/angular-ui-notification.js',
    '../bower_components/jshashes/hashes.min.js',
    '../bower_components/bcryptjs/dist/bcrypt.min.js',
    './resources/assets/js/app/*.js',
    './resources/assets/js/service/*.js'
  ], 'public/js/vendor.js');

  mix.styles([
    '../bower_components/bootstrap/dist/css/bootstrap.min.css',
    '../bower_components/sweetalert/dist/sweetalert.css',
    '../bower_components/AdminLTE/dist/css/skins/_all-skins.min.css',
    '../bower_components/font-awesome/css/font-awesome.min.css',
    '../bower_components/angular-material/angular-material.min.css',
    '../bower_components/fullpage.js/dist/jquery.fullpage.min.css',
    '../bower_components/angular-ui-notification/dist/angular-ui-notification.min.css',
    './resources/assets/css/app/'
  ], 'public/css/vendor.css');

  mix.copy('resources/assets/view/', 'public/view');

  mix.sass([
    'resources/assets/sass/'
  ], 'public/css/app.css');

  mix.copy('resources/assets/bower_components/bootstrap/dist/fonts/', 'public/fonts');
  mix.copy('resources/assets/bower_components/font-awesome/fonts/', 'public/fonts');
  mix.copy('resources/assets/fonts/', 'public/fonts');
  mix.copy('resources/assets/bower_components/adminLTE/dist/img', 'public/imgs');
  mix.copy('resources/assets/imgs/', 'public/imgs');
  mix.copy('resources/assets/bower_components/adminLTE/plugins', 'public/plugins');
});