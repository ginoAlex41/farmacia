<?php
include_once 'Conexion.php';
class Usuario
{
  var $objetos;
  public function __construct()
  {
    $db = new Conexion();
    $this->acceso = $db->pdo;
  }
  function Loguearse($ci, $pass)
  {
    $sql = "SELECT * FROM usuario inner join tipo_us on us_tipo=id_tipo_us where ci_us=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':ci' => $ci));
    $objetos = $query->fetchAll();
    foreach ($objetos as $objeto) {
      $contrasena_actual = $objeto->contrasena_us;
    }
    if (strpos($contrasena_actual, '$2y$10$') === 0) {
      if (password_verify($pass, $contrasena_actual)) {
        return "logueado";
      }
    } else {
      if ($pass == $contrasena_actual) {
        return "logueado";
      }
    }
  }
  function obtener_dato_logueo($ci)
  {
    $sql = "SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us and ci_us=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':ci' => $ci));
    $this->objetos = $query->fetchall();
    return $this->objetos;
  }
  function obtener_datos($id)
  {
    $sql = "SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us and id_usuario=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id));
    $this->objetos = $query->fetchall();
    return $this->objetos;
  }
  function editar($id_usuario, $telefono, $residencia, $correo, $sexo, $obs)
  {
    $sql = "UPDATE usuario SET telefono_us=:telefono, residencia_us=:residencia,correo_us=:correo,sexo_us=:sexo,obs_us=:obs where id_usuario=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id_usuario, ':telefono' => $telefono, ':residencia' => $residencia, ':correo' => $correo, ':sexo' => $sexo, ':obs' => $obs));
  }
  function cambiar_contra($id_usuario, $oldpass, $newpass)
  {
    $sql = "SELECT * FROM usuario where id_usuario=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id_usuario));
    $this->objetos = $query->fetchall();
    foreach ($this->objetos as $objeto) {
      $contrasena_actual = $objeto->contrasena_us;
    }
    if (strpos($contrasena_actual, '$2y$10$') === 0) {
      if (password_verify($oldpass, $contrasena_actual)) {
        $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
        $sql = "UPDATE usuario SET contrasena_us=:newpass where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_usuario, ':newpass' => $pass));
        echo 'update';
      } else {
        echo 'noupdate';
      }
    } else {
      if ($oldpass == $contrasena_actual) {
        $pass = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 10]);
        $sql = "UPDATE usuario SET contrasena_us=:newpass where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_usuario, ':newpass' => $pass));
        echo 'update';
      } else {
        echo 'noupdate';
      }
    }
  }
  function cambiar_photo($id_usuario, $nombre)
  {
    $sql = "SELECT foto FROM usuario where id_usuario=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id_usuario));
    $this->objetos = $query->fetchall();

    $sql = "UPDATE usuario SET foto=:nombre where id_usuario=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id_usuario, ':nombre' => $nombre));
    return $this->objetos;
  }
  function buscar()
  {
    if (!empty($_POST['consulta'])) {
      $consulta = $_POST['consulta'];
      $sql = "SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us where nombre_us LIKE :consulta";
      $query = $this->acceso->prepare($sql);
      $query->execute(array(':consulta' => "%$consulta%"));
      $this->objetos = $query->fetchall();
      return $this->objetos;
    } else {

      $sql = "SELECT * FROM usuario join tipo_us on us_tipo=id_tipo_us where nombre_us NOT LIKE '' ORDER BY id_usuario LIMIT 25";
      $query = $this->acceso->prepare($sql);
      $query->execute();
      $this->objetos = $query->fetchall();
      return $this->objetos;
    }
  }
  function crear($nombre, $apellido, $edad, $ci, $pass, $tipo, $foto)
  {
    $sql = "SELECT id_usuario FROM usuario where ci_us=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':ci' => $ci));
    $this->objetos = $query->fetchall();
    if (!empty($this->objetos)) {
      echo 'noadd';
    } else {
      $sql = "INSERT INTO usuario(nombre_us,apellidos_us,edad,ci_us,contrasena_us,us_tipo,foto) VALUES (:nombre,:apellido,:edad,:ci,:pass,:tipo,:foto);";
      $query = $this->acceso->prepare($sql);
      $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':edad' => $edad, ':ci' => $ci, ':pass' => $pass, ':tipo' => $tipo, ':foto' => $foto));
      echo 'add';
    }
  }

  function ascender($pass, $id_ascendido, $id_usuario)
  {
    $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id_usuario' => $id_usuario));
    $this->objetos = $query->fetchall();
    foreach ($this->objetos as $objeto) {
      $contrasena_actual = $objeto->contrasena_us;
    }
    if (strpos($contrasena_actual, '$2y$10$') === 0) {
      if (password_verify($pass, $contrasena_actual)) {
        $tipo = 1;
        $sql = "UPDATE usuario SET us_tipo=:tipo where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_ascendido, ':tipo' => $tipo));
        echo 'ascendido';
      } else {
        echo 'noacendido';
      }
    } else {
      if ($pass == $contrasena_actual) {
        $tipo = 1;
        $sql = "UPDATE usuario SET us_tipo=:tipo where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_ascendido, ':tipo' => $tipo));
        echo 'ascendido';
      } else {
        echo 'noacendido';
      }
    }
  }

  function descender($pass, $id_descendido, $id_usuario)
  {
    $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id_usuario' => $id_usuario));
    $this->objetos = $query->fetchall();
    foreach ($this->objetos as $objeto) {
      $contrasena_actual = $objeto->contrasena_us;
    }
    if (strpos($contrasena_actual, '$2y$10$') === 0) {
      if (password_verify($pass, $contrasena_actual)) {
        $tipo = 2;
        $sql = "UPDATE usuario SET us_tipo=:tipo where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_descendido, ':tipo' => $tipo));
        echo 'descendido';
      } else {
        echo 'nodescendido';
      }
    } else {
      if ($pass == $contrasena_actual) {
        $tipo = 2;
        $sql = "UPDATE usuario SET us_tipo=:tipo where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_descendido, ':tipo' => $tipo));
        echo 'descendido';
      } else {
        echo 'nodescendido';
      }
    }
  }
  function borrar($pass, $id_borrado, $id_usuario)
  {
    $sql = "SELECT * FROM usuario where id_usuario=:id_usuario";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id_usuario' => $id_usuario));
    $this->objetos = $query->fetchall();
    foreach ($this->objetos as $objeto) {
      $contrasena_actual = $objeto->contrasena_us;
    }
    if (strpos($contrasena_actual, '$2y$10$') === 0) {
      if (password_verify($pass, $contrasena_actual)) {
        $sql = "DELETE FROM usuario where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_borrado));
        echo 'borrado';
      } else {
        echo 'noborrado';
      }
    } else {
      if ($pass == $contrasena_actual) {
        $sql = "DELETE FROM usuario where id_usuario=:id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id_borrado));
        echo 'borrado';
      } else {
        echo 'noborrado';
      }
    }
  }
  function devolver_foto($id_usuario)
  {
    $sql = "SELECT foto FROM usuario where id_usuario=:id_usuario";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id_usuario' => $id_usuario));
    $this->objetos = $query->fetchall();
    return $this->objetos;
  }
  function verificar($email, $ci)
  {
    $sql = "SELECT * FROM usuario where correo_us=:email and ci_us=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':email' => $email, ':ci' => $ci));
    $this->objetos = $query->fetchall();
    if (!empty($this->objetos)) {
      if ($query->rowCount() == 1) {
        echo 'encontrado';
      } else {
        echo 'noencontrado';
      }
    } else {
      echo 'noencontrado';
    }
  }
  function remplazar($codigo, $email, $ci)
  {
    $sql = "UPDATE usuario SET contrasena_us=:codigo where correo_us=:email and ci_us=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':codigo' => $codigo, ':email' => $email, ':ci' => $ci));
    //echo 'remplazado';
  }
}
