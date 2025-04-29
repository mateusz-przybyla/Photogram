# Photogram

**[App in progress]**  
Photogram is an Instagram-style demo application built with **Symfony**. It allows users to share pictures, follow each other, like and comment on posts, and manage their profiles.

## Table of contents

- [Overview](#overview)
  - [About](#about)
  - [How it works](#how-it-works)
  - [Key features](#key-features)
- [Architecture](#architecture)
  - [Authentication & Access](#authentication--access)
  - [Main views](#main-views)
  - [Database design](#database-design)
  - [Screenshots](#screenshots)
- [My process](#my-process)
  - [Built with](#built-with)
  - [Useful resources](#useful-resources)

## Overview

### About

Photogram is a social web application that replicates core features of Instagram. It focuses on sharing photo posts, interacting with other users, and managing a user profile. The app is built with a **Symfony PHP backend**, styled with **Tailwind CSS**, and designed to be responsive and scalable.

---

### How it works

1. Create an account and confirm registration via email.
2. Sign in to access the application.
3. Upload photo posts with optional description and location.
4. Follow or unfollow other users.
5. Like and comment on posts.
6. Browse your personalized feed or explore all public posts.
7. Manage your profile and change your profile picture and personal data.

---

### Key features

- 🔐 **Email verification** after registration.
- 🧾 **CRUD for photo posts** (create, edit, delete).
- 👥 **Follow system** (followers/following).
- ❤️ **Like and comment** on photos.
- 🏠 **Main feed** – shows posts from followed users.
- 🌍 **Explore feed** – shows all public posts.
- 🧑‍💼 **Profile page** – with editable info and image.
- 📱 Fully **responsive design** with Tailwind.

---

## Architecture

### Authentication & Access

- Only logged-in users can access app resources.
- After login, the user is redirected to their main feed.
- Account verification via email is required before logging in.

---

### Main views

- **Main feed** – Posts from followed users (homepage after login).
- **Explore** – All public posts from every user.
- **User profiles** – See posts, followers, and following list.
- **Post view** – See post details and comments.
- **Profile settings** – Edit profile info, upload profile picture.

---

### Database design

_[To be added]_

---

### Screenshots

**Welcome page**  
![](/readme/welcome.png)

**Sign up page**  
![](/readme/sign-up.png)

**Email verification notice**  
![](/readme/email-verification.png)

**Main feed – posts from followed users**  
![](/readme/main-feed.png)

**Explore feed – all posts**  
![](/readme/explore.png)

**Post details – with likes and comments**  
![](/readme/post-details.png)

**User profile – posts, followers, following**  
![](/readme/profile.png)

**Profile settings – update data and profile image**  
![](/readme/profile-settings.png)

**Mobile view**  
![](/readme/mobile.png)

## My process

### Built with

**Frontend:**

- Tailwind CSS
- HTML, CSS
- JavaScript
- Stimulus (JS controller system)

**Backend:**

- PHP
- Symfony Framework
- Doctrine ORM
- Mailer component + MailCatcher
- MySQL
- Docker for containerized environment

---

### Useful resources

- https://symfony.com/
- https://symfonycasts.com/
- https://tailwindcss.com/
- https://www.docker.com/
