InfoSquid - Puesta en Producción
==================================

Este documento contiene información sobre cómo poner en producción la aplicación InfoSquid.

1) Configurar el sistema
----------------------------------

Como parte del proceso de instalación, InfoSquid crea una Oficina *Informática*, un usuario de red (las personas que se conectan a la red) *Usuario Administrador* y un usuario de sistema (las personas que pueden acceder al sistema) con perfil administrador con nombre de usuario *admin* y contraseña *admin*

Accediendo al sistema, arriba a la derecha hay un link para acceder a la configuración, que consiste en cuatro campos:

  * **Paginación**: cantidad de items que se muestran por página en los listados
  * **Directorio de Logs Antiguos**: ubicación de archivos antiguos de logs
  * **Directorio de Logs Antiguos**: ubicación del archivos de logs de Squid
  * **Tiempo de mantenimiento**: antigüedad máxima en días para las requests

2) Configurar las tareas programadas
----------------------------------

InfoSquid contiene tres tareas para ejecutar periódicamente:

  * Dar de alta requests del log de Squid
  * Mover requests antiguos a otra tabla
  * Enviar los informes predefinidos que correspondan

###a) Dar de alta requests del log de Squid

Para realizar informes, InfoSquid debe primero procesar las requests del log de acceso de Squid.
InfoSquid lee el archivo de logs desde la última linea hacia arriba, por lo que si se interrumpe la ejecución de esta tarea, se deben eliminar las requests (dadas de alta en la ejecución) de la base de datos de forma manual (el campo fechaAlta contiene la fecha y hora de alta en la base de datos).
Cuando termina de procesar el archivo de logs, InfoSquid busca el archivo más reciente dentro del directorio definido en la configuración y lo procesa de la misma forma que el archivo de logs.

Nota: las URLs se truncarán a 255 caracteres.

El comando a ejecutar es

    php app/console infosquid:altalog --no-debug
	
Es importante el parámetro `--no-debug` para evitar errores de consumo de memoria.

###b) Mover requests antiguos a otra tabla

Para lograr mejor rendimiento, InfoSquid mueve los requests antiguos a otra tabla de la base de datos con el comando

    php app/console infosquid:mantenimiento

###c) Enviar los Informes Predefinidos

InfoSquid permite generar plantillas, que consisten en un grupo de filtros reutilizables. Para cada plantilla puede definirse un *informe predefinido* para que se envíe con determinada frecuencia el informe obtenido en formato PDF al email del propietario de la plantilla y el informe predefinido.
Para que InfoSquid realice esta tarea, ejecutar el comando

    php app/console infosquid:informepredefinido:enviar


####[Leer sobre la instalación][1]

[1]:  http://github.com/Grupo3-TallerUNLP/InfoSquid/blob/master/INSTALACION.md
