# Calendar of Activity
Calendar of Activity is a Laravel-based web application designed to streamline the management and tracking of school activities. The system serves as a centralized platform where administrators (PIOs) can schedule and organize events, while users (students) can view and monitor their participation. It features a robust role-based access control system, ensuring that only authorized personnel can modify activity data, while providing an intuitive and user-friendly interface for all users.

# List of Implemented Features
1. Authentication & Role Management
Secure User Authentication: Integrated login, registration, and password management using Laravel Breeze.
Multi-Role Architecture: Defined roles for PIO (Public Information Officers) and Students, each with specific permissions.
Role-Based Access Control (RBAC): Custom middleware to protect administrative routes and ensure data integrity.
2. Activity Management (CRUD)
Administrative Control: PIOs have full authority to Create, Read, Update, and Delete activities.
Activity Details: Support for titles, descriptions, specific activity dates, and ownership tracking.
Categorization: Ability to group activities into categories for easier navigation and organization.
3. Student Interaction & Tracking
Activity Participation: Students can view scheduled activities and mark them as completed.
Progress Tracking: Many-to-many relationships between users and activities to track status (e.g., RSVP/Completion) via pivot tables.
Unified Dashboard: A central dashboard that redirects users to the activity index upon login.
4. Visualization & UI
Interactive Calendar: A dedicated visual calendar view that displays activities by date, providing a clear timeline of events.
Responsive Design: Modern and responsive user interface built with Blade templates and styled for optimal user experience.
Profile Management: Users can manage their personal profiles and account settings.
5. Technical Foundations
Eloquent Relationships: Advanced use of one-to-many (Activity-Category) and many-to-many (Activity-User) relationships.
Database Migrations: Structured database schema with automated migrations and seeders.
Middleware Protection: Enhanced security through custom role-checking middleware.
