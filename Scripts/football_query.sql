CREATE DATABASE userData;
USE userData;

CREATE TABLE user (  
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(65) NOT NULL,
  username VARCHAR(65) NOT NULL,
  password VARCHAR(65) NOT NULL,
  primary key (id)
);

CREATE TABLE players (  
  playerID INT not null,
  espnName VARCHAR(65),
  weight INT,
  height VARCHAR(65),
  age INT,
  jerseyNum INT,
  team VARCHAR(65),
  teamID INT,
  lastGamePlayed VARCHAR(65),
  pos VARCHAR(4),
  espnHeadshot VARCHAR(255),
  PRIMARY KEY (playerID)
);

CREATE TABLE teams (  
  teamId INT not null AUTO_INCREMENT,
  teamName VARCHAR(65) NOT NULL,
  wins INT NOT NULL,
  loses INT not null,
  logo VARCHAR(200),
  primary key (teamID)
);

CREATE TABLE followedPlayers (  
  id INT,
  playerId INT not null,
  playerTeam VARCHAR(65) NOT NULL,
  playerName VARCHAR(65) NOT NULL,
  playerNumber INT not null,
  playerPosition Varchar(4) not Null,
  FOREIGN KEY (id) REFERENCES user(id)
);

CREATE TABLE games (
  gameID VARCHAR(255) PRIMARY KEY,
  gameDate DATETIME,
  espnID VARCHAR(255),
  teamIDHome VARCHAR(255),
  gameStatus VARCHAR(50),
  gameWeek VARCHAR(255),
  teamIDAway VARCHAR(255),
  gameTime VARCHAR(255),
  season VARCHAR(50),
  teamStats JSON,
  scoringPlays JSON,
  homeResult VARCHAR(50),
  awayResult VARCHAR(50),
  gameLocation VARCHAR(255),
  arena VARCHAR(255),
  homePts INT,
  awayPts INT,
  currentPeriod VARCHAR(50),
  homeName VARCHAR(50),
  awayName VARCHAR(50)
);

CREATE TABLE liveGameData (  
  gameID INT NOT NULL,
  season INT NOT NULL,
  team1 VARCHAR(65) NOT NULL,
  team2 VARCHAR(65) NOT NULL,
  player VARCHAR(65) NOT NULL,
  actions VARCHAR(65) NOT NULL,
  time VARCHAR(65) NOT NULL,
  points INT NOT NULL,
  playerTeam VARCHAR(65) NOT NULL
);

ALTER TABLE teams 
DROP COLUMN logo;

insert into user  (email, username, password)
values('sample123@gmail.com', 'sampleUser', '123456');

insert into user  (email, username, password)
values('s12345@gmail.com', 'user2Ex', '123456');
 
insert into teams (teamName, wins,loses)
values ('Arizona Cardinals', 3,4),
	('Atlanta Falcons',4,3),
	('Baltimore Ravens',4,3),
	('Buffalo Bills',5,2),
	('Carolina Panthers',1,6),
	('Chicago Bears',4,2),
	('Cincinnati Bengals',3,4),
	('Cleveland Browns',1,6),
	('Dallas Cowboys',3,3),
	('Denver Broncos',4,3),
	('Detroit Lions',5,1),
	('Green Bay Packers',5,2),
	('Houston Texans',5,2),
	('Indianapolis Colts',4,3),
	('Jacksonville Jaguars',2,5),
	('Kansas City Chiefs',6,0),
	('Los Angeles Chargers',3,3),
	('Los Angeles Rams',2,4),
	('Las Vegas Raiders',2,5),
	('Miami Dolphins',2,4),
	('Minnesota Vikings',5,1),
	('New England Patriots',1,6),
	('New Orleans Saints',2,5),
	('New York Giants',2,5),
	('New York Jets',2,5),
	('Philadelphia Eagles',4,2),
	('Pittsburgh Steelers',5,2),
	('San Francisco 49ers',3,4),
	('Seattle Seahawks',4,3),
	('Tampa Bay Buccaneers',4,3),
	('Tennessee Titans',1,5),
	('Washington Commanders',5,2);