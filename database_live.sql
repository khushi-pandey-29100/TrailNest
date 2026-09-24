-- Use this file on live hosting: create the database in your hosting panel first, then import this file into it.

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS trek_images;
DROP TABLE IF EXISTS treks;

CREATE TABLE treks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  region VARCHAR(100) NOT NULL,
  tagline VARCHAR(150) NOT NULL,
  short_description VARCHAR(255) NOT NULL,
  full_description TEXT NOT NULL,
  itinerary TEXT NOT NULL,
  difficulty ENUM('Easy','Moderate','Difficult') NOT NULL DEFAULT 'Moderate',
  max_altitude_m INT NOT NULL,
  duration_days INT NOT NULL,
  price_from INT NOT NULL,
  best_season VARCHAR(100) NOT NULL,
  cover_image VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE trek_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  trek_id INT NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(150) DEFAULT '',
  FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  trek_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  trek_id INT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  participants INT NOT NULL DEFAULT 1,
  start_date DATE NULL,
  pickup_point VARCHAR(150) DEFAULT '',
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (trek_id) REFERENCES treks(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO treks (id, name, region, tagline, short_description, full_description, itinerary, difficulty, max_altitude_m, duration_days, price_from, best_season, cover_image) VALUES
(1,'Kedarkantha Trail','Uttarakhand, India','A snow trek built for first-timers','Pine forests, snow meadows and a summit view of over 20 peaks.',
 'Kedarkantha is one of the most reliable winter treks in India: guaranteed snow, a short approach and a summit that rewards you with a wall of Himalayan peaks. The trail moves through dense pine and oak forest before opening into wide snow meadows, making it a good first high-altitude trek.',
 'Day 1: Drive to Sankri, briefing and gear check|Day 2: Sankri to Juda Ka Talab, forest trail|Day 3: Juda Ka Talab to Kedarkantha Base|Day 4: Summit day and descent to Hargaon|Day 5: Hargaon to Sankri, drive back',
 'Moderate',3810,6,8500,'December to April','assets/images/kedarkantha-1.jpg'),
(2,'Hampta Pass','Himachal Pradesh, India','A single trail, two completely different sides','Green Kullu valley on one side, the Lahaul desert on the other.',
 'Hampta Pass is famous for the contrast at the top: lush green valley behind you, dry brown Lahaul mountains ahead. The trek crosses a high pass with river crossings, steep moraine and a night camped beside the Chandratal lake on clear-weather departures.',
 'Day 1: Drive to Jobra, trek to Jwara|Day 2: Jwara to Balu Ka Ghera|Day 3: Balu Ka Ghera to Hampta Pass to Shea Goru|Day 4: Shea Goru to Chatru, optional Chandratal visit|Day 5: Drive back to Manali',
 'Moderate',4270,5,9800,'June to September','assets/images/hampta-1.jpg'),
(3,'Everest Base Camp','Khumbu, Nepal','Walk to the foot of the world''s highest mountain','Sherpa villages, suspension bridges and the Khumbu Icefall up close.',
 'The classic. This route follows the Dudh Kosi valley through Sherpa villages, monasteries and rhododendron forest before the trail thins out above the tree line. Two rest days for altitude acclimatisation are built in before reaching Base Camp itself.',
 'Day 1: Fly to Lukla, trek to Phakding|Day 2: Phakding to Namche Bazaar|Day 3: Acclimatisation day in Namche|Day 4: Namche to Tengboche|Day 5: Tengboche to Dingboche|Day 6: Acclimatisation day in Dingboche|Day 7: Dingboche to Lobuche|Day 8: Lobuche to Gorak Shep, visit Base Camp|Day 9: Climb Kala Patthar, descend to Pheriche|Day 10: Pheriche to Namche|Day 11: Namche to Lukla, fly to Kathmandu',
 'Difficult',5364,12,145000,'March to May, September to November','assets/images/everest-1.jpg'),
(4,'Valley of Flowers','Uttarakhand, India','A national park that turns into a flower carpet','Hundreds of alpine flower species inside a UNESCO World Heritage site.',
 'A gentle trek by Himalayan standards, this route follows the Pushpawati river into a high valley that blooms with alpine flowers through the monsoon months. A side trip to the Sikh pilgrimage site of Hemkund Sahib is usually included.',
 'Day 1: Drive to Govindghat, trek to Ghangaria|Day 2: Ghangaria to Valley of Flowers and back|Day 3: Ghangaria to Hemkund Sahib and back|Day 4: Ghangaria to Govindghat, drive back',
 'Easy',3658,4,7200,'July to September','assets/images/valley-1.jpg'),
(5,'Markha Valley', 'Ladakh, India','High desert, gompas and the Zanskar range','A trans-Himalayan trek through Ladakh''s driest, starkest landscape.',
 'Markha Valley crosses the Hemis National Park through villages that still run on barley and yak herding. Expect huge open skies, mani walls, an ancient gompa at Markha village, and one high pass crossing near the end of the route.',
 'Day 1: Drive to Chilling, trek to Skiu|Day 2: Skiu to Markha|Day 3: Markha to Hankar|Day 4: Hankar to Nimaling|Day 5: Nimaling, cross Kongmaru La, descend to Chokdo|Day 6: Chokdo to Shang Sumdo, drive to Leh',
 'Difficult',5150,6,16500,'June to September','assets/images/markha-1.jpg'),
(6,'Triund','Himachal Pradesh, India','A weekend trek with a Dhauladhar view','A short ridge walk out of McLeod Ganj, doable in two days.',
 'Triund is the trek people do when they only have a weekend. A steady climb through oak and rhododendron forest opens onto a wide grassy ridge with the snowbound Dhauladhar range straight ahead. Popular for a reason.',
 'Day 1: McLeod Ganj to Triund, camp overnight|Day 2: Sunrise at Triund, descend to McLeod Ganj',
 'Easy',2850,2,2800,'March to June, September to December','assets/images/triund-1.jpg');

INSERT INTO trek_images (trek_id, image_path, caption) VALUES
(1,'assets/images/kedarkantha-1.jpg','Snow meadow camp'),(1,'assets/images/kedarkantha-2.jpg','Summit ridge'),(1,'assets/images/kedarkantha-3.jpg','Pine forest trail'),
(2,'assets/images/hampta-1.jpg','Balu Ka Ghera'),(2,'assets/images/hampta-2.jpg','Pass crossing'),(2,'assets/images/hampta-3.jpg','Chandratal lake'),
(3,'assets/images/everest-1.jpg','Namche Bazaar'),(3,'assets/images/everest-2.jpg','Khumbu Icefall'),(3,'assets/images/everest-3.jpg','Kala Patthar sunrise'),
(4,'assets/images/valley-1.jpg','Flower carpet'),(4,'assets/images/valley-2.jpg','Pushpawati river'),(4,'assets/images/valley-3.jpg','Hemkund Sahib'),
(5,'assets/images/markha-1.jpg','Markha village gompa'),(5,'assets/images/markha-2.jpg','Kongmaru La'),(5,'assets/images/markha-3.jpg','Nimaling plains'),
(6,'assets/images/triund-1.jpg','Dhauladhar view'),(6,'assets/images/triund-2.jpg','Ridge camp'),(6,'assets/images/triund-3.jpg','Sunrise light');

INSERT INTO reviews (trek_id, name, rating, comment, created_at) VALUES
(1,'Ananya R.',5,'Perfect first snow trek. Guides were great about pacing the group.','2026-02-10 10:00:00'),
(1,'Rohit S.',4,'Cold nights but worth it for the summit view.','2026-01-22 18:30:00'),
(2,'Kabir M.',5,'The pass crossing day is unreal. Chandratal side trip is a must.','2025-08-05 09:15:00'),
(3,'Priya V.',5,'Hard but completely worth every step. Namche acclimatisation days matter, do not skip.','2025-11-01 14:00:00'),
(3,'Daniel K.',4,'Tea houses were more comfortable than I expected. Bring warmer gloves than you think you need.','2025-10-18 08:45:00'),
(4,'Meera J.',5,'Went in August, flowers were everywhere. Easy trail, big payoff.','2025-08-20 16:00:00'),
(6,'Farhan A.',4,'Great weekend trip from Dharamshala, sunrise at Triund was worth the early start.','2025-10-02 07:30:00');
