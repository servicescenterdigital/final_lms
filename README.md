# Modern LMS Architecture Design

## Overview
This is a production-ready Learning Management System (LMS) designed for scalability, modularity, and ease of use. It supports role-based access control (RBAC), automated grading, payment integration, and AI-assisted course generation.

## Core Features
- **Multi-role Support**: Student, Instructor, Organization Admin, and Platform Admin.
- **AI Course Engine**: Instructors can generate courses using an AI-friendly JSON schema. The system validates and imports this schema automatically.
- **Automated Learning & Assessment**: Students learn through an AJAX-powered interface. Quizzes and final exams are auto-graded.
- **Certification System**: Automatic certificate issuance upon course completion with a public verification page.
- **Payment Integration**: Secure payment processing via MTN Momo and Airtel Money (SchoolDream+ API).
- **Communication Engine**: Integrated SMS (Bulk SMS API) and Email notifications.
- **1-to-1 Tutoring**: Optional module for booking separate tutoring sessions.

## Tech Stack
- **Backend**: PHP (Modular functions & PDO).
- **Frontend**: Bootstrap 5 + Vanilla JS (AJAX/Fetch API).
- **Database**: MySQL.
- **Routing**: Clean URLs via `.htaccess` and a central PHP router.

## Directory Structure
- `/api`: AJAX endpoints for dynamic interactions.
- `/includes`: Core logic, database configuration, and global functions.
- `/public`: Entry point and assets (CSS/JS).
- `/templates`: HTML templates for each user role and public pages.
- `/uploads`: Storage for course resources and generated certificates.

## AI Course JSON Schema
The system expects a specific JSON structure for course imports:
```json
{
  "title": "Course Title",
  "description": "...",
  "price": 5000,
  "modules": [
    {
      "title": "Module Name",
      "lessons": [
        { "title": "Lesson 1", "content": "Markdown/HTML", "type": "text" }
      ],
      "quizzes": [
        {
          "title": "Quiz 1",
          "pass_score": 70,
          "questions": [
            {
              "question": "What is...?",
              "type": "multiple_choice",
              "options": [
                { "text": "Answer A", "is_correct": true },
                { "text": "Answer B", "is_correct": false }
              ]
            }
          ]
        }
      ]
    }
  ]
}
```

## Security Considerations
- **Password Hashing**: Using `password_hash()` with BCRYPT.
- **SQL Injection**: All database queries use PDO prepared statements.
- **RBAC**: Strict role checks on every portal and API endpoint.
- **Input Sanitization**: All user inputs are sanitized before processing.
- **CSRF & Rate Limiting**: Recommended for production deployment.

## Integration Flow
1. **Payment**:
   - `api/payment-init.php` initiates the request to SchoolDream+.
   - `api/payment-callback.php` receives the status update and enrolls the student.
2. **SMS**:
   - `send_sms()` function in `includes/functions.php` handles communication with the Bulk SMS gateway.

## Deployment
1. Import `schema.sql` to your MySQL database.
2. Update `includes/config.php` with your database credentials and API keys.
3. Ensure Apache `mod_rewrite` is enabled for clean URLs.
