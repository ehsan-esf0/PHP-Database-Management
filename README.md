# 🗃️ PHP Database Management Class

📂 A robust and easy-to-use PHP class for managing MySQL databases with PDO. This class provides a wide range of functionalities to handle databases, tables, and data with ease.

---

## 🌟 Features

- **🔗 Database Connection**: Easily connect to MySQL databases using PDO.
- **🛠️ Database Management**: Create and delete databases effortlessly.
- **📊 Table Operations**: Create, delete, rename, and modify tables.
- **🔧 Column Operations**: Add, delete, rename, and modify columns.
- **🔑 Foreign Keys**: Add foreign keys with support for `ON DELETE` and `ON UPDATE` actions.
- **📝 Data Operations**: Insert, select, update, and delete data with ease.
- **✅ Error Handling**: Built-in error handling to ensure smooth operations.

---

## 🚀 Getting Started

### Prerequisites

- PHP 7.0 or higher
- MySQL database
- PDO extension enabled

### Installation

1. Clone the repository or download the `Database.php` file.
   ```bash
   git clone https://github.com/yourusername/your-repo-name.git
   ```
2. Include the `Database.php` file in your project.
   ```php
   require_once 'path/to/Database.php';
   ```

### Usage

#### 1. Connecting to a Database
```php
$db = new Database('hostname', 'database_name', 'username', 'password');
```

#### 2. Creating a Table
```php
$columns = [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(100) NOT NULL',
    'email' => 'VARCHAR(100) NOT NULL'
];
$db->createTable('users', $columns);
```

#### 3. Inserting Data
```php
$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com'
];
$db->insert_Into_Table('users', $data);
```

#### 4. Selecting Data
```php
$result = $db->select_From_Table('users', ['id', 'name', 'email'], "name LIKE '%John%'");
print_r($result);
```

#### 5. Updating Data
```php
$data = [
    'email' => 'john.doe@example.com'
];
$db->update_Table('users', $data, "id = 1");
```

#### 6. Deleting Data
```php
$db->delete_From_Table('users', "id = 1");
```

## 🤝 Contributing

Contributions are welcome! If you have any suggestions, bug reports, or feature requests, please open an issue or submit a pull request.

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a pull request.
