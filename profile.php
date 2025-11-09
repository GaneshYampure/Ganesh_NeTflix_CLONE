<?php
// trypro.php
session_start();
include 'dtata.php';

if (!isset($_SESSION['email'])) {
    header('Location: signin.php');
    exit();
}

$email = $_SESSION['email'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteProfileId'])) {
        // Delete profile
        $stmt = $con->prepare("DELETE FROM userprofile WHERE id = ? AND email = ?");
        $stmt->bind_param("is", $_POST['deleteProfileId'], $email);
        $stmt->execute();
    } elseif (isset($_POST['profileId'])) {
        // Update profile
        $stmt = $con->prepare("UPDATE userprofile SET profile = ? WHERE id = ? AND email = ?");
        $stmt->bind_param("sis", $_POST['profileName'], $_POST['profileId'], $email);
        $stmt->execute();
    } else {
        // Create new profile
        $stmt = $con->prepare("INSERT INTO userprofile (email, profile) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $_POST['profileName']);
        $stmt->execute();
    }
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}

// Get existing profiles
$stmt = $con->prepare("SELECT id, profile FROM userprofile WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$profiles = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix Profile Manager</title>
    <style>
        /* Import the Netflix Sans font */
        @font-face {
            font-family: 'Netflix Sans';
            font-weight: 300;
            src: url('https://assets.nflxext.com/ffe/siteui/fonts/netflix-sans/v3/NetflixSans_W_Lt.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Netflix Sans';
            font-weight: 400;
            src: url('https://assets.nflxext.com/ffe/siteui/fonts/netflix-sans/v3/NetflixSans_W_Rg.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Netflix Sans';
            font-weight: 500;
            src: url('https://assets.nflxext.com/ffe/siteui/fonts/netflix-sans/v3/NetflixSans_W_Md.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Netflix Sans';
            font-weight: 700;
            src: url('https://assets.nflxext.com/ffe/siteui/fonts/netflix-sans/v3/NetflixSans_W_Bd.woff2') format('woff2');
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Netflix Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        
        body {
            background-color: #141414;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        .netflix-logo {
            position: absolute;
            top:-50px;
            left: -0px;
            width: clamp(90px, 15vw, 250px);
            z-index: 10;
        }
        
        h1 {
            font-size: 3.5vw;
            margin-bottom: 2rem;
            font-weight: 500;
            letter-spacing: -0.5px;
            text-align: center;
        }
        
        .profiles-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2vw;
            margin-bottom: 4rem;
            max-width: 80vw;
        }
        
        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0.5rem;
            width: 10vw;
            min-width: 84px;
            max-width: 200px;
        }
        
        .profile:hover {
            transform: scale(1.1);
        }
        
        .profile-box {
            width: 10vw;
            height: 10vw;
            min-width: 84px;
            min-height: 84px;
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: rgb(43, 43, 43);
            position: relative;
            border: none;
            transition: all 0.3s ease;
        }
        
        .profile-letter {
            font-size: 5vw;
            min-font-size: 42px;
            max-font-size: 100px;
            color: #e5e5e5;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1;
            text-shadow: 0 1px 3px rgba(0,0,0,0.7);
            user-select: none;
        }
        
        .profile:hover .profile-box {
            border: 2px solid white;
        }
        
        .profile-name {
            color: #808080;
            font-size: 1.2vw;
            min-font-size: 12px;
            max-font-size: 20px;
            font-weight: 400;
            text-align: center;
            transition: all 0.3s ease;
            margin-top: 10px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .profile:hover .profile-name {
            color: white;
        }
        
        .edit-icon {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            justify-content: center;
            align-items: center;
            z-index: 5;
        }
        
        .edit-mode .profile:hover .edit-icon {
            display: flex;
        }
        
        .edit-mode .profile-box {
            opacity: 0.5;
        }
        
        .manage-profiles {
            padding: 0.8em 2em;
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #808080;
            font-size: 1.2vw;
            min-font-size: 13px;
            max-font-size: 18px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .manage-profiles:hover {
            border-color: white;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 100;
        }
        
        .modal-content {
            background-color: #181818;
            padding: 2rem;
            border-radius: 3px;
            width: 500px;
            max-width: 90%;
            color: white;
            border: 1px solid #333;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .modal-header h2 {
            font-size: 2rem;
            font-weight: 500;
        }
        
        .close-modal {
            background: none;
            border: none;
            color: #808080;
            font-size: 2rem;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close-modal:hover {
            color: white;
        }
        
        .form-group {
            margin-bottom: 2rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #808080;
            font-size: 1.1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            background-color: #333;
            border: none;
            border-radius: 2px;
            color: white;
            font-size: 1.1rem;
        }
        
        .form-group input:focus {
            outline: none;
            background-color: #454545;
        }
        
        .modal-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }
        
        .modal-actions-right {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.7em 1.5em;
            border-radius: 2px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            font-weight: 400;
        }
        
        .btn-cancel {
            background-color: transparent;
            color: #808080;
            border: 1px solid #808080;
        }
        
        .btn-cancel:hover {
            color: white;
            border-color: white;
        }
        
        .btn-continue {
            background-color: white;
            color: black;
            border: none;
        }
        
        .btn-continue:hover {
            background-color: #e6e6e6;
        }
        
        .btn-delete {
            background-color: transparent;
            color: #808080;
            border: 1px solid #808080;
        }
        
        .btn-delete:hover {
            color: #e50914;
            border-color: #e50914;
        }
        
        /* Add Profile Button Styling */
        #addProfile .profile-box {
            background-color: transparent;
            border: 1px solid #808080;
        }
        
        #addProfile .profile-letter {
            color: #808080;
            font-size: 4vw;
        }
        
        #addProfile:hover .profile-box {
            border-color: white;
        }
        
        #addProfile:hover .profile-letter {
            color: white;
        }
        
        /* Confirmation Dialog */
        .confirm-dialog {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 200;
        }
        
        .confirm-content {
            background-color: #181818;
            padding: 2rem;
            border-radius: 3px;
            width: 450px;
            max-width: 90%;
            text-align: center;
            border: 1px solid #333;
            color: white;
        }
        
        .confirm-content h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .confirm-content p {
            margin-bottom: 2rem;
            color: #ccc;
            line-height: 1.6;
        }
        
        .confirm-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }
            
            .profiles-container {
                gap: 10px;
            }
            
            .profile {
                width: 80px;
            }
            
            .profile-box {
                width: 80px;
                height: 80px;
            }
            
            .profile-letter {
                font-size: 40px;
            }
            
            .profile-name, .manage-profiles {
                font-size: 13px;
            }
        }
        
        @media (max-width: 480px) {
            .netflix-logo {
                left: 20px;
                width: 80px;
            }
            
            .profile {
                width: 60px;
            }
            
            .profile-box {
                width: 60px;
                height: 60px;
            }
            
            .profile-letter {
                font-size: 30px;
            }
            
            .modal-content {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 0.6em 1.2em;
                font-size: 0.9rem;
            }
            
            .modal-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .modal-actions-right {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <img src="images-removebg-preview.png" alt="Netflix" class="netflix-logo">
    <h1>Who's watching?</h1>
    
    <div class="profiles-container" id="profilesContainer">
        <?php foreach ($profiles as $profile): ?>
            <div class="profile" data-id="<?= $profile['id'] ?>">
                <div class="profile-box">
                    <div class="profile-letter"><?= substr($profile['profile'], 0, 1) ?></div>
                </div>
                <div class="profile-name"><?= htmlspecialchars($profile['profile']) ?></div>
                <div class="edit-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="profile" id="addProfile">
            <div class="profile-box">
                <div class="profile-letter">+</div>
            </div>
            <div class="profile-name">Add Profile</div>
        </div>
    </div>
    
    <button class="manage-profiles" id="manageProfiles">Manage Profiles</button>

    <!-- Add Profile Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Profile</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="addForm" method="post">
                <div class="form-group">
                    <label for="profileName">Name</label>
                    <input type="text" id="profileName" name="profileName" placeholder="Name" required maxlength="20">
                </div>
                <div class="modal-actions">
                    <div></div>
                    <div class="modal-actions-right">
                        <button type="button" class="btn btn-cancel">Cancel</button>
                        <button type="submit" class="btn btn-continue">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profile</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="editForm" method="post">
                <input type="hidden" id="editProfileId" name="profileId">
                <div class="form-group">
                    <label for="editProfileName">Name</label>
                    <input type="text" id="editProfileName" name="profileName" required maxlength="20">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-delete" id="deleteProfileBtn">Delete Profile</button>
                    <div class="modal-actions-right">
                        <button type="button" class="btn btn-cancel">Cancel</button>
                        <button type="submit" class="btn btn-continue">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Dialog -->
    <div class="confirm-dialog" id="deleteConfirmation">
        <div class="confirm-content">
            <h3>Delete Profile?</h3>
            <p>This profile will be permanently deleted. This cannot be undone.</p>
            <div class="confirm-actions">
                <button class="btn btn-cancel" id="cancelDelete">Keep Profile</button>
                <button class="btn btn-continue" id="confirmDelete">Delete Profile</button>
            </div>
        </div>
    </div>
    
    <!-- Hidden form for profile deletion -->
    <form id="deleteForm" method="post" style="display: none;">
        <input type="hidden" id="deleteProfileId" name="deleteProfileId">
    </form>

    <script>
        let editMode = false;
        const modals = document.querySelectorAll('.modal');
        const closeButtons = document.querySelectorAll('.close-modal, .btn-cancel');
        const profilesContainer = document.getElementById('profilesContainer');
        const deleteConfirmation = document.getElementById('deleteConfirmation');
        
        // Add Profile
        document.getElementById('addProfile').addEventListener('click', () => {
            if (!editMode) {
                showModal('addModal');
                document.getElementById('profileName').focus();
            }
        });

        // Manage Profiles
        document.getElementById('manageProfiles').addEventListener('click', () => {
            editMode = !editMode;
            
            document.querySelectorAll('.profile').forEach(profile => {
                if (editMode) {
                    profile.classList.add('edit-mode');
                } else {
                    profile.classList.remove('edit-mode');
                }
            });
            
            document.getElementById('manageProfiles').textContent = editMode ? 'Done' : 'Manage Profiles';
        });

        // Profile Click Handling
        document.querySelectorAll('.profile[data-id]').forEach(profile => {
            profile.addEventListener('click', (e) => {
                const profileId = profile.dataset.id;
                const profileName = profile.querySelector('.profile-name').textContent;
                
                if (editMode) {
                    showEditModal(profileId, profileName);
                } else {
                    // Redirect to profile page
                    window.location.href = `index.php?profile=${profileId}`;
                }
            });
        });

        // Modal Handling
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                closeAllModals();
            });
        });
        
        function closeAllModals() {
            modals.forEach(modal => {
                modal.style.display = 'none';
            });
            deleteConfirmation.style.display = 'none';
        }

        // Show modal function with animation
        function showModal(modalId) {
            closeAllModals();
            const modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            
            // Focus on input field
            setTimeout(() => {
                const input = modal.querySelector('input[type="text"]');
                if (input) input.focus();
            }, 300);
        }

        function showEditModal(profileId, profileName) {
            document.getElementById('editProfileId').value = profileId;
            document.getElementById('editProfileName').value = profileName;
            showModal('editModal');
        }

        // Delete Profile Handling
        document.getElementById('deleteProfileBtn').addEventListener('click', () => {
            const profileId = document.getElementById('editProfileId').value;
            document.getElementById('deleteProfileId').value = profileId;
            
            // Hide edit modal and show confirmation dialog
            document.getElementById('editModal').style.display = 'none';
            deleteConfirmation.style.display = 'flex';
        });
        
        // Cancel Delete
        document.getElementById('cancelDelete').addEventListener('click', () => {
            deleteConfirmation.style.display = 'none';
            showModal('editModal');
        });
        
        // Confirm Delete
        document.getElementById('confirmDelete').addEventListener('click', () => {
            document.getElementById('deleteForm').submit();
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            modals.forEach(modal => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
            if (e.target === deleteConfirmation) {
                deleteConfirmation.style.display = 'none';
            }
        });
        
        // Adding keyboard support
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });
    </script>
</body>
</html>