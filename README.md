# 🔐 SecurePass | PHP Authentication & RBAC System

A robust User Authentication system built with **PHP 8** and **MySQL**. This project serves as a secure "Gatekeeper" layer that can be integrated into any web application to manage user access and permissions.



## 🛠️ Security Features

* **Password Hashing:** Uses industry-standard `PASSWORD_BCRYPT` (Salted hashes) to ensure user data remains unreadable even if the database is compromised.
* **Role-Based Access Control (RBAC):** Distinct permissions for `Admin` and `User` roles.
* **Session Management:** Secure session handling to maintain user state and prevent unauthorized URL access.
* **Server-Side Validation:** Protects against common bypass techniques by checking credentials on every page load.

## 🚀 Key Logic Implemented

1.  **Hashed Registration:** Never stores plain-text passwords.
2.  **Verified Login:** Uses `password_verify()` to cross-check credentials against the database hash.
3.  **The Gatekeeper Pattern:** Redirects unauthenticated users back to the login page automatically.
4.  **Admin Protection:** Hard-blocks specific pages and UI elements from non-admin accounts.

## ⚙️ Setup

1. Clone the repo.
2. Import `secure_db.sql` (found in the root folder) into your phpMyAdmin.
3. Configure `db_config.php` with your local database credentials.
4. Start registering users!

---
Developed by **LordS7ark** | Focus: Web Security & Data Integrity