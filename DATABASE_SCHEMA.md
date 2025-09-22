# XpertBid Database Schema Documentation

## Overview
This document outlines the comprehensive database schema for the XpertBid Multi-Vendor E-Commerce & Auction Management System. The schema supports multi-tenancy, advanced auction management, property and vehicle management, and comprehensive e-commerce functionality.

## Core Tables

### 1. Multi-Tenancy Support

#### `tenants`
- **Purpose**: Manages multiple SaaS tenants with isolated data
- **Key Fields**:
  - `name`: Tenant business name
  - `domain`, `subdomain`: Domain management
  - `custom_domain`: Custom domain support
  - `database_name`: Isolated database per tenant
  - `status`: active, suspended, pending
  - `subscription_plan`: basic, premium, enterprise
  - `settings`: JSON configuration
  - `limits`: Resource limits (vendors, products, storage)
  - `white_label`: Branding customization
  - `monthly_fee`: Subscription pricing

### 2. User Management

#### `users` (Enhanced)
- **Purpose**: Extended user management with multi-tenant support
- **Key Fields**:
  - `tenant_id`: Links to tenant
  - `phone`, `date_of_birth`, `gender`: Extended profile
  - `avatar`: Profile image
  - `status`: active, inactive, suspended
  - `two_factor_enabled`: Security features
  - `preferences`: JSON user preferences

#### `user_roles`
- **Purpose**: Role-based access control
- **Key Fields**:
  - `role`: super_admin, admin, vendor, customer, estate_agent, sales_agent
  - `permissions`: JSON specific permissions
  - `is_active`: Role status

### 3. Vendor Management

#### `vendors`
- **Purpose**: Comprehensive vendor management
- **Key Fields**:
  - `business_name`, `business_type`: Business information
  - `business_registration_number`, `tax_id`: Legal compliance
  - `status`: pending, approved, rejected, suspended
  - `tier`: bronze, silver, gold, platinum
  - `store_name`, `store_slug`: Store identity
  - `store_theme`: JSON customization
  - `store_policies`: Shipping, return, privacy policies
  - `seo_settings`: Meta tags, keywords
  - `social_media`: Social platform integration
  - `commission_rate`: Revenue sharing
  - `verification_documents`: KYC compliance

### 4. Product Management

#### `categories`
- **Purpose**: Hierarchical product categorization
- **Key Fields**:
  - `parent_id`: Self-referencing for hierarchy
  - `name`, `slug`: Category identity
  - `image`, `icon`: Visual representation
  - `sort_order`: Display ordering
  - `is_featured`: Featured categories
  - `commission_rate`: Category-specific rates

#### `products`
- **Purpose**: Comprehensive product catalog
- **Key Fields**:
  - `name`, `slug`, `sku`: Product identity
  - `type`: physical, digital, service
  - `price`, `compare_price`, `cost_price`: Pricing
  - `quantity`, `min_quantity`, `max_quantity`: Inventory
  - `weight`, `length`, `width`, `height`: Physical dimensions
  - `status`: draft, active, inactive, archived
  - `images`: JSON array of product images
  - `attributes`, `variations`: Product variations
  - `seo_meta`: SEO optimization
  - `views_count`, `sales_count`: Analytics
  - `rating`, `reviews_count`: Customer feedback

### 5. Auction Management

#### `auctions`
- **Purpose**: Advanced auction system
- **Key Fields**:
  - `type`: english, reserve, buy_now, private
  - `starting_price`, `reserve_price`, `buy_now_price`: Pricing
  - `current_bid`: Real-time bidding
  - `bid_increment`: Automatic increments
  - `start_time`, `end_time`: Scheduling
  - `status`: scheduled, active, ended, cancelled
  - `auto_extend`: Anti-sniping feature
  - `extend_minutes`: Extension duration
  - `winner_id`: Auction winner
  - `images`: Auction images
  - `terms_conditions`: Auction terms

#### `bids`
- **Purpose**: Bid tracking and management
- **Key Fields**:
  - `amount`, `max_amount`: Bid amounts
  - `is_proxy_bid`: Automated bidding
  - `is_winning_bid`, `is_outbid`: Status tracking
  - `status`: active, outbid, winning, cancelled
  - `ip_address`, `user_agent`: Security tracking
  - `bid_time`: Timestamp

### 6. Property Management

#### `properties`
- **Purpose**: Real estate management
- **Key Fields**:
  - `property_type`: house, apartment, commercial, land
  - `listing_type`: sale, rent, both
  - `price`, `rent_price`: Pricing options
  - `address`, `city`, `state`, `country`: Location
  - `latitude`, `longitude`: GPS coordinates
  - `bedrooms`, `bathrooms`: Property details
  - `area_sqft`, `lot_size`: Size information
  - `year_built`: Construction year
  - `amenities`: JSON array (pool, garage, etc.)
  - `facilities`: Nearby facilities
  - `floor_plans`, `virtual_tour`: Media content
  - `commission_rate`: Agent commission

### 7. Vehicle Management

