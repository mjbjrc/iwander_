<?php


namespace App\Providers;

use App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      // if ($this->app->environment() !== 'production') {
      //     $this->app->register(IdeHelperServiceProvider::class);
      // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Schema::defaultStringLength(191);
      $environment = App::environment();

      if($environment === "production") {
          \URL::forceScheme('https');
        }
    }
}
