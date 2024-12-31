# Panchayat Data Management App

## Overview  
The Panchayat Data Management Application is a web-based platform designed to streamline and digitize the management of panchayat-level data. It enables panchayats to efficiently handle tasks like recordkeeping, resource management, and tracking development activities, enhancing transparency and accountability.  

---

## Features  
- **Citizen Records Management**: Store and manage population and demographic data.  
- **Resource Allocation**: Track and allocate panchayat resources effectively.  
- **Development Tracking**: Monitor ongoing and completed development projects.  
- **Grievance Redressal**: Manage and resolve citizen complaints transparently.  
- **Reports and Analytics**: Generate insightful reports for better decision-making.  
- **Role-based Access**: Ensure secure access for administrators, officials, and citizens.  

---

## Installation  

### Prerequisites  
- PHP (>= 8.1)  
- Laravel Framework (>= 11.x)  
- MySQL database  
- HTML CSS and JAVASCRIPT
- Composer  
- Web server: Nginx or Apache  

### Steps  
1. **Clone the repository**:  
   ```bash  
   git clone https://github.com/mauxi-panchayat/panchayat-data-mgmt-app.git 
   cd panchayat-data-mgmt-app
   
2. **Install dependencies**:

```bash 
composer update  
php artisan key:generate

3. **Set up environment variables**:
- Copy .env.example to .env and update database and other configurations.
```bash
cp .env.example .env

4. **Start the development server**:

```bash
php artisan serve  
