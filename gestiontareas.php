<?php

require_once('sistema.php');
require_once('config.php');

// Función para crear una tarea
function crearTarea($descripcion, $estado) {
    global $conexion;
    
    $stmt = $conexion->prepare("INSERT INTO tarea (descripcion, estado) VALUES (:descripcion, :estado)");
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':estado', $estado);
    
    return $stmt->execute();
}

// Función para obtener todas las tareas
function obtenerTodasLasTareas() {
    global $conexion;
    
    $stmt = $conexion->query("SELECT * FROM tarea");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener una tarea por su ID
function obtenerTareaPorId($id) {
    global $conexion;
    
    $stmt = $conexion->prepare("SELECT * FROM tarea WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para actualizar una tarea por su ID
function actualizarTarea($id, $estado) {
    global $conexion;
    
    $stmt = $conexion->prepare("UPDATE tarea SET estado = :estado WHERE id = :id");
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}

// Función para eliminar una tarea por su ID
function eliminarTarea($id) {
    global $conexion;
    
    $stmt = $conexion->prepare("DELETE FROM tarea WHERE id = :id");
    $stmt->bindParam(':id', $id);
    
    return $stmt->execute();
}

// Ejemplo de uso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de creación de tarea
    if (isset($_POST['descripcion'], $_POST['estado'])) {
        $descripcion = $_POST['descripcion'];
        $estado = $_POST['estado'];
        
        if (crearTarea($descripcion, $estado)) {
            echo 'Tarea creada con éxito.';
        } else {
            echo 'Error al crear la tarea.';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mostrar lista de tareas
    $tareas = obtenerTodasLasTareas();
    foreach ($tareas as $tarea) {
        echo "ID: {$tarea['id']}, Descripción: {$tarea['descripcion']}, Estado: {$tarea['estado']}<br>";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Tareas</title>
</head>
<body>
    <h1>Gestión de Tareas</h1>
    
    <h2>Crear Nueva Tarea</h2>
    <form method="POST">
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required>
        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="pendiente">Pendiente</option>
            <option value="en progreso">En Progreso</option>
            <option value="completada">Completada</option>
        </select>
        <button type="submit">Crear Tarea</button>
    </form>
</body>
</html>
