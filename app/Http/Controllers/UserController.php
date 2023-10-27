<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{   
    public function storeAvatar(Request $request ){
     $request->validate([
        'avatar'=> 'required|image'
     ]);
     $user = auth()->user();
     $filename =$user->id . '_' . uniqid() . '.jpg';

    $imgData = Image::make( $request->file('avatar'))->fit(120)->encode('jpg');
    Storage::put('public/avatars/' . $filename,$imgData);

    $oldAvatar = $user->avatar;

    $user->avatar = $filename;
    $user->save();
    if($oldAvatar != "/fallback-avatar.jpg"){
        Storage::delete(str_replace("/storage/","/public",$oldAvatar));
    }
    return back()->with('success','Congrats for the new avatar');

    }


    public function showAvatarForm(){
        return view('avatar-form');
    }

    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(),'followerCount'=>$user->followers()->count(),'followingCount'=>$user->followingTheseUsers()->count()]);
    }


    public function profile(User $Tempuser){
      $this->getSharedData($Tempuser);
       

        return view('profile-post',['username'=>$Tempuser->username,'posts' => $Tempuser->posts()->latest()->get()]);
    }
    public function profilefollowers(User $Tempuser){
        $this->getSharedData($Tempuser);

        return view('profile-followers', ['followers' => $Tempuser->followers()->latest()->get()]);
    }

    public function profilefollowing(User $Tempuser){
        $this->getSharedData($Tempuser);
       return view('profile-following', ['following' => $Tempuser->followingTheseUsers()->latest()->get()]);
    }


  

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','You are logged out');
    }


    public function showCorrectHomePage(){

      if (  auth()->check()){
        return  view('homepage-feed',['posts' =>auth()->user()->feedPosts()->latest()->paginate(5)]);
      }
      else {
        return view('homepage');
      }

    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']]))
      
           {
            $request->session()->regenerate();
            return redirect('/')->with('success','You have successfuly logged in');} 
        else 
            {return redirect('/')->with('failure','invalid log in');}

    }


    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username'=>['required','min:3','max:20',Rule::unique('users','username')],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>['required','min:8','confirmed']
        ]);
        $incomingFields['password']= bcrypt($incomingFields['password']);

       $user = User::create($incomingFields);

       auth()->login($user);

        return redirect('/')->with('success','Thank you for creating an account');
    }
}
