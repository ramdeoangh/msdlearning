# Environment Variables Setup

This project now supports environment variables for configuration. This allows you to keep sensitive information like database credentials out of your code and version control.

## Setup Instructions

### 1. Copy the Environment Template
```bash
cp .env.example .env
```

### 2. Edit Your Environment File
Open `.env` and update the values according to your environment:

```env
# Database Configuration
DB_HOST=localhost
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
DB_DATABASE=your_database_name
DB_DRIVER=mysqli
DB_PREFIX=
DB_CHARSET=utf8
DB_COLLATION=utf8_general_ci

# Application Configuration
APP_ENV=development
APP_DEBUG=true

# Security
ENCRYPTION_KEY=your_encryption_key_here

# Email Configuration (if needed)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls

# Other configurations
TIMEZONE=UTC
```

### 3. Environment Variables Available

| Variable | Description | Default Value |
|----------|-------------|---------------|
| `DB_HOST` | Database host | `localhost` |
| `DB_USERNAME` | Database username | `u694807547_ci_dev_mds_umi` |
| `DB_PASSWORD` | Database password | `Ramdeo321#@!` |
| `DB_DATABASE` | Database name | `u694807547_mds_ci` |
| `DB_DRIVER` | Database driver | `mysqli` |
| `DB_PREFIX` | Database table prefix | `` |
| `DB_CHARSET` | Database charset | `utf8` |
| `DB_COLLATION` | Database collation | `utf8_general_ci` |
| `APP_ENV` | Application environment | `production` |
| `APP_DEBUG` | Debug mode | `true` |

### 4. Security Notes

- **Never commit `.env` files** to version control
- The `.env` file is already in `.gitignore`
- Use `.env.example` as a template for other developers
- Keep your production `.env` file secure and separate

### 5. Production Deployment

For production deployment:

1. Create a `.env` file on your production server
2. Set appropriate values for production
3. Ensure the file has proper permissions (600 or 644)
4. Never use development credentials in production

### 6. Fallback Behavior

If environment variables are not set, the system will fall back to the default values defined in the configuration files. This ensures backward compatibility.

### 7. Testing Environment Variables

You can test if environment variables are working by adding this to any PHP file:

```php
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_USERNAME: " . getenv('DB_USERNAME') . "\n";
echo "DB_DATABASE: " . getenv('DB_DATABASE') . "\n";
```

## Troubleshooting

### Environment Variables Not Loading
- Ensure `.env` file exists in the project root
- Check file permissions
- Verify the file format (no spaces around `=`)
- Make sure there are no syntax errors in the `.env` file

### Database Connection Issues
- Verify database credentials in `.env` file
- Check if database server is running
- Ensure database user has proper permissions
- Test connection with default values first
