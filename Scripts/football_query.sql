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
  id INT,
  playerTeam VARCHAR(65) NOT NULL,
  playerName VARCHAR(65) NOT NULL,
  playerNumber INT not null,
  playerPosition Varchar(4) not Null,
  FOREIGN KEY (id) REFERENCES teams(teamId)
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

CREATE TABLE recordedGames (
  id INT NOT NULL AUTO_INCREMENT,
  season INT NOT NULL,
  teamA VARCHAR(65) NOT NULL,
  teamB VARCHAR(65) NOT NULL,
  teamAScore INT,
  teamBScore INT,
  primary key(id)
);

CREATE TABLE liveGameData (  
  gameID INT NOT NULL AUTO_INCREMENT,
  season INT NOT NULL,
  team1 VARCHAR(65) NOT NULL,
  team2 VARCHAR(65) NOT NULL,
  player VARCHAR(65) NOT NULL,
  actions VARCHAR(65) NOT NULL,
  time VARCHAR(65) NOT NULL,
  points INT NOT NULL,
  playerTeam VARCHAR(65) NOT NULL,
  FOREIGN KEY (gameID) REFERENCES recordedGames(id)
);

insert into user  (email, username, password)
values('sample123@gmail.com', 'sampleUser', '123456');
 
