CREATE DATABASE userData;
USE userData;

CREATE TABLE user (  
  id INT NOT NULL AUTO_INCREMENT,
  Fname VARCHAR(65) NOT NULL,
  Lname VARCHAR(65) NOT NULL,
  email VARCHAR(65) NOT NULL,
  username VARCHAR(65) NOT NULL,
  password VARCHAR(65) NOT NULL,
  primary key (id)
  );

CREATE TABLE followedPlayers (  
  id INT,
  playerID INT NOT NULL,
  playerName VARCHAR(65) NOT NULL,
  playerTeam VARCHAR(65) NOT NULL,
  FOREIGN KEY (id) REFERENCES user(id)
);

CREATE TABLE Players (  
  id INT,
  playerid INT NOT NULL,
  playerName VARCHAR(65) NOT NULL,
  playerTeam VARCHAR(65) NOT NULL,
  FOREIGN KEY (id) REFERENCES followedPlayers(id)
);

CREATE TABLE recordedGames (
  id INT NOT NULL AUTO_INCREMENT,
  season INT NOT NULL,
  teamA VARCHAR(65) NOT NULL,
  teamB VARCHAR(65) NOT NULL,
  teamAScore INT,
  teamBScore INT,
  primary key (id)
);
CREATE TABLE liveGameData (  
  id INT NOT NULL AUTO_INCREMENT,
  season INT NOT NULL,
  team1 VARCHAR(65) NOT NULL,
  team2 VARCHAR(65) NOT NULL,
  player VARCHAR(65) NOT NULL,
  action VARCHAR(65) NOT NULL,
  point INT,
  playerTeam VARCHAR(65) NOT NULL,
  primary key(id)
);

CREATE TABLE recentGameData (  
  id INT,
  play INT NOT NULL AUTO_INCREMENT,
  season VARCHAR(65) NOT NULL,
  team1 VARCHAR(65) NOT NULL,
  team2 VARCHAR(65) NOT NULL,
  player VARCHAR(65) NOT NULL,
  action VARCHAR(65) NOT NULL,
  point INT,
  playerTeam VARCHAR(65) NOT NULL,
  FOREIGN KEY (id) REFERENCES recordedGames(id)
);
