<?php
include_once 'Venta.php';
include_once 'VentaProducto.php';
include_once 'Cliente.php';

function getHtml($id_venta)
{
  $venta = new Venta();
  $venta_producto = new VentaProducto();
  $cliente = new Cliente();
  $venta->buscar_id($id_venta);
  $venta_producto->ver($id_venta);
  $plantilla = '
    <body>
    <header class="clearfix">
      <div id="logo">
        
      </div>
      <h1>COMPROBANTE DE VENTA</h1>
      <div id="company" class="clearfix">
      <img src="../img/logo2.png" width="60" height="60">
        <div id="negocio">Farmacia Gino</div>
        <div><a href="mailto:company@example.com">montana.tony.cfk1@gmail.com</a></div>
      </div>';
  foreach ($venta->objetos as $objeto) {
    if (empty($objeto->id_cliente)) {
      $cliente_nombre = $objeto->cliente;
      $cliente_ci = $objeto->ci;
    } else {
      $cliente->buscar_datos_cliente($objeto->id_cliente);
      foreach ($cliente->objetos as $cli) {
        $cliente_nombre = $cli->nombre . ' ' . $cli->apellidos;
        $cliente_ci = $cli->ci;
      }
    }
    $plantilla .= '
    
      <div id="project">
        <div><span>Codigo de Venta: </span>' . $objeto->id_venta . '</div>
        <div><span>Cliente: </span>' . $cliente_nombre . '</div>
        <div><span>ci: </span>' . $cliente_ci . '</div>
        <div><span>Fecha y Hora: </span>' . $objeto->fecha . '</div>
        <div><span>Vendedor: </span>' . $objeto->vendedor . '</div>
      </div>';
  }
  $plantilla .= '
    </header>
    <main>
      <table>
        <thead>
          <tr>
           
            <th class="service">Producto</th>
            <th class="service">Concentracion</th>
            <th class="service">obs</th>
            <th class="service">Laboratorio</th>
            <th class="service">Presentacion</th>
            <th class="service">Tipo</th>
            <th class="service">Cantidad</th>
            <th class="service">Precio</th>
            <th class="service">Subtotal</th>
          </tr>
        </thead>
        <tbody>';
  foreach ($venta_producto->objetos as $objeto) {

    $plantilla .= '<tr>
            
            <td class="servic">' . $objeto->producto . '</td>
            <td class="servic">' . $objeto->concentracion . '</td>
            <td class="servic">' . $objeto->obs . '</td>
            <td class="servic">' . $objeto->laboratorio . '</td>
            <td class="servic">' . $objeto->presentacion . '</td>
            <td class="servic">' . $objeto->tipo . '</td>
            <td class="servic">' . $objeto->cantidad . '</td>
            <td class="servic">' . $objeto->precio . '</td>
            <td class="servic">' . $objeto->subtotal . '</td>
          </tr>';
  }
  $calculos = new Venta();
  $calculos->buscar_id($id_venta);
  foreach ($calculos->objetos as $objeto) {
    $ga = $objeto->total * 0.15;
    $sub = $objeto->total + $ga;
    require_once "CifrasEnLetras.php";
    $v = new CifrasEnLetras();
    $letra = ($v->convertirBsEnLetras($sub));
    $plantilla .= '
          <tr>
            <td colspan="8" class="grand total">TOTAL</td>
            <td class="grand total">Bs.' . $sub . '</td>
            
          </tr>
          <tr>
            
          </tr>
          
          
          <tr >
          <td class="grand total">SON: </td>
          <td class="grand total" style="display: inline-block;">' . $letra . '</td>
          </tr>';
  }
  $plantilla .= '
        </tbody>
      </table>
      <div id="notices">
        <div>ATBERTENCIA:</div>
        <div class="notice">*Presentar este comprobante de pago para cualquier reclamo o devolucion.</div>
        <div class="notice">*El reclamo procedera dentro de las 24 horas de haber hecho la compra.</div>
        <div class="notice">*Si el producto esta dañado o abierto, la devolucion no procedera.</div>
        <div class="notice">*Revise su cambio antes de salir del establecimiento.</div>
      </div>
    </main>
     <div class="notice">*Vendedor.</div>
    <footer>
   
      
      Created by Warpiece (Gino Alex Rojas Sanjines) Estudiante de Sistemas Informaticos.
    </footer>
  </body>';
  return $plantilla;
}
