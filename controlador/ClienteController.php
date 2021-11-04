<?php
include '../modelo/Cliente.php';
$cliente = new Cliente();
if ($_POST['funcion'] == 'buscar') {
  $cliente->buscar();
  date_default_timezone_set('America/Lima');
  $fecha = date('Y-m-d H:i:s');
  $fecha_actual = new DateTime($fecha);
  $json = array();
  foreach ($cliente->objetos as $objeto) {
    $nac = new DateTime($objeto->edad);
    $edad = $nac->diff($fecha_actual);
    $edad_y = $edad->y;
    $json[] = array(
      'id' => $objeto->id,
      'nombre' => $objeto->nombre . ' ' . $objeto->apellidos,
      'ci' => $objeto->ci,
      'edad' => $edad_y,
      'telefono' => $objeto->telefono,
      'correo' => $objeto->correo,
      'sexo' => $objeto->sexo,
      'obs' => $objeto->obs,
      'foto' => '../img/avatar.png'
    );
  }
  $jsonstring = json_encode($json);
  echo $jsonstring;
}
if ($_POST['funcion'] == 'crear') {
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $ci = $_POST['ci'];
  $edad = $_POST['edad'];
  $telefono = $_POST['telefono'];
  $correo = $_POST['correo'];
  $sexo = $_POST['sexo'];
  $obs = $_POST['obs'];
  $foto = 'avatar.png';

  $cliente->crear($nombre, $apellido, $ci, $edad, $telefono, $correo, $sexo, $obs, $foto);
}
if ($_POST['funcion'] == 'editar') {

  $id = $_POST['id'];
  $telefono = $_POST['telefono'];
  $correo = $_POST['correo'];
  $obs = $_POST['obs'];

  $cliente->editar($id, $telefono, $correo, $obs);
}
if ($_POST['funcion'] == 'borrar') {
  $id = $_POST['id'];
  $cliente->borrar($id);
}
if ($_POST['funcion'] == 'rellenar_clientes') {
  $cliente->rellenar_clientes();
  $json = array();
  foreach ($cliente->objetos as $objeto) {
    $json[] = array(
      'id' => $objeto->id,
      'nombre' => $objeto->nombre . ' ' . $objeto->apellidos . ' | ' . $objeto->ci
    );
  }
  $jsonstring = json_encode($json);
  echo $jsonstring;
}
