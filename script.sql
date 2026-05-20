peersync-- =====================================================
-- DATABASE
-- =====================================================

CREATE DATABASE peersync;

USE peersync;

-- =====================================================
-- TABLE : roles
-- =====================================================

CREATE TABLE roles (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       label VARCHAR(50) NOT NULL UNIQUE
);

-- =====================================================
-- TABLE : users
-- =====================================================

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,

                       name VARCHAR(100) NOT NULL,

                       email VARCHAR(150) NOT NULL UNIQUE,

                       password VARCHAR(255) NOT NULL,

                       points INT DEFAULT 0,

                       availability BOOLEAN DEFAULT TRUE,

                       role_id INT NOT NULL,

                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                       FOREIGN KEY (role_id)
                           REFERENCES roles(id)
                           ON DELETE CASCADE
);

-- =====================================================
-- TABLE : skills
-- =====================================================

CREATE TABLE skills (
                        id INT AUTO_INCREMENT PRIMARY KEY,

                        name VARCHAR(100) NOT NULL UNIQUE,

                        type ENUM('mastered', 'learning') NOT NULL
);

-- =====================================================
-- TABLE : user_skill
-- =====================================================

CREATE TABLE user_skill (
                            user_id INT NOT NULL,

                            skill_id INT NOT NULL,

                            type ENUM('mastered', 'learning') NOT NULL,

                            PRIMARY KEY (user_id, skill_id, type),

                            FOREIGN KEY (user_id)
                                REFERENCES users(id)
                                ON DELETE CASCADE,

                            FOREIGN KEY (skill_id)
                                REFERENCES skills(id)
                                ON DELETE CASCADE
);

-- =====================================================
-- TABLE : help_request
-- =====================================================

CREATE TABLE help_request (
                              id INT AUTO_INCREMENT PRIMARY KEY,

                              title VARCHAR(255) NOT NULL,

                              description TEXT NOT NULL,

                              technology VARCHAR(100) NOT NULL,

                              status ENUM(
        'pending',
        'assigned',
        'resolved'
    ) DEFAULT 'pending',

                              student_id INT NOT NULL,

                              tutor_id INT NULL,

                              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                              resolved_at TIMESTAMP NULL,

                              FOREIGN KEY (student_id)
                                  REFERENCES users(id)
                                  ON DELETE CASCADE,

                              FOREIGN KEY (tutor_id)
                                  REFERENCES users(id)
                                  ON DELETE SET NULL
);

-- =====================================================
-- TABLE : review
-- =====================================================

CREATE TABLE review (
                        id INT AUTO_INCREMENT PRIMARY KEY,

                        rating INT NOT NULL,

                        comment TEXT,

                        help_request_id INT NOT NULL UNIQUE,

                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                        FOREIGN KEY (help_request_id)
                            REFERENCES help_request(id)
                            ON DELETE CASCADE,

                        CHECK (rating >= 1 AND rating <= 5)
);

-- =====================================================
-- TABLE : badge
-- =====================================================

CREATE TABLE badge (
                       id INT AUTO_INCREMENT PRIMARY KEY,

                       name VARCHAR(100) NOT NULL UNIQUE,

                       points INT NOT NULL
);

-- =====================================================
-- TABLE : user_badge
-- =====================================================

CREATE TABLE user_badge (
                            user_id INT NOT NULL,

                            badge_id INT NOT NULL,

                            earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                            PRIMARY KEY (user_id, badge_id),

                            FOREIGN KEY (user_id)
                                REFERENCES users(id)
                                ON DELETE CASCADE,

                            FOREIGN KEY (badge_id)
                                REFERENCES badge(id)
                                ON DELETE CASCADE
);

-- =====================================================
-- DEFAULT ROLES
-- =====================================================

INSERT INTO roles (label)
VALUES
    ('admin'),
    ('user');

-- =====================================================
-- DEFAULT SKILLS
-- =====================================================

INSERT INTO skills (name, type)
VALUES
    ('PHP', 'mastered'),
    ('OOP', 'learning'),
    ('JavaScript', 'mastered'),
    ('SQL', 'learning'),
    ('Laravel', 'learning'),
    ('React', 'learning');

-- =====================================================
-- DEFAULT BADGES
-- =====================================================

INSERT INTO badge (name, points)
VALUES
    ('Helper', 50),
    ('Mentor', 100),
    ('Top Contributor', 300),
    ('Expert', 500);