<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\View;
use App\Models\Organization;
use App\Models\User;
use App\Models\Room;
use App\Models\UserOrganization;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('decimal', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\d+(\.\d{1,2})?$/', $value);
        });

        Validator::replacer('decimal', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute must be a valid decimal number with up to two decimal places.');
        });
        // View::composer('*', function ($view) {
        //     if (auth()->check()) {
        //         $view->with('profile', Details::find(auth()->user()->id));
        //     } else {
        //         $view->with('profile', null); // or handle as needed
        //     }
        // });

        View::composer('*', function ($view) {
            if (auth()->check()) {
                // Fetch user profile
                $profile = User::where('id', auth()->user()->id)->with('gcash')->first();
                $view->with('profile', $profile);

                // Fetch user organization
                $organization = UserOrganization::where('user_id', auth()->user()->id)
                    ->whereNotNull('organization_id')
                    ->with('userorganization_organization') // Check if organization_id is not null
                    ->first();
                $view->with('organization', $organization);

                // Fetch inbox messages
                $inbox = Room::with('user')->where('seller_id', auth()->user()->id)->get();
                $view->with('inbox', $inbox);
            } else {
                $view->with('profile', null);
                $view->with('organization', null);
                $view->with('inbox', null);
            }
        });
    }
}
