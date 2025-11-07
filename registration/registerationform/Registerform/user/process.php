<?php
header('Content-Type: application/json');

// Include database configuration
require_once 'config.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

try {
    // Check if the request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate required fields
    $requiredFields = [
        'first_name', 'last_name', 'email', 'password', 'confirm_password',
        'birth_date', 'birth_time', 'birth_month', 'birth_week', 'gender',
        'color', 'salary', 'bio', 'address', 'city', 'state', 'country', 'qualification'
    ];

    foreach ($requiredFields as $field) {
        if (empty(trim($_POST[$field] ?? ''))) {
            throw new Exception("Please fill in all required fields.");
        }
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        throw new Exception("Email already exists. Please use a different email.");
    }
    $stmt->close();

    // Validate password match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        throw new Exception("Passwords do not match");
    }

    // Validate password strength (at least 8 characters, 1 uppercase, 1 lowercase, 1 number)
    $password = $_POST['password'];
    if (strlen($password) < 8 || 
        !preg_match("/[A-Z]/", $password) || 
        !preg_match("/[a-z]/", $password) || 
        !preg_match("/[0-9]/", $password)) {
        throw new Exception("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.");
    }

    // Handle file upload
    $profileImage = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        // Validate file type
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            throw new Exception("File size exceeds 2MB limit.");
        }
        
        // Generate unique filename
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $fileExt;
        $uploadPath = 'uploads/' . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to upload file. Please try again.");
        }
        
        $profileImage = $uploadPath;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (
        first_name, last_name, email, password, birth_date, birth_time, 
        birth_month, birth_week, website, gender, color, salary, bio, 
        profile_image, address, city, state, country, qualification, newsletter
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    // Bind parameters
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    $website = !empty($_POST['website']) ? $_POST['website'] : null;
    
    $stmt->bind_param(
        "sssssssssssdsssssssi",
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $hashedPassword,
        $_POST['birth_date'],
        $_POST['birth_time'],
        $_POST['birth_month'],
        $_POST['birth_week'],
        $website,
        $_POST['gender'],
        $_POST['color'],
        $_POST['salary'],
        $_POST['bio'],
        $profileImage,
        $_POST['address'],
        $_POST['city'],
        $_POST['state'],
        $_POST['country'],
        $_POST['qualification'],
        $newsletter
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registration successful!';
    } else {
        throw new Exception("Error: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Close database connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>
