<?php

namespace App\Http\Controllers;

use Agent;

use App\Models\User;
use App\Models\Player;
use App\Http\Resources\UserResource;
use App\Http\Resources\PlayerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Create a new user.
     *
     * @return json of User and token
     */
    public function register(Request $request)
    {
        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'between:9,255', PasswordRule::min(9)->numbers()],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => 'No name!',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $device_name = $request->device_name;

        return response()
            ->json([
                'token' => $user->createToken($device_name)->plainTextToken,
                'user' =>   new UserResource($user)
                ]);
    }

    /**
     * Create a new user with player role.
     *
     * @return json of player and token
     */
    public function player(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'room' => ['required', 'string', 'max:255']
        ]);

        $player = Player::create([
            'name' => $request->name,
            'room' => $request->room
        ]);

        $device_name = $request->device_name;

        return response()
            ->json([
                'token' => $player->createToken($device_name)->plainTextToken,
                'user' =>   new PlayerResource($player)
                ]);
    }


    /**
     * Login a new user.
     *
     * @return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
          
        $user = User::where('email', $request->email)->first();
          
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
              'email' => ['The provided credentials are incorrect.'],
            ]);
        }
          
        return $user->createToken($request->device_name)->plainTextToken;
    }

    /**
     * Create room and save it on user model.
     *
     * @return room
     */
    public function createRoom(Request $request)
    {
      do {
        $room['room'] = $this->generateRoomName(4);
        $validator = Validator::make($room, [
          'room' => ['string', 'max:4', 'unique:users,room']
        ]);
        $result = $validator->fails();
      } while ($result);

      $user = auth()->user();
      $user->room = $room['room'];
      $user->save();
      return $user->room;
    }

    /**
     * Generate a new room name.
     *
     * @return room
     */
    function generateRoomName($length = 4) {
      $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
}
