<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
//use App\Http\Requests\Users\UserRequest;

class UserController extends Controller
{
   public function register(Request $request)
   {

    // recibir los datos
    $json = $request->input('json', null);
    $params = json_decode($json); // tengo un objeto
    $params_array = json_decode($json, true); // obtengo un array
   
  
    // validar los datos
    if (!empty($params_array) && !empty($params)) {

        $params_array = array_map('trim', $params_array);

            $validate = \Validator::make($params_array, [
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email|unique:users',  // comprobar si el usuario existe
                'password' => 'required'
            ]);

                if ($validate->fails()) {
        $data = array('status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'error' => $validate->errors()
                );
                
                
                }else{

                    // si validación pasa correctamente

                     // cifrar contraseña
                 $pwd =   hash('sha256', $params->password);

                    $user = new User();
                    $user->name = $params_array['name'];
                    $user->surname = $params_array['surname'];
                    $user->email = $params_array['email'];
                    $user->password = $pwd;

                    //guardar el usuario

                    $user->save();
                       
                        // crear el usuario
        $data = array('status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente'
                );
                }

    }else {
        $data = array('status' => 'error',
        'code' => 404,
        'message' => 'Los datos enviados no son correctos'
    );

    }
    return response()->json($data, $data['code']);


   }

    public function login(Request $request)
    {

            $jwtAuth = new \JwtAuth();

            // recibir los datos por post
            
            $json = $request->input('json', null);
            $params = json_decode($json); // tengo un objeto
            $params_array = json_decode($json, true); // obtengo un array

        

            // validar los datos

            $validate = \Validator::make($params_array, [
                'email' => 'required|email',  // comprobar si el usuario existe
                'password' => 'required'
            ]);

            if ($validate->fails()) {
                $signup = array('status' => 'error',
                            'code' => 404,
                            'message' => 'El usuario no se ha podido loguear',
                            'error' => $validate->errors()
                        );
                    }else {

                        // cifrar contraseña
                        $pwd =   hash('sha256', $params->password);
                    $signup =  $jwtAuth->signup($params->email, $pwd);

                    if (!empty($params->gettoken)) {
                        $signup =  $jwtAuth->signup($params->email, $pwd, true);
                    }
                    }
        
            // devolver token o datos

        

        // return $jwtAuth->signup($email, $pwd, true);
            return response()->json( $signup, 200);

            
    }

    public function update(Request $request)
    {
                // comprobar que el usuario esté identificado
                    $token = $request->header('Authorization');
                    $jwtAuth = new \JwtAuth();

                    $checkToken =  $jwtAuth->checkToken($token);

                $json = $request->input('json', null);
            
                $params_array = json_decode($json, true); // obtengo un array

                    if ($checkToken && !empty($params_array)) {
                    // actualiar el usuario
                    // recoger datos por post

                

                    // sacar usuario identificado
                    $user =  $jwtAuth->checkToken($token, true);

                    // validar los datos

                    $validate = \Validator::make($params_array, [
                        'name' => 'required',
                        'surname' => 'required',
                        'email' => 'required|email|unique:users'.$user->sub,  // comprobar si el usuario existe
                    
                    ]);
                    // quitar los campos que no quieroa ctualizar
                    unset($params_array['id']);
                    unset($params_array['role']);
                    unset($params_array['password']);
                    unset($params_array['created_at']);
                    unset($params_array['remember_token']);
                    // actualizar usuario en DB

                    $user_update = User::where('id', $user->sub)->update(
                        $params_array
                    );
                    // devolver array con resultado

                        $data = array('status' => 'success',
                    'code' => 200,
                    'message' => $user,
                    'changes' => $params_array
                    );

                    }else {
                    // mensaje de error
                    $data = array('status' => 'error',
                    'code' => 400,
                    'message' => 'Usuario no está identificado'
                    );
                }

                    return response()->json($data, $data['code']);
            }

            public function upload(Request $request)
            {
                // recoger datos

                $image = $request->file('file0');

                // validar que el archivo sea una imagen

                $validate = \Validator::make($request->all(), 
                [
                    'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
                ]);

                // subir y guardar imagen
                if (!$image || $validate->fails()) {


                    $data = array('status' => 'error',
                    'code' => 400,
                    'message' => 'Error al subir imagen'
                );


                }else {
                    $image_name = time().$image->getClientOriginalName();
                    \Storage::disk('user_img')->put($image_name, \File::get($image));

                    $data = array('status' => 'success',
                    'code' => 200,
                    'image' => $image_name
                );
                }

            

            return response()->json($data, $data['code']);
        

    }

    public function getImage($filename)
    {

                $isset = \Storage::disk('user_img')->exists($filename);

                if ($isset) {
                    $file = \Storage::disk('user_img')->get($filename);
                    return new Response($file, 200);
                }else{
                    
                    $data = array('status' => 'error',
                    'code' => 404,
                    'message' => 'La imagen no existe'
                );

                return response()->json($data, $data['code']);
                }

            
    }

    public function detail($id)
    {

                    $user = User::find($id);

                    if (is_object($user)) {
                        $data = array('status' => 'success',
                        'code' => 200,
                        'user' =>  $user
                     );
                    }else {
                        $data = array('status' => 'error',
                        'code' => 400,
                        'message' =>  'El usuatio no existe'
                     );
                    }

                    return response()->json($data, $data['code']);
    }
}
