<?php

// Clase que implementa el patrón de instancia única (Singleton pattern)
// para manejar la conexión a la base de datos
class DbConnection
{
    // Instancia única de la clase
    private static $instance = null;

    // Constructor privado, para evitar que se cree una nueva instancia
    // de la clase usando el operador 'new'
    private function __construct()
    {
        // Incluye los detalles de la conexión a la base de datos
        include("config/db.php");

        // Si hay un error de conexión, finaliza la ejecución del script
        // y muestra un mensaje de error
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
    }

    // Método estático que devuelve la única instancia de la clase
    public static function getInstance()
    {
        // Si la instancia aún no ha sido creada, crea una nueva
        if (self::$instance == null) {
            self::$instance = new DbConnection();
        }
        // Devuelve la instancia
        return self::$instance;
    }

    // Método que devuelve la conexión a la base de datos
    public function getConnection()
    {
        // Incluye los detalles de la conexión a la base de datos
        include("config/db.php");

        // Devuelve la conexión
        return $conn;
    }

    // Método que cierra la conexión y establece la instancia a null
    public function close()
    {
        // Incluye los detalles de la conexión a la base de datos
        include("config/db.php");

        // Cierra la conexión
        $conn->close();

        // Establece la instancia a null
        self::$instance = null;
    }
}

// Clase que representa una entrada de blog
class BlogEntry
{
    // Propiedades de la entrada de blog
    public $title;
    public $content;
    public $image;
    public $date;
    public $pageName;

    // Constructor que inicializa las propiedades de la entrada de blog
    public function __construct($title, $content, $image, $date, $pageName)
    {
        $this->title = $title;
        $this->content = $content;
        $this->image = $image;
        $this->date = $date;
        $this->pageName = $pageName;
    }
}

// Clase que se encarga de crear objetos BlogEntry a partir de filas de resultados
// de una consulta
class BlogEntryFactory
{
    // Método estático que toma una fila de resultados de una consulta a la base de datos
    // y devuelve una nueva instancia de la clase BlogEntry inicializada con los valores
    // de esa fila
    public static function createFromRow($row)
    {
        return new BlogEntry(
            $row['titulo'],
            $row['contenido'],
            $row['imagen'],
            $row['fecha'],
            $row['nombre_pagina']
        );
    }
}

// Clase que se encarga de controlar el flujo de trabajo para recuperar entradas de blog
// de la base de datos, procesarlas y guardarlas en archivos HTML
class BlogEntryController
{
    // Método estático que recupera todas las entradas de blog de la base de datos
    // y las devuelve como una matriz de objetos BlogEntry
    public static function getBlogEntriesFromDb()
    {
        // Obtiene la instancia única de la clase DbConnection
        $db = DbConnection::getInstance();

        // Obtiene la conexión a la base de datos
        $conn = $db->getConnection();

        // Prepara una consulta para seleccionar todas las entradas de blog
        $stmt = $conn->prepare("SELECT * FROM publications");

        // Ejecuta la consulta
        $stmt->execute();

        // Obtiene el resultado de la consulta
        $result = $stmt->get_result();

        // Matriz que almacenará las entradas de blog
        $entries = [];

        // Recorre cada fila de resultados y crea un objeto BlogEntry para cada una
        while ($row = $result->fetch_assoc()) {
            $entries[] = BlogEntryFactory::createFromRow($row);
        }

        // Cierra la conexión a la base de datos
        $db->close();

        // Devuelve las entradas de blog
        return $entries;
    }
    // Método estático que toma una matriz de objetos BlogEntry y los procesa
    // en una plantilla HTML para cada entrada de blog. Devuelve el HTML resultante
    // como una cadena
    public static function renderBlogEntries($entries)
    {
        // Inicia el almacenamiento en búfer de salida (output buffer)
        ob_start();
        // Procesa cada entrada de blog
        foreach ($entries as $entry) {
        // Asigna las propiedades de la entrada de blog a variables locales
            $title_post = $entry->title;
            $date_post = $entry->date;
            $content_post = $entry->content;
            $img_post = $entry->image;

            // Incluye la plantilla para cada entrada de blog
            include "content/planilla.php";
            }
        // Devuelve el contenido del búfer de salida como una cadena y lo elimina
        return ob_get_clean();
     }

    // Método estático que toma una matriz de objetos BlogEntry y guarda cada uno de ellos
    // en un archivo HTML individual utilizando el nombre de archivo especificado en la propiedad
    // pageName de cada objeto BlogEntry
    public static function saveBlogEntriesToFiles($entries)
    {
        // Recorre cada entrada de blog
        foreach ($entries as $entry) {
            // Procesa la entrada de blog en HTML
            $html = self::renderBlogEntries([$entry]);

            // Guarda el HTML en un archivo con el nombre especificado en la propiedad pageName
            file_put_contents("content/{$entry->pageName}.html", $html);
        }
    }
}

// Recupera todas las entradas de blog de la base de datos
$entries = BlogEntryController::getBlogEntriesFromDb();

// Guarda todas las entradas de blog en archivos HTML
BlogEntryController::saveBlogEntriesToFiles($entries);