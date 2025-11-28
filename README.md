# BookManagement

Official repository for BookManagement - A comprehensive book management software licensed under GNU General Public License v3.0.

## Prerequisites

- Docker or Podman
- 2 GB RAM (minimum)
- 15 GB free disk space

## Installation

### 1. Configure Database Credentials

Before running the application, you must change the default database credentials in `docker-compose.yml`:
```yaml
MYSQL_USER: your_username
MYSQL_PASSWORD: your_secure_password
```

**Security Warning**: Never use default credentials in production!

### 2. Start the Application

**Using Docker:**
```bash
cd /path/to/BookManagement
cd run
./docker.sh
    -s rebuild, start
    -r stopp, rebuild and start
    -d shutdown
```

**Using Podman:**
```bash
cd /path/to/BookManagement
cd run
./podman.sh
    -s rebuild, start
    -r stopp, rebuild and start
    -d shutdown
```

## Access

After starting the application, access the web interface at: http://localhost:YOUR_PORT, you can change the default Port to an other Port.

## License

This project is licensed under the GNU General Public License v3.0.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

For issues and questions, please use the GitHub Issues page.