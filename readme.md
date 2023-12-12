# Task Manager API and App

## Create Table
```
// Create tasks table in database
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    completed TINYINT(1) DEFAULT 0
);
```

## API Documentation

[Postman Collection](https://api.postman.com/collections/12486414-f0cd6a6e-0c10-407d-b361-a736ba276790?access_key=PMAT-01HHFHCWB16208S9TRYC9NB07W) 


### CREATE

URL - http://localhost/task-manager/
Method - POST
sample JSON request - 
{   
    "title":"First Post",
    "description":"Feeling Excited"
}


### UPDATE

URL - http://localhost/task-manager/
Method - PUT
sample JSON request - 
{
    "id": 1, 
    "title":"Hello",
    "description":"World is here"
}

### READ Task

URL - http://localhost/task-manager/?id=1
Method - GET

