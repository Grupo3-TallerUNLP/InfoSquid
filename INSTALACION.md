InfoSquid - Instalación
==================================

Este documento contiene información sobre cómo descargar e instalar la aplicación InfoSquid.

1) Descargar el código fuente
----------------------------------

Para descargar el código, basta con clonar el repositorio de GitHub:

    git clone https://github.com/Grupo3-TallerUNLP/InfoSquid.git

2) Instalar Software de terceros
----------------------------------

InfoSquid utiliza [wkhtmltopdf][1] para exportar los informes a formato PDF.

Instalar el resto de los vendors utilizando composer:

    composer install --no-dev --optimize-autoloader

De no tener composer, instalarlo con el comando:

    curl -s http://getcomposer.org/installer | php


3) Comprobar la configuración del sistema
----------------------------------

Para comprobar que el sistema cumple con los requerimientos y recomendaciones básicas, ejecutar el comando:

    php app/check.php

4) Crear Base de Datos
----------------------------------

Para comenzar a usar el sistema, se deben crear la base de datos, el es esquema de datos y cargar los datos iniciales de la aplicación.

Para crear la base de datos, ejecutar:

    php app/console doctrine:database:create

Para crear el esquema de datos:

    php app/console doctrine:schema:create

Para cargar los datos iniciales:

    php app/console doctrine:fixtures:load

Además de los datos necesarios para que funcione la aplicación correctamente, se creó un usuario administrador con nombre de usuario `admin` y contraseña `admin`

5) Instalar los assets
----------------------------------

Para instalar los assets (imágenes, hojas de estilos, javascripts) que utiliza InfoSquid:

Limpiar la cache en entorno de producción con el comando:

    php app/console cache:clear --env=prod --no-debug

Instalar los assets:

    php app/console assetic:dump --env=prod --no-debug


####[Leer sobre la puesta en producción][2]

[1]:  http://wkhtmltopdf.org/
[2]:  http://github.com/Grupo3-TallerUNLP/InfoSquid/blob/master/PUESTA-EN-PRODUCCION.md
