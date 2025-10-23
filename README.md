# Gateway Dashboard

A comprehensive Laravel-based dashboard application for managing gateway services, clients, pricing, and transaction monitoring. This application provides a complete solution for managing API gateway operations with advanced features like real-time monitoring, reporting, and client management.

## 🚀 Features

### Core Modules
- **User Management** - Complete user authentication and role-based access control
- **Client Management** - Client onboarding, credential management, and service assignments
- **Service Management** - Internal and external service configuration
- **Pricing Management** - Master pricing and custom pricing per client
- **Currency Management** - Multi-currency support for pricing
- **Transaction Monitoring** - Real-time transaction tracking and history
- **Balance Management** - Client balance tracking and top-up management
- **Reporting System** - Daily and monthly reports with export capabilities

### Advanced Features
- **Soft Delete** - All modules support soft delete for data preservation
- **Redis Caching** - Client data caching for improved performance
- **Real-time Dashboard** - 7-day transaction analytics with interactive charts
- **Export Functionality** - Excel and PDF export for reports
- **Search & Filtering** - Universal search across all modules
- **Responsive Design** - Mobile-friendly interface with Tailwind CSS
- **Role-based Access** - Admin, Operator, and Viewer roles

## 📋 Requirements

- PHP >= 8.1
- Laravel >= 10.0
- PostgreSQL >= 13.0
- Redis >= 6.0
- Node.js >= 16.0
- Composer
- NPM/Yarn

## 🛠️ Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd gateway_dashboard
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Configure your database in .env file
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=gateway
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 5. Redis Configuration
```bash
# Configure Redis in .env file
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 6. Build Assets
```bash
# Build frontend assets
npm run build

# Or for development
npm run dev
```

### 7. Start the Application
```bash
# Start Laravel development server
php artisan serve

# Start queue worker (for background jobs)
php artisan queue:work
```

## 🗄️ Database Schema

### Core Tables
- `users` - User authentication and roles
- `clients` - Client information and credentials
- `services` - Available services (internal/external)
- `currencies` - Supported currencies
- `price_masters` - Default pricing for services
- `price_customs` - Custom pricing per client
- `balances` - Client balance tracking
- `balance_topups` - Balance top-up transactions
- `histories` - Transaction history and logs
- `service_assigns` - Client-service assignments

### Key Relationships
- Clients have many Services (many-to-many)
- Services have many Price Masters and Price Customs
- Clients have many Balances and Balance Topups
- All modules support soft delete with `is_active` field

## 🔐 Authentication & Authorization

### User Roles
- **Admin** - Full system access
- **Operator** - Limited administrative access
- **Viewer** - Read-only access

### Security Features
- Password hashing with bcrypt
- CSRF protection
- SQL injection prevention
- XSS protection
- Role-based route protection

## 📊 Dashboard Features

### Real-time Analytics
- 7-day transaction trends
- Revenue tracking
- Success rate monitoring
- Top services and clients
- Interactive charts with Chart.js

### Reporting System
- **Daily Reports** - Comprehensive daily transaction analysis
- **Monthly Reports** - Monthly trends and statistics
- **Export Options** - Excel and PDF export
- **Filtering** - Date range, client, and service filters

## 🔧 API Integration

### Client Credentials
- API Key (AK) and Secret Key (SK) management
- AVKey encryption with IV and Pass
- Redis caching for client authentication
- Whitelist IP management

### Service Configuration
- Module 40 configuration
- Service allow lists
- Custom pricing per client
- Real-time service monitoring

## 🎨 Frontend Technology

### UI Framework
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Chart.js** - Interactive charts and graphs
- **Custom Blade Components** - Reusable UI components

### Key Components
- `x-card` - Card layout component
- `x-input` - Form input component
- `x-select` - Dropdown select component
- `x-badge` - Status badge component
- `x-button` - Button component
- `x-pagination` - Pagination component
- `x-sidebar-link` - Navigation component

## 📱 Responsive Design

The application is fully responsive and optimized for:
- Desktop computers
- Tablets
- Mobile phones
- Various screen sizes

## 🔄 Soft Delete Implementation

All modules implement soft delete functionality:

### Supported Modules
- Users
- Clients
- Services
- Currencies
- Price Customs
- Price Masters

### Soft Delete Features
- Data preservation
- Reversible operations
- Audit trail
- Referential integrity
- Performance optimization

## 📈 Performance Optimization

### Caching Strategy
- Redis for client data caching
- Query optimization
- Eager loading for relationships
- Pagination for large datasets

### Database Optimization
- Proper indexing
- Soft delete queries
- Relationship optimization
- Connection pooling

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

### Test Coverage
- Unit tests for models
- Feature tests for controllers
- Integration tests for services
- Database testing with transactions

## 📦 Deployment

### Production Setup
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=pgsql
REDIS_HOST=your_redis_host
QUEUE_CONNECTION=redis
```

## 🔧 Configuration

### Key Configuration Files
- `config/database.php` - Database configuration
- `config/redis.php` - Redis configuration
- `config/auth.php` - Authentication settings
- `config/cache.php` - Caching configuration

### Custom Settings
- Pagination limits
- Cache TTL settings
- Export formats
- Chart configurations

## 📚 Documentation

### Available Documentation
- `docs/SOFT_DELETE_IMPLEMENTATION.md` - Soft delete implementation guide
- `docs/USER_CRUD_ENHANCEMENT.md` - User management documentation
- `docs/CLIENT_CRUD_DOCUMENTATION.md` - Client management guide
- `docs/CLIENT_BALANCE_AUTO_CREATE.md` - Balance management documentation

## 🤝 Contributing

### Development Guidelines
1. Follow PSR-12 coding standards
2. Write comprehensive tests
3. Update documentation
4. Use meaningful commit messages
5. Follow Git flow workflow

### Code Structure
```
app/
├── Http/Controllers/     # API and web controllers
├── Models/              # Eloquent models
├── Services/            # Business logic services
├── Exports/             # Export classes
└── Http/Requests/       # Form validation

resources/
├── views/               # Blade templates
├── components/          # Blade components
└── css/                 # Stylesheets

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

## 🐛 Troubleshooting

### Common Issues

#### Database Connection
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();
```

#### Redis Connection
```bash
# Test Redis connection
php artisan tinker
Redis::ping();
```

#### Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Debug Mode
```bash
# Enable debug mode
APP_DEBUG=true

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📞 Support

### Getting Help
- Check the documentation in `docs/` folder
- Review the troubleshooting section
- Check Laravel and PostgreSQL logs
- Contact the development team

### Log Files
- Laravel logs: `storage/logs/laravel.log`
- Web server logs: Check your web server configuration
- Database logs: Check PostgreSQL logs

## 📄 License

This project is proprietary software. All rights reserved.

## 🔄 Version History

### Current Version: 1.0.0
- Initial release
- Complete CRUD operations for all modules
- Soft delete implementation
- Real-time dashboard
- Export functionality
- Responsive design

### Upcoming Features
- API rate limiting
- Advanced analytics
- Mobile app integration
- Webhook support
- Multi-tenant support

---

**Gateway Dashboard** - Your comprehensive solution for API gateway management and monitoring.
