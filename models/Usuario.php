<?php

namespace Model;

class Usuario extends ActiveRecord
{

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }




    //* VALIDAR INICIO DE SESION
    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        return self::$alertas;
    }




    //* VALIDACION DE FORMULARIO PARA CUENTAS NUEVAS
    public function validarNuevaCuenta()
    {

        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El Email es obligatorio";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
        }

        if ($this->password !== $this->password2) {
            self::$alertas['error'][] = "Los password son diferentes";
        }
        return self::$alertas;
    }


    //* VALIDA UN EMAIL
    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El Email es obligatorio';
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }

        return self::$alertas;
    }





    //* VALIDAR PERFIL
    public function validar_perfil()
    {

        if (!$this->nombre) {

            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->email) {

            self::$alertas['error'][] = 'El email es obligatorio';
        }


        return self::$alertas;
    }







    //* NUEVO PASSWORD DESDE PERFIL
    public function nuevoPassword()
    {

        if (!$this->password_actual) {
            self::$alertas['error'][] = 'Password Actual es obligatorio';
        }

        if (!$this->password_nuevo) {
            self::$alertas['error'][] = 'Nuevo Password es obligatorio';
        }


        if (strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'Nuevo Password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }






    //* VALIDAR NUEVO PASSWORD - OLVIDE PASSWORD
    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
        }

        return self::$alertas;
    }





    //* COMPRUEBA QUE EL PASSWORD ACTUAL SEA EL MISMO QUE EL GUARDADO EN LA BASE DE DATOS
    public function comprobarPassword(): bool
    {
        return password_verify($this->password_actual, $this->password);
    }





    //* HASHEAR PASSWORDS
    public function hashearPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }



    //* GENERAR TOKEN
    public function crearToken()
    {
        $this->token = uniqid();
    }
}