#### `vehicles`
- **Purpose**: Automotive marketplace
- **Key Fields**:
  - `vehicle_type`: car, motorcycle, truck, bus
  - `listing_type`: sale, rent, both
  - `make`, `model`, `year`: Vehicle identity
  - `variant`, `body_type`: Vehicle specifications
  - `fuel_type`: petrol, diesel, electric, hybrid
  - `transmission`: manual, automatic, CVT
  - `mileage`, `mileage_unit`: Usage tracking
  - `color`, `doors`, `seats`: Physical details
  - `engine_size`, `engine_power`: Engine specifications
  - `condition`: new, used, certified
  - `vin_number`, `registration_number`: Legal identification
  - `features`: JSON array of vehicle features
  - `documents`: Registration, insurance documents

### 8. Order Management

#### `orders`
- **Purpose**: Order processing and tracking
- **Key Fields**:
  - `order_number`: Unique order identifier
  - `status`: pending, confirmed, processing, shipped, delivered, cancelled, refunded
  - `subtotal`, `tax_amount`, `shipping_amount`, `discount_amount`: Financial breakdown
  - `total_amount`: Final order total
  - `payment_status`: pending, paid, failed, refunded
  - `payment_method`, `payment_gateway`: Payment processing
  - `billing_address`, `shipping_address`: Address information
  - `tracking_number`: Shipping tracking
  - `confirmed_at`, `shipped_at`, `delivered_at`: Status timestamps

#### `order_items`
- **Purpose**: Individual order line items
- **Key Fields**:
  - `product_name`, `product_sku`: Product identification
  - `quantity`: Ordered quantity
  - `unit_price`, `total_price`: Pricing
  - `product_attributes`: Size, color, etc.

### 9. Payment & Financial Management

#### `payments`
- **Purpose**: Payment processing tracking
- **Key Fields**:
  - `payment_id`: External payment identifier
  - `gateway`: paypal, stripe, razorpay, etc.
  - `method`: card, bank_transfer, wallet, cod
  - `amount`, `currency`: Payment details
  - `status`: pending, completed, failed, cancelled, refunded
  - `gateway_response`: JSON response from gateway
  - `transaction_id`: Gateway transaction ID
  - `failure_reason`: Error tracking

#### `commissions`
- **Purpose**: Revenue sharing management
- **Key Fields**:
  - `type`: order, auction, subscription
  - `order_amount`: Transaction amount
  - `commission_rate`: Percentage rate
  - `commission_amount`: Calculated commission
  - `status`: pending, paid, cancelled
  - `paid_at`: Payment timestamp

### 10. Internationalization

#### `languages`
- **Purpose**: Multi-language support
- **Key Fields**:
  - `name`: Language name (English, Spanish, etc.)
  - `code`: Language code (en, es, fr)
  - `native_name`: Native language name
  - `direction`: ltr, rtl
  - `is_active`, `is_default`: Language status
  - `flag_url`: Language flag image

#### `currencies`
- **Purpose**: Multi-currency support
- **Key Fields**:
  - `name`: Currency name (US Dollar, Euro)
  - `code`: Currency code (USD, EUR)
  - `symbol`: Currency symbol ($, €)
  - `symbol_position`: before, after
  - `decimal_places`: Precision
  - `exchange_rate`: Real-time rates
  - `last_updated`: Rate update timestamp

#### `translations`
- **Purpose**: Content translation management
- **Key Fields**:
  - `group`: frontend, backend, emails
  - `key`: Translation key
  - `value`: Translated text
  - `is_auto_translated`: Google Translate flag
  - `needs_review`: Manual review flag

## Key Features Supported

### Multi-Tenancy
- Complete data isolation per tenant
- Custom domain support
- Resource limits and monitoring
- White-label customization

### Advanced Auction System
- Multiple auction types (English, Reserve, Buy Now)
- Real-time bidding with WebSocket support
- Proxy bidding system
- Anti-sniping extensions
- Comprehensive bid tracking

### Property & Vehicle Management
- Specialized fields for real estate
- Vehicle specifications and documentation
- Agent commission systems
- Advanced search capabilities

### Financial Management
- Multiple payment gateways
- Commission-based revenue sharing
- Automated payout systems
- Multi-currency support

### Internationalization
- Unlimited language support
- Auto-translation integration
- RTL language support
- Currency conversion

### Security & Compliance
- Two-factor authentication
- Audit trails
- KYC compliance
- Data encryption support

## Database Relationships

### Primary Relationships
- `tenants` → `users` (One-to-Many)
- `tenants` → `vendors` (One-to-Many)
- `vendors` → `products` (One-to-Many)
- `products` → `auctions` (One-to-Many)
- `auctions` → `bids` (One-to-Many)
- `orders` → `order_items` (One-to-Many)
- `users` → `user_roles` (One-to-Many)

### Cross-References
- All tables include `tenant_id` for multi-tenancy
- Foreign key constraints ensure data integrity
- Cascade deletes maintain referential integrity

## Performance Considerations

### Indexing Strategy
- Primary keys on all tables
- Foreign key indexes for relationships
- Unique constraints on business-critical fields
- Composite indexes for common queries

### Scalability
- JSON fields for flexible data storage
- Efficient relationship design
- Optimized for horizontal scaling
- Support for database partitioning

## Migration Status
✅ All migrations created and tested successfully
✅ Database schema ready for development
✅ Multi-tenant architecture implemented
✅ All core features supported

This schema provides a solid foundation for the XpertBid platform, supporting all specified features while maintaining scalability and performance.
