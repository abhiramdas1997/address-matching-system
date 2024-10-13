
# Address Matching System

A web-based **Address Matching System** built with **Laravel**, designed to **upload, compare, and export address datasets**. This system supports both **exact string matching** and **fuzzy matching** using Levenshtein distance.

## Table of Contents

1. [Features](#features)  
2. [Technologies Used](#technologies-used)  
3. [Prerequisites](#prerequisites)  
4. [Installation](#installation)  
5. [Configuration](#configuration)  
6. [Usage](#usage)  
7. [File Structure](#file-structure)  
8. [API Endpoints](#api-endpoints)  
9. [Troubleshooting](#troubleshooting)  
10. [Contributing](#contributing)  
11. [License](#license)  

## Features

- Upload client and listing addresses via `.txt` files.  
- Compare addresses using:
  - **Exact String Matching**.
  - **Fuzzy Matching** with adjustable similarity threshold.  
- Export comparison results as **CSV files**.  
- Handle large files efficiently using **generators**.  
- Provide **user-friendly error handling** and **logging**.  

## Technologies Used

- **Laravel**: PHP web framework.  
- **PHP 8+**: Backend language.  
- **MySQL**: Database for storing addresses.  
- **Levenshtein Algorithm**: For fuzzy matching.  
- **Carbon**: Date/time management.  
- **Bootstrap** (Optional): For UI components.  

## Prerequisites

Make sure you have the following installed on your machine:

- **PHP 8.0+**
- **Composer**
- **MySQL**
- **Git**
- **Laravel CLI**  
- **Node.js and NPM** (if using frontend components)

## Installation

Follow the steps below to set up the project locally:

1. **Clone the Repository**

   ```bash
   git clone <repository-url>
   cd address-matching-system
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Set Up Environment Variables**

   Copy the `.env.example` to `.env` and configure the database credentials.

   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Database Migrations**

   ```bash
   php artisan migrate
   ```

6. **Set Permissions**

   Ensure the `storage/` and `bootstrap/cache/` directories are writable.

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

7. **Start the Server**

   ```bash
   php artisan serve
   ```

   The application will be available at `http://127.0.0.1:8000`.

## Configuration

### Set the Fuzzy Match Threshold

The **fuzzy matching threshold** can be configured in the `config/constants.php` file.

```php
return [
    'FUZZY_MATCH_THRESHOLD' => 80, // Default threshold
];
```

## Usage

### Upload Addresses

1. Navigate to `/upload`.
2. Select two `.txt` files:
   - One for **client data**.
   - One for **listing data**.
3. Click **Upload**.

### Compare Addresses

1. After uploading, you will be redirected to `/compare`.
2. Use the **dropdown menu** to switch between **String Matching** and **Fuzzy Matching**.

### Export Results

1. Click **Export to CSV** to download the results.
2. The CSV filename will be based on the comparison type:
   - `String Match Result.csv`
   - `Fuzzy Match Result.csv`

## File Structure

```plaintext
address-matching-system/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AddressController.php
│   │   └── Requests/
│   │       └── UploadAddressesRequest.php
│   ├── Models/
│   │   ├── ClientAddress.php
│   │   └── ListingAddress.php
│   └── Services/
│       └── AddressService.php
├── config/
│   └── constants.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── upload.blade.php
│       └── compare.blade.php
├── routes/
│   └── web.php
├── storage/
└── .env.example
```

## API Endpoints

1. **Upload Form**: `GET /upload`
2. **Upload Addresses**: `POST /upload`
3. **Compare Addresses**: `GET /compare?type={string|fuzzy}`
4. **Export CSV**: `GET /export-csv?type={string|fuzzy}`

## Troubleshooting

1. **File Upload Errors**  
   - Ensure the uploaded files are **valid `.txt` files**.
   - Check that **file permissions** are correctly set.

2. **Database Errors**  
   - Run migrations with `php artisan migrate` if tables are missing.
   - Ensure the **database credentials** are correct in the `.env` file.

3. **Levenshtein Function Not Working**  
   - Ensure **PHP 8+** is installed and enabled.

4. **Performance Issues with Large Files**  
   - Use the **readFileInLines() generator** to handle large datasets efficiently.

## Contributing

1. **Fork the Repository**.
2. **Create a Feature Branch**:  
   ```bash
   git checkout -b feature/your-feature
   ```
3. **Commit Changes**:  
   ```bash
   git commit -m "Add your message"
   ```
4. **Push to Your Branch**:  
   ```bash
   git push origin feature/your-feature
   ```
5. **Open a Pull Request**.

## License

This project is licensed under the **MIT License**.

## Author

Developed by **Abhiram Das**  
Email: [abhiramdas2525@gmail.com](mailto:abhiramdas2525@gmail.com)
