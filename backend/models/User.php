<?php
// models/User.php

class User {
    private $conn;
    private $table_name = "users"; // Asegúrate de que coincida con el nombre de la tabla en tu base de datos

    public $id;
    public $username;
    public $password;
    public $email;
    public $role_id;
    public $first_name;
    public $last_name;
    public $date_joined;
    public $last_login;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para obtener todos los usuarios
    public function read() {
        // Actualización de la consulta para incluir el nombre del rol desde la tabla `roles`
        $query = "SELECT u.*, r.name as role_name 
                  FROM " . $this->table_name . " u 
                  LEFT JOIN roles r ON u.role_id = r.id"; // Join con la tabla roles
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

    // Método para actualizar la última fecha de inicio de sesión
    public function updateLastLogin($id) {
        $query = "UPDATE " . $this->table_name . " SET last_login = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, first_name, last_name, role_id, date_joined) 
                  VALUES (:username, :email, :password, :first_name, :last_name, :role_id, NOW())";
    
        $stmt = $this->conn->prepare($query);
    
        // Sanitizar y enlazar parámetros
        $username = htmlspecialchars(strip_tags($data['username']));
        $email = htmlspecialchars(strip_tags($data['email']));
        $password = password_hash(htmlspecialchars(strip_tags($data['password'])), PASSWORD_BCRYPT); // Hash la contraseña aquí
        $first_name = htmlspecialchars(strip_tags($data['first_name']));
        $last_name = htmlspecialchars(strip_tags($data['last_name']));
        $role_id = htmlspecialchars(strip_tags($data['role_id']));
    
        // Vincular los parámetros al statement
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // Asegúrate de usar la contraseña hasheada
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':role_id', $role_id);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
    
        // Retornar falso en caso de fallo
        return false;
    }
    

    // Método para actualizar un usuario existente
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . "
                  SET username = :username, email = :email, role_id = :role_id, first_name = :first_name, last_name = :last_name
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // Verificar que las claves existen antes de acceder a ellas
        $username = isset($data['username']) ? htmlspecialchars(strip_tags($data['username'])) : '';
        $email = isset($data['email']) ? htmlspecialchars(strip_tags($data['email'])) : '';
        $role_id = isset($data['role_id']) ? htmlspecialchars(strip_tags($data['role_id'])) : '';
        $first_name = isset($data['first_name']) ? htmlspecialchars(strip_tags($data['first_name'])) : '';
        $last_name = isset($data['last_name']) ? htmlspecialchars(strip_tags($data['last_name'])) : '';
    
        // Vincula los parámetros al statement
        $stmt->bindParam(':id', htmlspecialchars(strip_tags($id)));
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    
    
    

    // Método para eliminar un usuario
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Enlazar parámetro
        $id_clean = htmlspecialchars(strip_tags($id)); // Crea una variable
        $stmt->bindParam(':id', $id_clean); // Usa la variable
        

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Método para verificar las credenciales del usuario
    public function verifyLogin($email, $password) {
        // Agregar un registro para verificar la entrada
        error_log("Verificando login para el email: $email");
    
        $query = "SELECT u.*, r.name as role_name FROM " . $this->table_name . " u 
                  LEFT JOIN roles r ON u.role_id = r.id
                  WHERE u.email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Agregar un registro para verificar la contraseña que viene de la base de datos
            error_log("Contraseña hash recuperada: " . $row['password']);
    
            // Verificar la contraseña
            if (password_verify($password, $row['password'])) {
                // Eliminar la contraseña de la respuesta
                unset($row['password']);
                error_log("Inicio de sesión exitoso para el email: $email");
                return $row; // Devuelve la información del usuario con el nombre del rol
            } else {
                error_log("Contraseña incorrecta para el email: $email");
            }
        } else {
            error_log("No se encontró el usuario con el email: $email");
        }
    
        return false; // Retornar falso si no se encuentra el usuario o la contraseña no coincide
    }
    
    

    // Método para cambiar la contraseña del usuario
    public function changePassword($id, $newPassword) {
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Encriptar la nueva contraseña antes de almacenarla
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        return false;
    }
    
}
?>
