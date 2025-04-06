-- Insert test users
INSERT INTO users (name, email, password, role) VALUES
('John Freelancer', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'freelancer'),
('Sarah Employer', 'sarah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer'),
('Mike Developer', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'freelancer'),
('Lisa Hiring', 'lisa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer');

-- Insert test jobs
INSERT INTO jobs (title, description, employer_id, budget, category) VALUES
('Website Development', 'Need a responsive website for my business', 2, 1500.00, 'web-development'),
('Logo Design', 'Looking for a modern logo for my startup', 4, 500.00, 'graphic-design'),
('Content Writer Needed', 'Regular blog posts about technology trends', 2, 800.00, 'content-writing'),
('Social Media Manager', 'Manage our Instagram and Facebook accounts', 4, 1200.00, 'marketing');

-- Insert test applications
INSERT INTO applications (job_id, freelancer_id, proposal, status) VALUES
(1, 1, 'I have 5 years of experience building responsive websites with React and Node.js', 'pending'),
(1, 3, 'Full-stack developer specializing in modern web applications', 'pending'),
(2, 1, 'Graphic designer with portfolio of modern logos', 'accepted'),
(3, 3, 'Tech writer with published articles on Medium', 'rejected');

-- Insert test messages
INSERT INTO messages (sender_id, receiver_id, content) VALUES
(2, 1, 'Hi John, I saw your application for the website project. Can we schedule a call?'),
(1, 2, 'Sure Sarah, I''m available tomorrow after 2pm. What works for you?'),
(4, 3, 'Thanks for applying Mike, but we''ve decided to go with another candidate'),
(3, 4, 'Thanks for letting me know Lisa. Please keep me in mind for future projects');