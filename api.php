<?php

$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);



// API Functions

function getTasks()
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM tasks');
    $stmt->execute();

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);
}


function createTask($title, $desc)
{
    global $pdo;


    try {
        $stmt = $pdo->prepare('INSERT INTO tasks (title, description, completed) VALUES (:title, :description, :completed)');

        $completed = 0; // Default value for completed
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR);
        $stmt->bindParam(':completed', $completed, PDO::PARAM_INT);

        $stmt->execute();

        $lastInsertId = $pdo->lastInsertId();

        $newTask = fetchTask($lastInsertId);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'task' => $newTask]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error creating task: ' . $e->getMessage()]);
    }
}
// Toggle task completed
function toggleTaskCompleted($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare('UPDATE tasks SET completed = 1 WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $task = [
            'id' => $id,
            'completed' => true
        ];

        echo json_encode($task);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error toggling task completion: ' . $e->getMessage()]);
    }
}

// Delete task
function deleteTask($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $response = [
            'message' => 'Task deleted'
        ];

        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error deleting task: ' . $e->getMessage()]);
    }
}

// Get single task
function getTask($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            echo json_encode($task);
        } else {
            echo json_encode([
                'error' => 'Task not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error fetching task: ' . $e->getMessage()]);
    }
}

// Get single task
function fetchTask($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            return $task;
        } else {
            return ['error' => 'Task not found'];
        }
    } catch (PDOException $e) {
        return ['error' => 'Error fetching task: ' . $e->getMessage()];
    }
}


// Update task
function updateTask($id, $data)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare('UPDATE tasks SET title = :title, description = :description, completed= :completed WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':completed', $data->completed, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $data->description, PDO::PARAM_STR);
        $stmt->execute();

        $task = [
            'id' => $id,
            'title' => $data->title,
            'description' => $data->description,
            'completed' => $data->completed
        ];

        echo json_encode(['success' => true, 'task' => $task], JSON_PRETTY_PRINT);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error updating task: ' . $e->getMessage()]);
    }
}

?>