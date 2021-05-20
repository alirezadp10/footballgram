<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\PostHelper;
use App\Http\Controllers\Controller;
use App\Image;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use MetzWeb\Instagram\Instagram;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    private $postHelper;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PostHelper $postHelper)
    {
        $this->middleware('guest')->except('logout');
        $this->postHelper = $postHelper;
    }

    /**
     * Redirect the user to the Instagram authentication page.
     *
     *
     * @throws \Exception
     */
    public function instagramRedirectToProvider()
    {
        $instagram = new Instagram([
            'apiKey'      => env('INSTAGRAM_CLIENT_ID'),
            'apiSecret'   => env('INSTAGRAM_CLIENT_SECRET'),
            'apiCallback' => env('INSTAGRAM_CLIENT_REDIRECT'),
        ]);

        return redirect($instagram->getLoginUrl());
    }

    /**
     * Obtain the user information from Instagram.
     *
     * @throws \Exception
     */
    public function instagramHandleProviderCallback()
    {
        $instagram = new Instagram([
            'apiKey'      => env('INSTAGRAM_CLIENT_ID'),
            'apiSecret'   => env('INSTAGRAM_CLIENT_SECRET'),
            'apiCallback' => env('INSTAGRAM_CLIENT_REDIRECT'),
        ]);

        $data = $instagram->getOAuthToken($_GET['code']);

        // get all user likes
//        $likes = $instagram->getUserLikes();

        try{
            $user = User::where('instagram_id',$data->user->id)->first();

            if (!is_null($user)){
                Auth::loginUsingId($user->id);
                return redirect(route('users.index'));
            }
            else {
                DB::beginTransaction();

                $userFind = User::where('username',$data->user->username)->first();
                $user = User::create([
                    'name'         => $data->user->full_name,
                    'username'     => is_null($userFind) ? $data->user->username : '',
                    'bio'          => $data->user->bio,
                    'instagram_id' => $data->user->id,
                    'password'     => bcrypt('1234'),
                ]);

                $user->userActions()->attach([1,3,6,7]);

                $info = pathinfo($data->user->profile_picture);
                $contents = file_get_contents($data->user->profile_picture);
                $file = '/tmp/' . $info['basename'];
                file_put_contents($file, $contents);
                $uploaded_file = new UploadedFile($file, $info['basename']);


                $original = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/original'
                );

                $xs = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/xs',
                    50,50
                );

                $sm = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/sm',
                    100,100
                );

                $md = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/md',
                    200,200
                );

                $lg = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/lg',
                    300,300
                );

                Image::create([
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'original'       => $original,
                ]);

                DB::commit();

                Auth::loginUsingId($user->id);
                return redirect(route('password.create'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('instagram login fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'عملیات ثبت نام متاسفانه با شکست مواجه شد. <br> لطفا ثبت نام را از طریق وب سایت انجام دهید.'
            );
            session()->flash('message.time', '15');
            return redirect(route('login'));
        }
    }

    /**
     * Redirect the user to the Telegram authentication page.
     *
     *
     * @throws \Exception
     */
    public function telegramRedirectToProvider()
    {
        return Socialite::with('telegram')->redirect();
    }

    /**
     * Obtain the user information from Telegram.
     *
     * @throws \Exception
     */
    public function telegramHandleProviderCallback()
    {
        $data = Socialite::driver('telegram')->user();

        try{
            $user = User::where('telegram_id',$data->id)->first();
            if (!is_null($user)){
                Auth::loginUsingId($user->id);
                return redirect(route('users.index'));
            }
            else {
                DB::beginTransaction();

                $userFind = User::where('username',$data->username)->first();
                $user = User::create([
                    'name'        => $data->first_name . $data->last_name,
                    'username'    => is_null($userFind) ? $data->username : '',
                    'telegram_id' => $data->id,
                    'password'    => bcrypt($data->token),
                    'email'       => $data->email,
                ]);

                $user->userActions()->attach([1,3,6,7]);

                $info = pathinfo($data->avatar);
                $contents = file_get_contents($data->avatar);
                $file = '/tmp/' . $info['basename'];
                file_put_contents($file, $contents);
                $uploaded_file = new UploadedFile($file, $info['basename']);

                $original = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/original'
                );

                $xs = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/xs',
                    50,50
                );

                $sm = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/sm',
                    100,100
                );

                $md = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/md',
                    200,200
                );

                $lg = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/lg',
                    300,300
                );

                Image::create([
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'original'       => $original,
                ]);

                DB::commit();

                Auth::loginUsingId($user->id);
                return redirect(route('password.create'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('telegram login fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'عملیات ثبت نام متاسفانه با شکست مواجه شد. <br> لطفا ثبت نام را از طریق وب سایت انجام دهید.'
            );
            session()->flash('message.time', '15');
            return redirect(route('login'));
        }
    }

    /**
     * Redirect the user to the Google authentication page.
     *
     *
     * @throws \Exception
     */
    public function googleRedirectToProvider()
    {
        return response()->json([
            'status'  => 'failed',
            'message' => 'دسترسی مجاز نیست',
        ]);
        return Socialite::with('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @throws \Exception
     */
    public function googleHandleProviderCallback()
    {
        return response()->json([
            'status'  => 'failed',
            'message' => 'دسترسی مجاز نیست',
        ]);

        $data = Socialite::driver('google')->user();


        dd($data);


        try{
            $user = User::where('telegram_id',$data->id)->first();

            if (!is_null($user)){
                Auth::loginUsingId($user->id);
                return redirect(route('users.index'));
            }
            else {
                DB::beginTransaction();

                $userFind = User::where('username',$data->username)->first();
                $user = User::create([
                    'name'        => $data->first_name . $data->last_name,
                    'username'    => is_null($userFind) ? $data->username : '',
                    'telegram_id' => $data->id,
                    'password'    => bcrypt($data->token),
                    'email'       => $data->email,
                ]);
                $user->userActions()->attach([1,3,6,7]);


                $info = pathinfo($data->avatar);
                $contents = file_get_contents($data->avatar);
                $file = '/tmp/' . $info['basename'];
                file_put_contents($file, $contents);
                $uploaded_file = new UploadedFile($file, $info['basename']);

                $original = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/original'
                );

                $xs = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/xs',
                    50,50
                );

                $sm = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/sm',
                    100,100
                );

                $md = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/md',
                    200,200
                );

                $lg = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/lg',
                    300,300
                );

                Image::create([
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'original'       => $original,
                ]);

                DB::commit();

                Auth::loginUsingId($user->id);
                return redirect(route('password.create'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('telegram login fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'عملیات ثبت نام متاسفانه با شکست مواجه شد. <br> لطفا ثبت نام را از طریق وب سایت انجام دهید.'
            );
            session()->flash('message.time', '15');
            return redirect(route('login'));
        }
    }

    /**
     * Redirect the user to the Twitter authentication page.
     *
     *
     * @throws \Exception
     */
    public function twitterRedirectToProvider()
    {
        return response()->json([
            'status'  => 'failed',
            'message' => 'دسترسی مجاز نیست',
        ]);
        return Socialite::with('twitter')->redirect();
    }

    /**
     * Obtain the user information from Twitter.
     *
     * @throws \Exception
     */
    public function twitterHandleProviderCallback()
    {
        return response()->json([
            'status'  => 'failed',
            'message' => 'دسترسی مجاز نیست',
        ]);

        $data = Socialite::driver('twitter')->user();


        dd($data);


        try{
            $user = User::where('telegram_id',$data->id)->first();

            if (!is_null($user)){
                Auth::loginUsingId($user->id);
                return redirect(route('users.index'));
            }
            else {
                DB::beginTransaction();

                $userFind = User::where('username',$data->username)->first();
                $user = User::create([
                    'name'        => $data->first_name . $data->last_name,
                    'username'    => is_null($userFind) ? $data->username : '',
                    'telegram_id' => $data->id,
                    'password'    => bcrypt($data->token),
                    'email'       => $data->email,
                ]);
                $user->userActions()->attach([1,3,6,7]);


                $info = pathinfo($data->avatar);
                $contents = file_get_contents($data->avatar);
                $file = '/tmp/' . $info['basename'];
                file_put_contents($file, $contents);
                $uploaded_file = new UploadedFile($file, $info['basename']);

                $original = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/original'
                );

                $xs = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/xs',
                    50,50
                );

                $sm = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/sm',
                    100,100
                );

                $md = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/md',
                    200,200
                );

                $lg = $this->postHelper->storeImage(
                    $uploaded_file,
                    'public/user/lg',
                    300,300
                );

                Image::create([
                    'imageable_type' => 'App\User',
                    'imageable_id'   => $user->id,
                    'xs'             => $xs,
                    'sm'             => $sm,
                    'md'             => $md,
                    'lg'             => $lg,
                    'original'       => $original,
                ]);

                DB::commit();

                Auth::loginUsingId($user->id);
                return redirect(route('users.index'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error('telegram login fails because of: ' . $e->getMessage() . ' on line: ' . $e->getLine() . 'in file: ' . $e->getFile());
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'عملیات ثبت نام متاسفانه با شکست مواجه شد. <br> لطفا ثبت نام را از طریق وب سایت انجام دهید.'
            );
            session()->flash('message.time', '15');
            return redirect(route('login'));
        }
    }

    public function createPassword()
    {
        return view('auth.passwords.create');
    }

    public function storePassword()
    {
        $this->validate(request(), [
            'password'     => 'required|confirmed|min:6',
        ], [
            'password.required'     => 'وارد کردن رمز عبور کنونی الزامی است !',
            'password.confirmed'    => 'لطفا در وارد کردن تکرار رمز عبور دقت فرمایید !',
            'password.min'          => 'رمز عبور حداقل باید ۶ حرف باشد !',
        ]);

        Auth::user()->update([
            'password'  => bcrypt(request('password'))
        ]);

        return redirect(route('users.index'));
    }

}
