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

    /**
     * melakukan cek user yang login masih aktif
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $active = $this->isActive($request->only($this->username()));
        if( !$active) {
            return $this->sendFailedLoginResponse($request);
        }

        return parent::login($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
