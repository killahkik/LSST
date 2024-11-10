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
  primary key (teamID)
);

CREATE TABLE followedPlayers (  
  id INT,
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
  gameTime TIME,
  season VARCHAR(50)
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

insert into user  (email, username, password)
values('sample123@gmail.com', 'sampleUser', '123456');

insert into user  (email, username, password)
values('s12345@gmail.com', 'user2Ex', '123456');
 
