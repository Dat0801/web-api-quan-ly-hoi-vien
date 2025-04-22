<?php

namespace App\Providers;

use App\Interfaces\CertificateRepositoryInterface;
use App\Interfaces\BusinessRepositoryInterface;
use App\Interfaces\FieldRepositoryInterface;
use App\Interfaces\MarketRepositoryInterface;
use App\Interfaces\OrganizationRepositoryInterface;
use App\Repositories\CertificateRepository;
use App\Repositories\BusinessRepository;
use App\Repositories\FieldRepository;
use App\Repositories\MarketRepository;
use App\Repositories\OrganizationRepository;
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
use App\Interfaces\TargetCustomerGroupRepositoryInterface;  
use App\Repositories\IndustryRepository;
use App\Repositories\TargetCustomerGroupRepository;
use App\Interfaces\BoardCustomerRepositoryInterface;
use App\Repositories\BoardCustomerRepository;

use Illuminate\Database\Eloquent\Model;

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
        $this->app->bind(FieldRepositoryInterface::class, FieldRepository::class);
        $this->app->bind(BusinessRepositoryInterface::class, BusinessRepository::class);
        $this->app->bind(CertificateRepositoryInterface::class, CertificateRepository::class);
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(TargetCustomerGroupRepositoryInterface::class, TargetCustomerGroupRepository::class);
        $this->app->bind(BoardCustomerRepositoryInterface::class, BoardCustomerRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Model::preventLazyLoading();
    }
}
