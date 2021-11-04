<?php
include_once 'Conexion.php';
class Cliente
{
  var $objetos;
  public function __construct()
  {
    $db = new Conexion();
    $this->acceso = $db->pdo;
  }
  function buscar()
  {
    if (!empty($_POST['consulta'])) {
      $consulta = $_POST['consulta'];
      $sql = "SELECT * FROM cliente where estado='A' and nombre LIKE :consulta";
      $query = $this->acceso->prepare($sql);
      $query->execute(array(':consulta' => "%$consulta%"));
      $this->objetos = $query->fetchall();
      return $this->objetos;
    } else {
      $sql = "SELECT * FROM cliente where estado='A' and  nombre NOT LIKE '' ORDER BY id desc LIMIT 25";
      $query = $this->acceso->prepare($sql);
      $query->execute();
      $this->objetos = $query->fetchall();
      return $this->objetos;
    }
  }
  function crear($nombre, $apellido, $ci, $edad, $telefono, $correo, $sexo, $obs, $foto)
  {
    $sql = "SELECT id,estado FROM cliente where nombre=:nombre and apellidos=:apellido and ci=:ci";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':ci' => $ci));
    $this->objetos = $query->fetchall();
    if (!empty($this->objetos)) {
      foreach ($this->objetos as $cli) {
        $cli_id = $cli->id;
        $cli_estado = $cli->estado;
      }
      if ($cli_estado == 'A') {
        echo 'noadd';
      } else {
        $sql = "UPDATE cliente SET estado='A' where id=:id ";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $cli_id));
        echo 'add';
      }
    } else {
      $sql = "INSERT INTO cliente(nombre,apellidos,ci,edad,telefono,correo,sexo,obs,foto) values (:nombre,:apellido,:ci,:edad,:telefono,:correo,:sexo,:obs,:foto);";
      $query = $this->acceso->prepare($sql);
      $query->execute(array(':nombre' => $nombre, ':apellido' => $apellido, ':ci' => $ci, ':edad' => $edad, ':telefono' => $telefono, ':correo' => $correo, ':sexo' => $sexo, ':obs' => $obs, ':foto' => $foto));
      echo 'add';
    }
  }
  function editar($id, $telefono, $correo, $obs)
  {
    $sql = "SELECT id FROM cliente where id=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id));
    $this->objetos = $query->fetchall();
    if (empty($this->objetos)) {
      echo 'noedit';
    } else {
      $sql = "UPDATE cliente SET telefono=:telefono, correo=:correo, obs=:obs where id=:id";
      $query = $this->acceso->prepare($sql);
      $query->execute(array(':id' => $id, ':telefono' => $telefono, ':correo' => $correo, ':obs' => $obs));
      echo 'edit';
    }
  }
  function borrar($id)
  {


    $sql = "UPDATE cliente SET estado='I' where id=:id";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id' => $id));
    if (!empty($query->execute(array(':id' => $id)))) {
      echo 'borrado';
    } else {
      echo 'noborrado';
    }
  }
  function rellenar_clientes()
  {


    $sql = "SELECT * FROM cliente where estado='A' order by nombre asc";
    $query = $this->acceso->prepare($sql);
    $query->execute();
    $this->objetos = $query->fetchall();
    return $this->objetos;
  }
  function buscar_datos_cliente($id_cliente)
  {


    $sql = "SELECT * FROM cliente where id=:id_cliente";
    $query = $this->acceso->prepare($sql);
    $query->execute(array(':id_cliente' => $id_cliente));
    $this->objetos = $query->fetchall();
    return $this->objetos;
  }
}
