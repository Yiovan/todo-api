<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        try {
            $stmt = $pdo->query("SELECT * FROM tareas");
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tareas);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'POST':
        try {
            $stmt = $pdo->prepare("INSERT INTO tareas (titulo, descripcion) VALUES (:titulo, :descripcion)");
            $stmt->execute([
                ':titulo' => $input['titulo'],
                ':descripcion' => $input['descripcion']
            ]);
            echo json_encode(['message' => 'Tarea creada']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

        case 'DELETE':
            // Obtener el ID de la URL
            $id = isset($path[0]) ? $path[0] : null;
        
            if ($id) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = :id");
                    $stmt->execute([':id' => $id]);
        
                    echo json_encode(['message' => 'Tarea eliminada']);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(['error' => $e->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de tarea requerido']);
            }
            break;
        

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}