<?php
// Include database configuration
require_once 'config.php';

// Pagination variables
$recordsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Get total number of records
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get users data with pagination
$sql = "SELECT id, CONCAT(first_name, ' ', last_name) as full_name, email, 
        CONCAT(birth_date, ' ', birth_time) as birth_datetime, 
        website, gender, color, salary, bio, newsletter, profile_image,
        address, city, state, country, qualification, created_at, updated_at
        FROM users 
        ORDER BY created_at DESC 
        LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users - User Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .color-swatch {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            display: inline-block;
            border: 1px solid #ddd;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .table-responsive {
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(101, 78, 163, 0.05);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Registered Users</h1>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add New User
            </a>
        </div>

        <?php if (isset($_GET['delete'])): ?>
            <div class="alert alert-<?php echo $_GET['delete'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php 
                if ($_GET['delete'] === 'success') {
                    echo 'User deleted successfully!';
                } else {
                    echo 'Error deleting user. Please try again.';
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Birth Date/Time</th>
                        <th>Gender</th>
                        <th>Color</th>
                        <th>Salary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php $count = $offset + 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td>
                                    <?php if (!empty($row['profile_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile" class="user-avatar">
                                    <?php else: ?>
                                        <div class="user-avatar bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo date('M j, Y h:i A', strtotime($row['birth_datetime'])); ?></td>
                                <td>
                                    <?php 
                                    $genderIcon = '';
                                    switch (strtolower($row['gender'])) {
                                        case 'male':
                                            $genderIcon = '<i class="fas fa-mars text-primary"></i>';
                                            break;
                                        case 'female':
                                            $genderIcon = '<i class="fas fa-venus text-danger"></i>';
                                            break;
                                        default:
                                            $genderIcon = '<i class="fas fa-genderless text-secondary"></i>';
                                    }
                                    echo $genderIcon . ' ' . ucfirst($row['gender']);
                                    ?>
                                </td>
                                <td>
                                    <div class="color-swatch" style="background-color: <?php echo htmlspecialchars($row['color']); ?>"></div>
                                </td>
                                <td>$<?php echo number_format($row['salary'], 2); ?></td>
                                <td class="action-btns">
                                    <button class="btn btn-sm btn-info text-white view-btn" data-bs-toggle="modal" data-bs-target="#userModal" 
                                        data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
                                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                        data-birth="<?php echo date('F j, Y, g:i a', strtotime($row['birth_datetime'])); ?>"
                                        data-website="<?php echo !empty($row['website']) ? htmlspecialchars($row['website']) : 'N/A'; ?>"
                                        data-gender="<?php echo ucfirst($row['gender']); ?>"
                                        data-color="<?php echo htmlspecialchars($row['color']); ?>"
                                        data-salary="$<?php echo number_format($row['salary'], 2); ?>"
                                        data-bio="<?php echo !empty($row['bio']) ? nl2br(htmlspecialchars($row['bio'])) : 'N/A'; ?>"
                                        data-address="<?php echo htmlspecialchars($row['address']); ?>"
                                        data-city="<?php echo htmlspecialchars($row['city']); ?>"
                                        data-state="<?php echo htmlspecialchars($row['state']); ?>"
                                        data-country="<?php echo htmlspecialchars($row['country']); ?>"
                                        data-qualification="<?php echo htmlspecialchars($row['qualification']); ?>"
                                        data-newsletter="<?php echo $row['newsletter'] ? 'Yes' : 'No'; ?>"
                                        data-created="<?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>"
                                        data-updated="<?php echo date('F j, Y, g:i a', strtotime($row['updated_at'])); ?>"
                                        data-image="<?php echo !empty($row['profile_image']) ? htmlspecialchars($row['profile_image']) : ''; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['id']; ?>" data-name="<?php echo htmlspecialchars($row['full_name']); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p class="mb-0">No users found. <a href="index.php">Register a new user</a> to get started.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- User Details Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="userModalLabel">User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div id="userImage" class="mx-auto mb-3" style="width: 150px; height: 150px; border-radius: 50%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <i class="fas fa-user text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 id="userName" class="mb-1"></h4>
                            <p class="text-muted mb-0" id="userEmail"></p>
                            <div class="d-flex justify-content-center align-items-center mt-2" id="userGender"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Birth Date/Time</p>
                                    <p id="userBirth" class="mb-3"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Website</p>
                                    <p id="userWebsite" class="mb-3"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Favorite Color</p>
                                    <div class="d-flex align-items-center">
                                        <div id="userColor" class="me-2" style="width: 30px; height: 30px; border-radius: 4px; border: 1px solid #ddd;"></div>
                                        <span id="userColorText"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Salary (Yearly)</p>
                                    <p id="userSalary"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <p class="mb-1 text-muted">Bio</p>
                                    <p id="userBio" class="bg-light p-3 rounded"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Address</p>
                                    <p id="userAddress"></p>
                                    <p id="userCityState"></p>
                                    <p id="userCountry"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <p class="mb-1 text-muted">Qualification</p>
                                    <p id="userQualification"></p>
                                    
                                    <p class="mb-1 text-muted">Newsletter</p>
                                    <p id="userNewsletter"></p>
                                </div>
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex justify-content-between text-muted small">
                                        <div>
                                            <p class="mb-0">Created: <span id="userCreated"></span></p>
                                        </div>
                                        <div>
                                            <p class="mb-0">Last Updated: <span id="userUpdated"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Delete User</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // User Details Modal
        document.addEventListener('DOMContentLoaded', function() {
            // View user details
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const birth = this.getAttribute('data-birth');
                    const website = this.getAttribute('data-website');
                    const gender = this.getAttribute('data-gender');
                    const color = this.getAttribute('data-color');
                    const salary = this.getAttribute('data-salary');
                    const bio = this.getAttribute('data-bio');
                    const address = this.getAttribute('data-address');
                    const city = this.getAttribute('data-city');
                    const state = this.getAttribute('data-state');
                    const country = this.getAttribute('data-country');
                    const qualification = this.getAttribute('data-qualification');
                    const newsletter = this.getAttribute('data-newsletter');
                    const created = this.getAttribute('data-created');
                    const updated = this.getAttribute('data-updated');
                    const image = this.getAttribute('data-image');

                    // Set modal content
                    document.getElementById('userName').textContent = name;
                    document.getElementById('userEmail').textContent = email;
                    document.getElementById('userBirth').textContent = birth;
                    
                    // Handle website link
                    const userWebsite = document.getElementById('userWebsite');
                    if (website !== 'N/A') {
                        userWebsite.innerHTML = `<a href="${website}" target="_blank">${website}</a>`;
                    } else {
                        userWebsite.textContent = 'N/A';
                    }
                    
                    // Set gender with icon
                    let genderIcon = '';
                    if (gender.toLowerCase() === 'male') {
                        genderIcon = '<i class="fas fa-mars text-primary me-1"></i>';
                    } else if (gender.toLowerCase() === 'female') {
                        genderIcon = '<i class="fas fa-venus text-danger me-1"></i>';
                    } else {
                        genderIcon = '<i class="fas fa-genderless text-secondary me-1"></i>';
                    }
                    document.getElementById('userGender').innerHTML = `${genderIcon} ${gender}`;
                    
                    // Set color
                    const userColor = document.getElementById('userColor');
                    const userColorText = document.getElementById('userColorText');
                    userColor.style.backgroundColor = color;
                    userColorText.textContent = color;
                    
                    // Set salary
                    document.getElementById('userSalary').textContent = salary;
                    
                    // Set bio with line breaks
                    document.getElementById('userBio').innerHTML = bio;
                    
                    // Set address
                    document.getElementById('userAddress').textContent = address;
                    document.getElementById('userCityState').textContent = `${city}, ${state}`;
                    document.getElementById('userCountry').textContent = country;
                    
                    // Set other details
                    document.getElementById('userQualification').textContent = qualification;
                    document.getElementById('userNewsletter').innerHTML = newsletter === 'Yes' ? 
                        '<span class="badge bg-success">Subscribed</span>' : 
                        '<span class="badge bg-secondary">Not Subscribed</span>';
                    
                    // Set timestamps
                    document.getElementById('userCreated').textContent = created;
                    document.getElementById('userUpdated').textContent = updated;
                    
                    // Set image if available
                    const userImage = document.getElementById('userImage');
                    userImage.innerHTML = ''; // Clear previous content
                    if (image) {
                        const img = document.createElement('img');
                        img.src = image;
                        img.alt = name;
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        userImage.appendChild(img);
                    } else {
                        const icon = document.createElement('i');
                        icon.className = 'fas fa-user text-muted';
                        icon.style.fontSize = '4rem';
                        userImage.appendChild(icon);
                    }
                });
            });

            // Delete user confirmation
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');
                    
                    document.getElementById('deleteUserName').textContent = userName;
                    confirmDeleteBtn.href = `delete_user.php?id=${userId}`;
                    
                    deleteModal.show();
                });
            });
        });
    </script>
</body>
</html>
