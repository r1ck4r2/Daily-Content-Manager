<?php
/**
 * Dailyscript - app | web | media
 *
 *
 *
 * @category    Administracion
 * @package     Controllers
 * @author      Iván D. Meléndez
 * @copyright   Copyright (c) 2010 Dailyscript Team (http://www.dailyscript.com.co)
 * @version     1.0
 */

Load::models('usuario');
Load::models('post');

class UsuarioController extends AppController {

    public function before_filter() {
        if(Input::isAjax()) {
            View::template(null);
        }
    }

    public function index() {

    }

    public function entrar() {
        $usuario = new Usuario();
        $usuario->entrar();
    }

    public function salir() {
        $usuario = new Usuario();
        $usuario->salir();
    }

    /**
     * Método para agregar un nuevo usuario
     */
    public function agregar() {
        Flash::info(Router::get('module'));
        //Titulo de la página
        $this->title = 'Nueva usuario';
        //Ckeck de los radios para habilitar comentarios
        // $this->check_si = (HABILITAR_USUARIO) ? false : true;
        // $this->check_no = (disabled) ? true : false;
        $this->check_si = true;
        $this->check_no = false;

        //Array para determinar la visibilidad de los post
        $this->tipo = array(
            Grupo::ADMINISTRADOR => 'Administrador',
            Grupo::AUTOR => 'Autor',
            Grupo::COLABORADOR => 'Colaborador',
            Grupo::EDITOR => 'Editor'
            );

        //Verifico si ha enviado los datos a través del formulario
        if(Input::hasPost('usuario')) {
            //Verifico que el formulario coincida con la llave almacenada en sesion
            if(SecurityKey::isValid()) {
                Load::models('usuario');
                $usuario = new Usuario(Input::post('usuario'));
                $resultado = $usuario->registrarUsuario();
                if($resultado) {
                    View::select('usuario');
                }/* else {
                    //Hago persitente los datos
                    $this->categoria = Input::post('categorias');
                    $this->etiquetas = Input::post('etiquetas');
                }
                $this->post = $post;*/
            } else {
                Flash::info('La llave de acceso ha caducado. Por favor intente nuevamente.');
            }
        }
    }

    /**
     * Método para agregar un nuevo usuario
     */
    public function perfil() {
        Flash::info(Router::get('module'));
        $usuario = new Usuario();

        $dataUsuario = $usuario->buscarUsuario(Session::get('id'), '');

        //Titulo de la página
        $this->title = "Modificar tu Perfil";

        $this->nombre = $dataUsuario->nombre;
        $this->apellido = $dataUsuario->apellido;
        $this->email = $dataUsuario->mail;
        $this->login = $dataUsuario->login;

        //Array para determinar la visibilidad de los post
        $this->tipo = array(
            Grupo::ADMINISTRADOR => 'Administrador',
            Grupo::AUTOR => 'Autor',
            Grupo::COLABORADOR => 'Colaborador',
            Grupo::EDITOR => 'Editor'
            );

        //Verifico si ha enviado los datos a través del formulario
        if(Input::hasPost('usuario')) {
            //Verifico que el formulario coincida con la llave almacenada en sesion
            if(SecurityKey::isValid()) {
                Load::models('usuario');
                $usuario = new Usuario(Input::post('usuario'));
                $resultado = $usuario->registrarUsuario();
                if($resultado) {
                    View::select('usuario');
                }
            } else {
                Flash::info('La llave de acceso ha caducado. Por favor intente nuevamente.');
            }
        }
    }

    public function eliminarTwitter($id) {
        $usuario = new Usuario();

        if (Session::get('id') != $id)  {
            Flash::error('No puedes eliminar una cuenta de Twitter que no es suya');
            $location = '/dc-admin/';
        } else {
            if ( $usuario->setTwitter($id) ){
                Flash::valid('Cuenta de Twitter borrada exitosamente');
            } else {
                Flash::error('Error al eliminar la cuenta de Twitter');
            }
            $location = Utils::getBack();
        }
        Router::redirect($location);
    }

    public function checkEmail(){
        $salida['status'] = "ERROR";

        if ( Input::hasPost('email') ) {

            $email = Input::post('email');
            Load::model('usuario');
            $usuario = new Usuario();

            if ( !$usuario->buscarEmail($email) ) {
                $salida['status'] = "OK";
            }

        }

        View::template(null);
        View::response('json');
        print json_encode($salida);
    }

    public function checkLogin(){
        $salida['status'] = "ERROR";

        if ( Input::hasPost('login') ) {

            $login = Input::post('login');
            Load::model('usuario');
            $usuario = new Usuario();

            if ( !$usuario->buscarLogin($login) ) {
                $salida['status'] = "OK";
            }

        }

        View::template(null);
        View::response('json');
        print json_encode($salida);
    }
}
?>
