<?php


namespace Tsung\NovaUserManagement\Http\Controllers\Auth;


use Illuminate\Http\Request;

class LoginController extends \Laravel\Nova\Http\Controllers\LoginController
{
    protected function authenticated(Request $request, $user)
    {
        if($user->administrator() || $user->can('backend')) {
            return redirect(config('nova.path'));
        }

        return redirect('/');
    }

    private function isActive($user)
    {
        $userModel = config('novauser.gates.user.model');
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
