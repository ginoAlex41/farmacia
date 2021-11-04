$(document).ready(function(){
    var funcion='';
    var id_usuario = $('#id_usuario').val();
        buscar_usuario(id_usuario);

function buscar_usuario(dato) {
    funcion='buscar_usuario';
    $.post('../controlador/UsuarioController.php',{dato,funcion},(response)=>{
        console.log(response);
        let nombre='';
        let apellidos='';
        let edad='';
        let ci='';
        let tipo='';
        let telefono='';
        let domicilio='';
        let email='';
        let sexo='';
        let obs='';
        const usuario = JSON.parse(response);
        nombre+=`${usuario.nombre}`;
        apellidos+=`${usuario.apellidos}`;
        edad+=`${usuario.edad}`;
        ci+=`${usuario.ci}`;
        tipo+=`${usuario.tipo}`;
        telefono+=`${usuario.telefono}`;
        domicilio+=`${usuario.domicilio}`;
        email+=`${usuario.email}`;
        sexo+=`${usuario.sexo}`;
        obs+=`${usuario.obs}`;
        $('#nombre_us').html(nombre);

    })
    
}
     })