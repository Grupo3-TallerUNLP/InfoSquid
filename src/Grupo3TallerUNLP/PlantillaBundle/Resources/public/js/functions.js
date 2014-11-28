function mostrarOficina()
{
    document.getElementById('div_oficina').style.display = 'block';
    document.getElementById('div_usuario').style.display = 'none';
    document.getElementById('div_rango').style.display = 'none';
    document.getElementById('div_ip').style.display = 'none';
}

function mostrarusuarioespecifico()
{
    document.getElementById('div_oficina').style.display = 'none';
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

function ocultarfiltro1()
{
    document.getElementById('div_oficina').style.display = 'none';
    document.getElementById('div_usuario').style.display = 'none';
    document.getElementById('div_ip').style.display = 'none';
    document.getElementById('div_rango').style.display = 'none';
}

function mostrargrupo()
{
    document.getElementById('div_grupo').style.display = 'block';
    document.getElementById('div_sitio').style.display = 'none';
}


function mostrarsitio()
{
    document.getElementById('div_grupo').style.display = 'none';
    document.getElementById('div_sitio').style.display = 'block';
}

function ocultarfiltro2()
{
    document.getElementById('div_grupo').style.display = 'none';
    document.getElementById('div_sitio').style.display = 'none';
}

function mostrarPlantilla()
{
    document.getElementById('div_plantillas').style.display = 'block';
    document.getElementById('div_filtros').style.display = 'none';
}

function mostrarFiltros()
{
    document.getElementById('div_plantillas').style.display = 'none';
    document.getElementById('div_filtros').style.display = 'block';
}