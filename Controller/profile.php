<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/User.php");

$user_id = $_SESSION["user_id"];

$name = $_SESSION["name"];
$email = $_SESSION["email"];
$role = $_SESSION["role"];

$phone = getUserPhone($user_id);

require("../View/common/profile.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile - CampusNest</title>
    <link rel="stylesheet" href="../assets/styles.css" />
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        .profile-field {
            margin-bottom: 20px;
        }
        .profile-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .profile-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .profile-field input:disabled {
            background: #f5f5f5;
            color: #666;
        }
        .btn-save {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn-save:hover { background: #218838; }
        .btn-ajax {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-ajax:hover { background: #0056b3; }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .btn-danger:hover { background: #c82333; }
        .ajax-section {
            margin-top: 30px;
            border-top: 2px solid #e0e0e0;
            padding-top: 20px;
        }
        .loading { color: #666; font-style: italic; }
        .error { color: #d9534f; }
        .success { color: #5cb85c; }
        .form-group { margin: 10px 0; }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .profile-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
        .sign-out {
            display: inline-block;
            margin-top: 20px;
            color: #dc3545;
            text-decoration: none;
        }
        .sign-out:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header class="nest-topbar">
        <a href="../index.php" class="nest-brand">CampusNest</a>
        <span class="nest-role-pill line"><?php echo htmlspecialchars($role); ?></span>
    </header>

    <main class="nest-page">
        <div class="nest-shell profile-container">
            <a href="<?php echo ($role === 'seeker') ? '../seeker/seeker-dashboard.php' : '../lister/lister-dashboard.php'; ?>" class="back-link">
                ← Back to Dashboard
            </a>

            <div class="profile-avatar">
                <?php echo strtoupper(substr($name, 0, 1)); ?>
            </div>

            <h1 class="nest-heading">My Profile</h1>
            <p class="nest-sub">View and manage your personal information</p>

            <!-- PROFILE FORM (Regular POST, non-AJAX) -->
            <form action="../../Controller/profile.php" method="POST" novalidate>
                <div class="profile-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required />
                </div>

                <div class="profile-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required />
                </div>

                <div class="profile-field">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Enter 11-digit phone number" required />
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
            </form>

            <a href="../../Controller/logout.php" class="sign-out">Sign Out</a>

            <!-- ============================================= -->
            <!-- AJAX SECTION 1: Load Profile (GET)            -->
            <!-- ============================================= -->
            <div class="ajax-section">
                <h2>👤 Refresh Profile Data</h2>
                <p>Click the button below to fetch your latest profile data without refreshing the page.</p>
                <button id="loadProfileBtn" class="btn-ajax">Load My Profile</button>
                <div id="profileData"></div>
            </div>

            <!-- ============================================= -->
            <!-- AJAX SECTION 2: Change Password (POST)        -->
            <!-- ============================================= -->
            <div class="ajax-section">
                <h2>🔐 Change Password</h2>
                <p>Update your password securely without reloading the page.</p>
                <form id="changePasswordForm">
                    <div class="form-group">
                        <input type="password" id="currentPassword" placeholder="Current Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="newPassword" placeholder="New Password (min 6 chars)" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn-save" style="background:#007bff;">Update Password</button>
                </form>
                <div id="passwordMessage"></div>
            </div>

        </div>
    </main>

    <script>
        // ============================================================
        // AJAX 1: GET – Load Profile (Absolute Path ব্যবহার করা হয়েছে)
        // ============================================================
        document.getElementById('loadProfileBtn').addEventListener('click', function() {
            const profileDiv = document.getElementById('profileData');
            profileDiv.innerHTML = '<div class="loading">Loading profile...</div>';

            const xhr = new XMLHttpRequest();
            // ✅ Absolute Path ব্যবহার করছি
            xhr.open('GET', '/Web%20tech%20project%203/Web%20tech%20project/Controller/ajax/get-profile.php', true);
            xhr.responseType = 'json';

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = xhr.response;
                    if (data.error) {
                        profileDiv.innerHTML = '<div class="error">' + data.error + '</div>';
                        return;
                    }
                    if (data.success) {
                        const user = data.user;
                        profileDiv.innerHTML = `
                            <div class="profile-box">
                                <p><strong>Name:</strong> ${user.name}</p>
                                <p><strong>Email:</strong> ${user.email}</p>
                                <p><strong>Phone:</strong> ${user.phone || 'Not provided'}</p>
                                <p><strong>Role:</strong> ${user.role}</p>
                            </div>
                        `;
                    }
                } else {
                    profileDiv.innerHTML = '<div class="error">Error loading profile.</div>';
                }
            };

            xhr.onerror = function() {
                profileDiv.innerHTML = '<div class="error">Network error occurred.</div>';
            };

            xhr.send();
        });

        // ============================================================
        // AJAX 2: POST – Change Password (Absolute Path ব্যবহার করা হয়েছে)
        // ============================================================
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const messageDiv = document.getElementById('passwordMessage');

            if (newPassword.length < 6) {
                messageDiv.innerHTML = '<div class="error">New password must be at least 6 characters.</div>';
                return;
            }
            if (newPassword !== confirmPassword) {
                messageDiv.innerHTML = '<div class="error">Passwords do not match.</div>';
                return;
            }

            messageDiv.innerHTML = '<div class="loading">Updating password...</div>';

            const xhr = new XMLHttpRequest();
            // ✅ Absolute Path ব্যবহার করছি
            xhr.open('POST', '/Web%20tech%20project%203/Web%20tech%20project/Controller/ajax/change-password.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.responseType = 'json';

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const data = xhr.response;
                    if (data.error) {
                        messageDiv.innerHTML = '<div class="error">' + data.error + '</div>';
                        return;
                    }
                    if (data.success) {
                        messageDiv.innerHTML = '<div class="success">' + data.message + '</div>';
                        document.getElementById('changePasswordForm').reset();
                    }
                } else {
                    messageDiv.innerHTML = '<div class="error">Error updating password.</div>';
                }
            };

            xhr.onerror = function() {
                messageDiv.innerHTML = '<div class="error">Network error occurred.</div>';
            };

            const formData = 'current_password=' + encodeURIComponent(currentPassword) +
                            '&new_password=' + encodeURIComponent(newPassword) +
                            '&confirm_password=' + encodeURIComponent(confirmPassword);

            xhr.send(formData);
        });
    </script>

</body>
</html>