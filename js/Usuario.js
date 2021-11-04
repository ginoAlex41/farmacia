$(document).ready(function(){
    var funcion='';
    var id_usuario = $('#id_usuario').val();
    var edit=false;
    buscar_usuario(id_usuario);
    function buscar_usuario(dato) {
        funcion='buscar_usuario';
        $.post('../controlador/UsuarioController.php',{dato,funcion},(response)=>{
            let nombre='';
            let apellidos='';
            let edad='';
            let ci='';
            let tipo='';
            let telefono='';
            let residencia='';
            let correo='';
            let sexo='';
            let obs='';
            const usuario = JSON.parse(response);
            nombre+=`${usuario.nombre}`;
            apellidos+=`${usuario.apellidos}`;
            edad+=`${usuario.edad}`;
            ci+=`${usuario.ci}`;
            if(usuario.tipo=='Root'){
                tipo+=`<h1 class="badge badge-danger">${usuario.tipo}</h1>`;
              }
              if(usuario.tipo=='Administrador'){
                tipo+=`<h1 class="badge badge-warning">${usuario.tipo}</h1>`;
              }
              if(usuario.tipo=='Tecnico'){
                tipo+=`<h1 class="badge badge-info">${usuario.tipo}</h1>`;
              }
            
            telefono+=`${usuario.telefono}`;
            residencia+=`${usuario.residencia}`;
            correo+=`${usuario.correo}`;
            sexo+=`${usuario.sexo}`;
            obs+=`${usuario.obs}`;
            $('#nombre_us').html(nombre);
            $('#apellidos_us').html(apellidos);
            $('#edad').html(edad);
            $('#ci_us').html(ci);
            $('#us_tipo').html(tipo);
            $('#telefono_us').html(telefono);
            $('#residencia_us').html(residencia);
            $('#correo_us').html(correo);
            $('#sexo_us').html(sexo);
            $('#obs_us').html(obs);
            
            $('#foto2').attr('src',usuario.foto);
            $('#foto1').attr('src',usuario.foto);
            $('#foto3').attr('src',usuario.foto);
            $('#foto4').attr('src',usuario.foto);

        })
    }
    $(document).on('click','.edit',(e)=>{
        funcion='capturar_datos';
        edit=true;
        $.post('../controlador/UsuarioController.php',{funcion,id_usuario},(response)=>{
            const usuario = JSON.parse(response);
            $('#telefono').val(usuario.telefono);
            $('#residencia').val(usuario.residencia);
            $('#correo').val(usuario.correo);
            $('#sexo').val(usuario.sexo);
            $('#obs').val(usuario.obs);
        })
    });
    $('#form-usuario').submit(e=>{
        if(edit==true){
            let telefono=$('#telefono').val();
            let residencia=$('#residencia').val();
            let correo=$('#correo').val();
            let sexo=$('#sexo').val();
            let obs=$('#obs').val();
            funcion='editar_usuario';
            $.post('../controlador/UsuarioController.php',{id_usuario,funcion,telefono,residencia,correo,sexo,obs},(response)=>{
                console.log(response)
                if(response=='editado'){
                    $('#editado').hide('slow');
                    $('#editado').show(1000);
                    $('#editado').hide(2000);
                    $('#form-usuario').trigger('reset');
                }
                edit=false;
                buscar_usuario(id_usuario);
            })
        }
        else{
            $('#noeditado').hide('slow');
            $('#noeditado').show(1000);
            $('#noeditado').hide(2000);
            $('#form-usuario').trigger('reset');
        }
        e.preventDefault();
    });

    $('#form-pass').submit(e=>{
        let oldpass=$('#oldpass').val();
        let newpass=$('#newpass').val();
        funcion='cambiar_contra';
        $.post('../controlador/UsuarioController.php',{id_usuario,funcion,oldpass,newpass},(response)=>{
            if(response=='update'){
                $('#update').hide('slow');
                    $('#update').show(1000);
                    $('#update').hide(2000);
                    $('#form-pass').trigger('reset');
            }
            else{
                $('#noupdate').hide('slow');
                $('#noupdate').show(1000);
                $('#noupdate').hide(2000);
                $('#form-pass').trigger('reset');
            }
        })
        e.preventDefault();
    })
    $('#form-photo').submit(e=>{
        let formData = new FormData($('#form-photo')[0]);
        $.ajax({
            url:'../controlador/UsuarioController.php',
            type:'POST',
            data:formData,
            cache:false,
            processData: false,
            contentType:false
        }).done(function(response){
            const json = JSON.parse(response);
            if(json.alert=='edit'){
                $('#foto1').attr('src',json.ruta);
                $('#edit').hide('slow');
                $('#edit').show(1000);
                $('#edit').hide(2000);
                $('#form-photo').trigger('reset');
                buscar_usuario(id_usuario);
            }
            else{
                $('#noedit').hide('slow');
                $('#noedit').show(1000);
                $('#noedit').hide(2000);
                $('#form-photo').trigger('reset');
            }
            
            
        });
        e.preventDefault();
    })

})