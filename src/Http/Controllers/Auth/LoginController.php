<?php


namespace Tsung\NovaUserManagement\Http\Controllers\Auth;


use Illuminate\Http\Request;
use Laravel\Nova\Http\Controllers\LoginController as NovaLoginController;

class LoginController extends NovaLoginController
{
    protected function authenticated(Request $request, $user)
    {
        if($user->administrator() || $user->can('viewNova')) {
            return redirect(config('nova.path'));
        }

        return redirect('/');
    }

    private function isActive($user)
    {
        $userModel = config('auth.providers.users.model');
        $user = $userModel::where($this->username(), $user)->first();
        if($user) {
            return $user->is_active;
        }
        return false;
    }

    public function login(Request $request)
    {
        $active = $this->isActive($request->only($this->username()));
        if( !$active) {
            return $this->sendFailedLoginResponse($request);
        }

        return parent::login($request);
    }
}
