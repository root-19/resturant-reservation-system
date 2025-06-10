-- Insert 12 dummy customers with accepted orders for 2-person table
INSERT INTO reservations (name, email, reservation_date, reservation_time, table_size, created_at) VALUES
('John Smith', 'john.smith@email.com', CURDATE(), '12:00:00', 2, NOW()),
('Alice Johnson', 'alice.j@email.com', CURDATE(), '12:15:00', 2, NOW()),
('Bob Wilson', 'bob.w@email.com', CURDATE(), '12:30:00', 2, NOW()),
('Carol Brown', 'carol.b@email.com', CURDATE(), '12:45:00', 2, NOW()),
('David Lee', 'david.l@email.com', CURDATE(), '13:00:00', 2, NOW()),
('Emma Davis', 'emma.d@email.com', CURDATE(), '13:15:00', 2, NOW()),
('Frank Miller', 'frank.m@email.com', CURDATE(), '13:30:00', 2, NOW()),
('Grace Taylor', 'grace.t@email.com', CURDATE(), '13:45:00', 2, NOW()),
('Henry Clark', 'henry.c@email.com', CURDATE(), '14:00:00', 2, NOW()),
('Ivy White', 'ivy.w@email.com', CURDATE(), '14:15:00', 2, NOW()),
('Jack Moore', 'jack.m@email.com', CURDATE(), '14:30:00', 2, NOW()),
('Kelly Young', 'kelly.y@email.com', CURDATE(), '14:45:00', 2, NOW()); 