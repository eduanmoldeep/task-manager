<?php

include 'db.php';
include 'api.php';


// Router
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    if (isset($_GET['id'])) {
      handleGetTaskRequest($_GET['id']);
    } else {
      handleGetTasksRequest();
    }
    break;
  case 'POST':
    handlePostRequest();
    break;
  case 'PUT':
    $putData = json_decode(file_get_contents('php://input'));
   
    if (!empty($putData->id)) {
      handlePutTaskRequest($putData->id);
    } else {
      handleInvalidMethod();
    }
    break;
  case 'DELETE':
    $putData = json_decode(file_get_contents('php://input'));
   
    if (!empty($putData->id)) {
      handleDeleteTaskRequest($putData->id);
    } else {
      handleInvalidMethod();
    }
    break;
  default:
    handleInvalidMethod();
}

// GET request handler for all tasks
function handleGetTasksRequest()
{
  getTasks();
}

// GET request handler for a specific task by ID
function handleGetTaskRequest($taskId)
{
  getTask($taskId);
}

// POST request handler
function handlePostRequest()
{
  try {
    $inputData = file_get_contents('php://input');

    $data = json_decode($inputData);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('Invalid JSON data');
    }

    $title = $data->title;
    $description = $data->description;

    createTask($title, $description);
  } catch (Exception $e) {
    // Handle exceptions here
    echo json_encode(['error' => $e->getMessage()]);
  }
}

// PUT request handler for a specific task by ID
function handlePutTaskRequest($taskId)
{
  try {
    $inputData = file_get_contents('php://input');

    $data = json_decode($inputData);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('Invalid JSON data');
    }

    updateTask($taskId, $data);
  } catch (Exception $e) {
    // Handle exceptions here
    echo json_encode(['error' => $e->getMessage()]);
  }
}
// DELETE request handler for a specific task by ID
function handleDeleteTaskRequest($taskId)
{
  try {
    deleteTask($taskId);
  } catch (Exception $e) {
    throw $e;
  }
}
// Handler for unsupported HTTP methods
function handleInvalidMethod()
{
  echo json_encode(['error' => 'Method Not Allowed']);
}

?>