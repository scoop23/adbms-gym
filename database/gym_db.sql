CREATE TABLE users (
  user_id VARCHAR(50) PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('client', 'instructor', 'admin') NOT NULL
);
CREATE TABLE clients (
  user_id VARCHAR(50) PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
CREATE TABLE admins (
  user_id VARCHAR(50) PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
CREATE TABLE instructors (
  instructor_id VARCHAR(50) PRIMARY KEY,
  bio TEXT,
  photo VARCHAR(255),
  rating INT CHECK (
    rating BETWEEN 1 AND 5
  ),
  certifications TEXT,
  specialties TEXT,
  FOREIGN KEY (instructor_id) REFERENCES users(user_id) ON DELETE CASCADE,
);
CREATE TABLE classes (
  class_id VARCHAR(50) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  difficulty_level VARCHAR(50),
  duration INT,
  capacity INT
);
CREATE TABLE schedules (
  schedule_id VARCHAR(50) PRIMARY KEY,
  class_id VARCHAR(50),
  instructor_id VARCHAR(50),
  start_time TIMESTAMP,
  end_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  location VARCHAR(100),
  FOREIGN KEY (class_id) REFERENCES classes(class_id),
  FOREIGN KEY (instructor_id) REFERENCES instructors(instructor_id)
);
CREATE TABLE bookings (
  booking_id VARCHAR(50) PRIMARY KEY,
  schedule_id VARCHAR(50),
  user_id VARCHAR(50),
  status VARCHAR(50),
  booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (schedule_id) REFERENCES schedules(schedule_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE reviews (
  review_id VARCHAR(50) PRIMARY KEY,
  instructor_id VARCHAR(50) NOT NULL,
  user_id VARCHAR(50) NOT NULL,
  rating INT CHECK (
    rating BETWEEN 1 AND 5
  ),
  comment TEXT,
  review_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (instructor_id) REFERENCES instructors(instructor_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
-- Insert dummy data into the 'users' table
INSERT INTO users (
    user_id,
    first_name,
    last_name,
    email,
    username,
    password,
    phone,
    role
  )
VALUES (
    'user_001',
    'John',
    'Doe',
    'john.doe@example.com',
    'johndoe',
    'password123',
    '123-456-7890',
    'client'
  ),
  (
    'user_002',
    'Jane',
    'Smith',
    'jane.smith@example.com',
    'janesmith',
    'password123',
    '234-567-8901',
    'client'
  ),
  (
    'user_003',
    'Mike',
    'Johnson',
    'mike.johnson@example.com',
    'mikejohnson',
    'password123',
    '345-678-9012',
    'admin'
  ),
  (
    'user_004',
    'Emily',
    'Davis',
    'emily.davis@example.com',
    'emilydavis',
    'password123',
    '456-789-0123',
    'instructor'
  ),
  (
    'user_005',
    'David',
    'Lee',
    'david.lee@example.com',
    'davidlee',
    'password123',
    '567-890-1234',
    'instructor'
  );
-- Insert dummy data into the 'clients' table
INSERT INTO clients (user_id)
VALUES ('user_001'),
  ('user_002');
-- Insert dummy data into the 'admins' table
INSERT INTO admins (user_id)
VALUES ('user_003');
-- Insert dummy data into the 'instructors' table
INSERT INTO instructors (
    instructor_id,
    bio,
    photo,
    rating,
    certifications,
    specialties
  )
VALUES (
    'user_004',
    'Certified yoga instructor with 10 years of experience.',
    'uploads/emily_davis.jpg',
    4,
    'Certified Yoga Trainer',
    'Vinyasa, Hatha, Restorative'
  ),
  (
    'user_005',
    'Fitness coach with a specialization in strength training and conditioning.',
    'uploads/david_lee.jpg',
    5,
    'Certified Personal Trainer',
    'Strength Training, Conditioning'
  );
-- Insert dummy data into the 'classes' table
INSERT INTO classes (
    class_id,
    name,
    description,
    difficulty_level,
    duration,
    capacity
  )
VALUES (
    'class_001',
    'Yoga Basics',
    'A beginner yoga class for flexibility and strength.',
    'Easy',
    60,
    20
  ),
  (
    'class_002',
    'Advanced Yoga',
    'Advanced poses and deep stretching for experienced practitioners.',
    'Hard',
    90,
    15
  ),
  (
    'class_003',
    'Strength Training',
    'A class focusing on building muscle and strength.',
    'Medium',
    60,
    25
  ),
  (
    'class_004',
    'Cardio Bootcamp',
    'A high-intensity class designed for fat loss and endurance.',
    'Medium',
    45,
    30
  );
-- Insert dummy data into the 'schedules' table
INSERT INTO schedules (
    schedule_id,
    class_id,
    instructor_id,
    start_time,
    end_time,
    location
  )
VALUES (
    'schedule_001',
    'class_001',
    'user_004',
    '2025-05-15 09:00:00',
    '2025-05-15 10:00:00',
    'Studio 1'
  ),
  (
    'schedule_002',
    'class_002',
    'user_004',
    '2025-05-15 10:30:00',
    '2025-05-15 12:00:00',
    'Studio 1'
  ),
  (
    'schedule_003',
    'class_003',
    'user_005',
    '2025-05-16 14:00:00',
    '2025-05-16 15:00:00',
    'Gym 2'
  ),
  (
    'schedule_004',
    'class_004',
    'user_005',
    '2025-05-16 16:00:00',
    '2025-05-16 16:45:00',
    'Gym 1'
  );
-- Insert dummy data into the 'bookings' table
INSERT INTO bookings (
    booking_id,
    schedule_id,
    user_id,
    status,
    booking_time
  )
VALUES (
    'booking_001',
    'schedule_001',
    'user_001',
    'confirmed',
    '2025-05-14 12:00:00'
  ),
  (
    'booking_002',
    'schedule_002',
    'user_002',
    'pending',
    '2025-05-14 13:00:00'
  ),
  (
    'booking_003',
    'schedule_003',
    'user_001',
    'confirmed',
    '2025-05-14 14:00:00'
  ),
  (
    'booking_004',
    'schedule_004',
    'user_002',
    'cancelled',
    '2025-05-14 15:00:00'
  );
-- Insert dummy data into the 'reviews' table
INSERT INTO reviews (
    review_id,
    instructor_id,
    user_id,
    rating,
    comment,
    review_time
  )
VALUES (
    'review_001',
    'user_004',
    'user_001',
    5,
    'Great instructor! Really helped me with my flexibility.',
    '2025-05-14 11:00:00'
  ),
  (
    'review_002',
    'user_005',
    'user_002',
    4,
    'Tough but rewarding class. Loved the workout!',
    '2025-05-14 12:30:00'
  ),
  (
    'review_003',
    'user_004',
    'user_001',
    4,
    'Good class but a bit challenging for beginners.',
    '2025-05-14 14:30:00'
  );