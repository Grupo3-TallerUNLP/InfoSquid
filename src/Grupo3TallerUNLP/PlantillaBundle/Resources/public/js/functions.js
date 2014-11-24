function mostrarOficina()
{
    document.getElementById('div_oficina').style.display = 'block';
    document.getElementById('div_usuario').style.display = 'none';
    document.getElementById('div_rango').style.display = 'none';
    document.getElementById('div_ip').style.display = 'none';
}

function mostrarusuarioespecifico()
{
    document.getElementById('div_oficina').style.display = 'block';
    document.getElementById('div_usuario').style.display = 'block';
    document.getElementById('div_rango').style.display = 'none';
    document.getElementById('div_ip').style.display = 'none';
}


function mostrarip()
{
    document.getElementById('div_ip').style.display = 'block';
    document.getElementById('div_usuario').style.display = 'none';
    document.getElementById('div_oficina').style.display = 'none';
    document.getElementById('div_rango').style.display = 'none';
}


function mostrarrangoip()
{
    document.getElementById('div_rango').style.display = 'block';
    document.getElementById('div_usuario').style.display = 'none';
    document.getElementById('div_ip').style.display = 'none';
    document.getElementById('div_oficina').style.display = 'none';
}

function mostrargrupo()
{
    document.getElementById('div_grupo').style.display = 'block';
    document.getElementById('div_sitio').style.display = 'none';
}


function mostrarsitio()
{
    document.getElementById('div_grupo').style.display = 'block';
    document.getElementById('div_sitio').style.display = 'block';
}
