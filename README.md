# Study Buddy 📚✨

![Study Buddy](https://img.shields.io/badge/Study-Buddy-blueviolet?style=for-the-badge&logo=bookstack)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Live Chat](https://img.shields.io/badge/Live_Chat-25D366?style=for-the-badge&logo=whatsapp&logoColor=white)

**Your all-in-one intelligent study companion designed to make learning more effective, collaborative, and enjoyable!**

Study Buddy transforms traditional studying into an interactive experience with AI-powered flashcards, collaborative study groups, real-time chat, and smart scheduling - all in one beautiful, intuitive platform.

## 🚀 Features

### 🎯 Core Functionality
- **🔐 User Authentication** - Secure registration and login system
- **📝 Smart Flashcards** - AI-powered flashcard generation and spaced repetition
- **❓ Interactive Quizzes** - Auto-generated quizzes with performance tracking
- **👥 Study Groups** - Create, join, and manage collaborative study sessions
- **💬 Real-time Chat** - Instant messaging with other students
- **📅 Smart Scheduling** - Intelligent study planning and calendar integration
- **📚 Resource Sharing** - Easy file and note sharing within groups

### 🎨 Premium Features
- **🤖 AI-Powered Learning** - Smart content suggestions based on your progress
- **📊 Progress Analytics** - Detailed performance insights and learning trends
- **🔔 Smart Notifications** - Reminders for upcoming study sessions and deadlines
- **📱 Mobile Responsive** - Study anywhere on any device
- **🎯 Gamification** - Earn badges and rewards for learning milestones

## 🛠️ Technology Stack

**Backend:**
- Laravel 10.x
- PHP 8.1+
- MySQL 8.0
- Redis (for caching and queues)

**Frontend:**
- Blade Templates
- Tailwind CSS
- Alpine.js
- Livewire (for real-time features)

**Real-time Features:**
- Laravel Echo
- Pusher/Socket.io
- Laravel Notifications

**AI Integration:**
- OpenAI API (for flashcard generation)
- Custom recommendation algorithms

## 📦 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL 8.0+
- Redis (optional but recommended)

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/study-buddy.git
   cd study-buddy
   ```
2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```
4. **Environment setup**
   ```bash
   cp .env.example .env
    php artisan key:generate
   ```
 5. **Condigure enviroment**
    Edit .env file with your database, mail, and other service configurations:

    ```env
    DB_DATABASE=study_buddy
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_db_password

    OPENAI_API_KEY=your_openai_key
    PUSHER_APP_ID=your_pusher_id
   PUSHER_APP_KEY=your_pusher_key
    PUSHER_APP_SECRET=your_pusher_secret
   ```

 6. **Database setup**
   ```bash
      php artisan migrate --seed
   ```
7. **Build assets**
   ```bash
   npm run build
   ```

 8. **Start develpment server**
   ```bash
   php artisan serve
   ```

