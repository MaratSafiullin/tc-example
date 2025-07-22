<?php

namespace App\Providers;

use App\Http\Core\Requests\Keys;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Request::macro('perPage', function (int $default = 10): int {
            $perPage = (int) $this->get(Keys::PER_PAGE);

            if ($perPage >= 1 && $perPage <= 100) {
                return $perPage;
            }

            return $default;
        });
    }
}
