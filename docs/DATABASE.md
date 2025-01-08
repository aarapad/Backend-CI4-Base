3. `docs/DATABASE.md`:
```markdown
# Database Schema

## Users Table
Main table for user management.

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

## Columns Description

id: Unique identifier
email: User's email address (unique)
username: User's username (unique)
password: Hashed password
name: User's full name
role: User role (admin/user)
is_active: Account status
created_at: Record creation timestamp
updated_at: Record last update timestamp

## Indexes

PRIMARY KEY on id
UNIQUE KEY on email
UNIQUE KEY on username