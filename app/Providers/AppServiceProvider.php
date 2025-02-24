<?php

namespace App\Providers;

use App\Interfaces\MarketRepositoryInterface;
use App\Repositories\MarketRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\DocumentRepository;
use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\DocumentRepositoryInterface;
use App\Interfaces\IndustryRepositoryInterface;
use App\Repositories\IndustryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(DocumentRepositoryInterface::class, DocumentRepository::class);
        $this->app->bind(MarketRepositoryInterface::class, MarketRepository::class);
        $this->app->bind(IndustryRepositoryInterface::class, IndustryRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
