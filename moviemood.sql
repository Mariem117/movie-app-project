CREATE DATABASE IF NOT EXISTS moviemood;
USE moviemood;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    duration VARCHAR(50),
    rating FLOAT,
    description TEXT,
    director VARCHAR(255),
    actors TEXT,
    trailer_url VARCHAR(255),
    tags VARCHAR(255),
    poster VARCHAR(255)
);

CREATE TABLE showtimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    showtime DATETIME,
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    movie_id INT,
    booking_date DATE,
    showtime TIME,
    seats VARCHAR(255),
    total_price DECIMAL(10, 2),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    movie_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

INSERT INTO users (first_name, last_name, email, password, role)
VALUES ('Admin', 'User', 'admin@gmail.com', '$2y$10$hXPGZvOY9aKnJHy3OIBi9ePZL4FJV9wGzvGDsP.A/lbMy44OHiu5.', 'admin');

INSERT INTO movies (title, year, duration, rating, description, director, mood, tags, poster, trailer_url, actors)
VALUES (
    'Black Bag', 2025, '1H 47Mins', 4.0,
    'A gripping spy thriller following a covert operative entangled in a high-stakes espionage mission.',
    'Steven Soderbergh', 'Thrilling', 'Spy,Thriller',
    'https://static1.srcdn.com/wordpress/wp-content/uploads/2025/01/image003.jpg',
    'https://www.youtube.com/embed/Du0Xp8WX_7I',
    'Cate Blanchett,Michael Fassbender,Regé-Jean Page,Marisa Abela,Naomie Harris'
);