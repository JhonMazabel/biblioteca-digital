
# Biblioteca Digital

Este proyecto es una aplicación de biblioteca digital desarrollada en PHP (backend) y Angular (frontend). El backend se aloja en un servidor local utilizando XAMPP, y el frontend se sirve en un servidor de desarrollo de Angular.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes programas y herramientas:

- [XAMPP](https://www.apachefriends.org/index.html): para ejecutar el servidor local y la base de datos MySQL.
- [Node.js](https://nodejs.org/): para ejecutar el servidor de desarrollo de Angular y manejar las dependencias de Node.
- [Angular CLI](https://angular.io/cli): para compilar y ejecutar el frontend de Angular.

## Instalación

### 1. Configuración del Backend (PHP con XAMPP)

1. **Descarga e instala XAMPP** desde su sitio oficial.
   
2. **Clona el repositorio del proyecto** a tu máquina local:

   ```bash
   git clone https://github.com/JhonMazabel/biblioteca-digital.git
   ```

3. **Mueve la carpeta `backend`** al directorio `htdocs` de XAMPP:

   - Copia la carpeta `backend` a la ruta donde instalaste XAMPP, generalmente es:
     ```
     C:\xampp\htdocs\
     ```

4. **Configura la base de datos:**

   - Inicia el panel de control de XAMPP y haz clic en "Start" para los servicios de **Apache** y **MySQL**.
   - Abre **phpMyAdmin** desde tu navegador visitando `http://localhost/phpmyadmin/`.
   - Crea una nueva base de datos (por ejemplo, `biblioteca_digital`).
   - Importa el archivo SQL (`bibliotecadigital BASE DE DATOS.sql`) en esta base de datos.

### 2. Configuración del Frontend (Angular)

1. **Navega a la carpeta `frontend` del proyecto**:

   ```bash
   cd /ruta/al/repo/biblioteca-digital/frontend
   ```

2. **Instala las dependencias de Node.js**:

   ```bash
   npm install
   ```

   Asegúrate de que estas dependencias están incluidas:

   - `@angular/core` y demás módulos de Angular.
   - `rxjs`
   - `zone.js`

3. **Compila y sirve el frontend de Angular**:

   ```bash
   ng serve
   ```

   Este comando iniciará el servidor de desarrollo de Angular. Una vez que esté compilado, puedes acceder a la aplicación en tu navegador:

   ```
   http://localhost:4200/
   ```

## Ejecución del Proyecto

1. **Inicia el servidor Apache y MySQL** en el panel de control de XAMPP.

2. **Ejecuta el frontend de Angular** utilizando el comando `ng serve`.

3. **Abre tu navegador** y visita `http://localhost:4200/` para interactuar con la aplicación de biblioteca digital.

## Notas Adicionales

- Asegúrate de tener permisos adecuados para ejecutar XAMPP y Node.js en tu sistema operativo.
- Si encuentras problemas con puertos ocupados, puedes cambiar el puerto del servidor Angular utilizando `ng serve --port 4201`.

¡Gracias por utilizar Biblioteca Digital!
```

### Instrucciones para usar el formato

1. **Copia** el texto proporcionado.
2. **Pega** el texto en tu editor de Markdown o editor de texto favorito (por ejemplo, Visual Studio Code, Sublime Text, o cualquier redactor de Markdown en línea).
3. **Guarda** el archivo con el nombre `README.md`.
4. **Sube** el archivo a tu repositorio de GitHub, siguiendo los pasos que mencionamos anteriormente para agregar, confirmar y hacer push de archivos.

¡Con esto tendrás tu archivo `README.md` listo para ser subido a tu repositorio!
