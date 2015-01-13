<?php


class CMovieAdmin {
	
	
	//private $acronym;
	private $db;
	private $title;
	private $director;
	private $length;
	private $year2;
	private $plot;
	private $image;
	private $subtext;
	private $speech;
	private $quality;
	private $price;
	private $imdb;
	private $youtube;
	private $id;
	private $genre;
	
	public function __construct($db, $title = null, $director = null, $length = null, $year2 = null, $plot = null, $image = null, $subtext = null, $speech = null, $quality = null, $price = null, $imdb = null, $youtube = null, $id = null, $genre = null) {
		$this->db = $db;
		$this->title = $title;
		$this->director = $director;
		$this->length = $length;
		$this->year2 = $year2;
		$this->plot = $plot;
		$this->image = $image;
		$this->subtext = $subtext;
		$this->speech = $speech;
		$this->quality = $quality;
		$this->price = $price;
		$this->imdb = $imdb;
		$this->youtube = $youtube;
		$this->id = $id;
		$this->genre = $genre;
	}
	
	
	public function resetDatabase() {
		$sql = "

		--
		-- Drop all tables in the right order.
		--
		DROP TABLE IF EXISTS Movie2Genre;
		DROP TABLE IF EXISTS Genre;
		DROP TABLE IF EXISTS Movie;
			
		CREATE TABLE Movie
		(
		  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
		  title VARCHAR(100) NOT NULL,
		  director VARCHAR(100),
		  length INT DEFAULT NULL, -- Length in minutes
		  year2 INT NOT NULL DEFAULT 1900,
		  plot TEXT, -- Short intro to the movie
		  image VARCHAR(100) DEFAULT NULL, -- Link to an image
		  subtext CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
		  speech CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
		  quality CHAR(3) DEFAULT NULL,
		  format2 CHAR(3) DEFAULT NULL, -- mp4, divx, etc
		  price INT NOT NULL DEFAULT 100,
		  imdb VARCHAR(100) DEFAULT NULL, -- Link to imdb
		  youtube VARCHAR(100) DEFAULT NULL, -- Link to youtube trailer
		  updated DATETIME
		) ENGINE INNODB CHARACTER SET utf8;
		 
		 
		SHOW CHARACTER SET;
		SHOW COLLATION LIKE 'utf8%';
		 
		DELETE FROM Movie;
		 
		INSERT INTO Movie (title, director, length, year2, plot, image, imdb, youtube, subtext, speech, quality) VALUES
		  ('Pulp fiction', 'Quentin Tarantino', 154, 1994, 'En spektakulär mix av explosiv action och humor fick kritikerna på knä som hyllade filmen som den största filmhändelsen under 1994.Författaren/regissören Quentin Tarantino ger oss en oförglömlig samling av karaktärer, som billiga lönnmördare, en maffiaboss sexiga fru och en desperat prisjägare i en vild, underhållande film Med sitt nya sätt att berätta kom filmen att sätta standard för en helt ny genre.', 'movie/pulp-fiction.jpg', 'http://www.imdb.com/title/tt0110912/', 'https://www.youtube.com/watch?v=ewlwcEBTvcg', 'swe', 'eng', 'HD'),
		  ('American Pie', 'Paul Weitz', 95,  1999, 'Jim är inte bara oskuld, han är dessutom desperat att bli av med den. Hans far, som är en ovanligt förstående sådan, gör allt för att på bästa pedagogiska vis ge sin son en lektion i ämnet sex. Till sin hjälp tar han förutom diverse porrtidningar även en samling anatomiska uttryck från 50-talet. Jim lyckas till slut få med sig skolans utbytesstudent Nadia till sitt pojkrum där han i ett tafatt försök att förföra henne gör bort sig ganska så ordentligt... och allting sänds ut via internet till hans skolkompisars stora glädje. Men skam den som ger sig tänker Jim och använder sedan sin mors nybakade paj till något helt annat än efterrätt...  American Pie  är en fullkomligt galen film om hur det är att vara ung och nyfiken. Vi får följa Jim och hans kompisar under en hektisk tid i skolan då läxor knappast är det första de tänker på... Och huvudet är knappast det första de tänker med.', 'movie/american-pie.jpg', 'http://www.imdb.com/title/tt0163651/?ref_=nv_sr_2', 'https://www.youtube.com/watch?v=iUZ3Yxok6N8', 'swe', 'eng', 'HD'),
		  ('Kopps', 'Josef Fares', 87, 2003, 'KOPPS är en komedi som utspelar sig i en liten svensk småstad. I berättelsen möter vi ett gäng arbetskamrater vid den lokala polisstationen. Här har inte skett ett allvarligt brott de senaste tio åren. Så plötsligt en dag hotas stationen av nedläggning. Nu måste de komma på ett sätt att rädda situationen', 'movie/kopps.jpg', 'http://www.imdb.com/title/tt0339230/?ref_=nv_sr_1', 'https://www.youtube.com/watch?v=aJFdePDqKrY', 'swe', 'swe', 'HD'),
		  ('From Dusk Till Dawn', 'Robert Rodriguez', 109, 1996, 'Bröderna Seth och Richard Gecko står på listan för Amerikas mest eftersökta brottslingar efter en rad väpnade bankrån. De är på väg till Mexiko där de har blivit lovade fristad av en kontakt, Carlos. Ett besök i en spritbutik slutar med att de dödar ägaren. Senare tar de en kvinnlig gisslan och tar in på ett motell. Medan Seth är ute för att proviantera våldtar och dödar Richard kvinnan. Seth blir rasande.På motellet finns också prästen Jacob Fuller med sina två barn, Kate och Scott. Bröderna tar trion som gisslan. Alla fem lyckas ta sig över gränsen till Mexiko i Jacobs husbil och hamnar nu intet ont anande på den avtalade mötesplatsen The Titty Twister Bar, ett ställe för chaufförer. Senare på kvällen uppträder en vacker kvinna som plötsligt förvandlas till en vampyr och dödar Richard. Det visar att flera av bargästerna är vampyrer och en långdragen blodig kamp äger rum. Seth och Kate blir de enda överlevande och möter Carlos.', 'movie/from-dusk-till-dawn.jpg', 'http://www.imdb.com/title/tt0116367/?ref_=nv_sr_1', 'https://www.youtube.com/watch?v=Wgk_SAbC5_0', 'swe', 'eng', 'HD'),
		  ('The Equalizer', 'Antoine Fuqua', 132, 2014, 'Robert McCall är en före detta hemlig specialagent som förfalskat sin egen död för att få leva ett lugnt liv i Boston. När en ung flicka vid namn Teri behöver räddning bestämmer han sig för att ge sig tillbaka in i hettan, och hamnar öga mot öga med ryska gangsters.', 'movie/equalizer.jpg', 'http://www.imdb.com/title/tt0455944/?ref_=nv_sr_2', 'https://www.youtube.com/watch?v=Qt0GkVZK8zA', 'swe', 'eng', 'HD'),
		  ('Sin City: A Dame to Kill For', 'Frank Miller, Robert Rodriguez', 102, 2014, 'Välkomna till Basin City, en stad där de flesta låtit hoppet fara och enda trösten är stripporna och spriten på Kadies Club Pecos. Där dansar bland andra Nancy som dricker i logen och på catwalken för att glömma pojkvännen Hartigans tragiska död. Här sitter ensamvargen Marv i baren, en bjässe som bara en mor kan älska, som beskyddar stripporna och de prostituerade som frekventerar haket i jakt på nya kunder. I det bakre hemliga rummet sitter senatorn Roark med bland andra den köpta polischefen Liebowitz och spelar poker och stackars den som vinner över den korrupte politikern. In på Kadies i Old Town kommer några nya ensamma själar som alla har ett speciellt mål i sikte och de är beredda att gå över lik för att nå dit de vill.', 'movie/sincity.jpg', 'http://www.imdb.com/title/tt0458481/?ref_=nv_sr_1', 'https://www.youtube.com/watch?v=nqRRF5y94uE', 'swe', 'eng', 'HD'),
		  ('I, Robot', 'Alex Proyas', 115, 2004, 'Året är 2035. Robotar är programmerade att leva i perfekt harmoni med människan, de tvättar våra kläder, styr våra flygplan och passar våra barn. Det råder full tillit och robotarna styrs av tre lagar som hindrar dem från att skada människan. När en forskare på US Robotics mördas blir detektiv Del Spooner (Will Smith) inkallad för att reda ut fallet. Tillsammans med psykologen dr. Susan Calvin (Bridget Moynahan) närmar sig den fasansfulla sanningen. Bevisen pekar på att en robot är inblandad i mordet. Medan tiden blir knapp, tvingas de in i en spännande kamp för sina egna liv och hela mänsklighetens existens. Den erkänt visuelle Alex Proyas regisserar filmen som är inspirerad av science-fiction-författaren Isaac Asimovs bok.', 'movie/irobot.jpg', 'http://www.imdb.com/title/tt0343818/?ref_=nv_sr_1', 'https://www.youtube.com/watch?v=rL6RRIOZyCM', 'swe', 'eng', 'HD'),
		  ('Iron Man', 'Jon Favreau', 126, 2008, 'Som VD för Stark Industries, en vapenindustri med kontrakt från den amerikanska staten, har Tony Stark i det närmaste uppnått stjärnstatus. Under decennier har han rest världen runt och försvarat amerikanska intressen. Men Tonys sorglösa livsstil förändras för alltid när den militärkonvoj som han färdas i attackeras och han tas som gisslan av en grupp rebeller. Han skadas också svårt vid attacken, ett granatsplitter tränger djupt in i hans kropp och hotar hans redan försvagade hjärta. Samtidigt beordras han av den mystiske rebelledaren Raza att bygga ett helt nytt vapensystem. Men istället för att uppfylla Razas önskan, bygger Tony en metalldräkt åt sig själv, som håller honom vid liv, samtidigt som den hjälper honom att fly från rebellerna.Tillbaka i USA måste Tony konfronteras med sitt förflutna, ta kontrollen över sitt företag och förändra Stark Industries.', 'movie/ironman.jpg', 'http://www.imdb.com/title/tt0371746/?ref_=nv_sr_2', 'https://www.youtube.com/watch?v=bK_Y5LjSJ-Y', 'swe', 'eng', 'HD'),
		  ('Interstellar', 'Christopher Nolan', 169, 2014, 'I en nära framtid när jorden är på väg att dö upptäcks en mystisk spricka i rumtiden som öppnar möjligheter för människan att undersöka nya platser att överleva på. Den före detta NASA-ingenjören och skickliga piloten Cooper, numera jordbrukare som de flesta andra, tvingas lämna sina barn bakom sig när det visar sig att han är bäst lämpad att leda uppdraget ut i det okända.', 'movie/interstellar.jpg', 'http://www.imdb.com/title/tt0816692/?ref_=nv_sr_1', 'https://www.youtube.com/watch?v=-gieJQejbHQ', 'swe', 'eng', 'HD'),
		  ('Fury', 'David Ayer', 134, 2014, 'En stridserfaren sergeant som går under namnet Wardaddy leder en Sherman-stridsvagn och hennes femmansbesättning på ett dödligt uppdrag bakom fiendens front. Wardaddy och hans män slåss emot oddsen i sina försök att angripa Nazityskland inifrån.', 'movie/fury.jpg', 'http://www.imdb.com/title/tt2713180/?ref_=fn_al_tt_1', 'https://www.youtube.com/watch?v=-OGvZoIrXpg', 'swe', 'eng', 'HD'),
		  ('Jack Reacher', 'Christopher McQuarrie', '130', '2012', 'När en ensam prickskytt tar livet av fem personer med sex skott pekar alla bevis på den person som polisen anhållit. I förhören har den misstänkte enbart en sak att säga: ”Hämta Jack Reacher”. Under den dramatiska jakten på sanningen ställs Jack Reacher mot en oväntad fiende som både är extremt våldsam och bär på en hemlighet. Bygger på Lee Childs bok ”Prickskytten”.', 'movie/jackreacher.jpg', 'http://www.imdb.com/title/tt0790724/?ref_=fn_al_tt_1', 'https://www.youtube.com/watch?v=kK7y8Ou0VvM','swe', 'eng', 'HD' ),
		  ('Avengers', 'Joss Whedon', '143', '2012', 'När Jordens befolkning hotas av den lömske Loki (Hiddleston) så måste Nick Fury (L. Jackson) samla alla krafter för att inleda operation Avengers. En plan som går ut på att samla alla de unika krafter som fredsstyrka S.H.E.I.L.D. samlat på sig genom åren. Black Widow (Johansson) och Hawkeye (Renner) leder tillsammans med Nick Fury ett rykreterings uppdrag för att få hjältarna Thor, Captain America och Iron Man att arbeta tillsammans med Hulken för att stoppa hotet från Thors bror Loki och rädda världen fårn total underkastelse av Lokis armé.', 'movie/avengers.jpg', 'http://www.imdb.com/title/tt0848228/?ref_=fn_al_tt_1', 'http://www.imdb.com/video/imdb/vi1891149081/?ref_=tt_ov_vi', 'swe', 'eng', 'HD')
	
		;
		 
		SELECT * FROM Movie;

		--
		-- Add tables for genre
		--
		DROP TABLE IF EXISTS Genre;
		CREATE TABLE Genre
		(
		  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
		  name CHAR(20) NOT NULL -- crime, svenskt, college, drama, etc
		) ENGINE INNODB CHARACTER SET utf8;
		 
		INSERT INTO Genre (name) VALUES 
		  ('comedy'), ('romance'), ('college'), 
		  ('crime'), ('drama'), ('thriller'), 
		  ('animation'), ('adventure'), ('family'), 
		  ('svenskt'), ('action'), ('horror'), 
		  ('science-fiction'),('war')
		;
		 
		DROP TABLE IF EXISTS Movie2Genre;
		CREATE TABLE Movie2Genre
		(
		  idMovie INT NOT NULL,
		  idGenre INT NOT NULL,
		 
		  FOREIGN KEY (idMovie) REFERENCES Movie (id),
		  FOREIGN KEY (idGenre) REFERENCES Genre (id),
		 
		  PRIMARY KEY (idMovie, idGenre)
		) ENGINE INNODB;
		 
		 
		INSERT INTO Movie2Genre (idMovie, idGenre) VALUES
		  (1, 1),
		  (1, 5),
		  (1, 6),
		  (2, 1),
		  (2, 2),
		  (2, 3),
		  (3, 11),
		  (3, 1),
		  (3, 10),
		  (3, 9),
		  (4, 11),
		  (4, 4),
		  (4, 12),
		  (5, 6),
		  (5, 11),
		  (5, 4),
		  (6, 6),
		  (6, 11),
		  (6, 4),
		  (7, 11),
		  (7, 13),
		  (8, 11),
		  (8, 13),
		  (8, 8),
		  (9, 5),
		  (9, 13),
		  (10, 11),
		  (10, 14),
		  (10, 5),
		  (11, 11),
		  (11, 4),
		  (11, 6),
		  (12, 11),
		  (12, 13),
		  (12, 8)
		;
		 
		DROP VIEW IF EXISTS VMovie;
		 
		CREATE VIEW VMovie
		AS
		SELECT 
		  M.*,
		  GROUP_CONCAT(G.name) AS genre
		FROM Movie AS M
		  LEFT OUTER JOIN Movie2Genre AS M2G
			ON M.id = M2G.idMovie
		  LEFT OUTER JOIN Genre AS G
			ON M2G.idGenre = G.id
		GROUP BY M.id
		;

		";

		$this->db->ExecuteQuery($sql);
	}
	
	public function createMovie($title) {
		  $sql = 'INSERT INTO Movie (title) VALUES (?)';
		  $this->db->ExecuteQuery($sql, array($title));
		  header('Location: edit_movie.php?id=' . $this->db->LastInsertId());
		  exit;
	}
	
	public function removeMovie($id) {
		$sql = 'DELETE FROM Movie2Genre WHERE idMovie = ?';
		$this->db->ExecuteQuery($sql, array($id));

		$sql = 'DELETE FROM Movie WHERE id = ? LIMIT 1';
		$this->db->ExecuteQuery($sql, array($id));
		
		header('Location: admin.php');
	}
	
	public function editMovie() {
		$sql = '
			UPDATE Movie SET 
				title  = ?, 
				director = ?,
				length = ?, 
				year2 = ?,
				plot = ?,
				image  = ?, 
				subtext = ?,
				speech = ?,
				quality = ?,
				price = ?,
				imdb = ?,
				youtube = ?,
				updated = NOW() 
			WHERE  
				id = ?
		';
		$params = array($this->title, $this->director, $this->length, $this->year2, $this->plot,$this->image, $this->subtext, $this->speech, $this->quality, $this->price, $this->imdb, $this->youtube, $this->id);
		$this->db->ExecuteQuery($sql, $params);
		
		$genres = explode(",", $this->genre);
		
		
		$sql = "DELETE from Movie2Genre WHERE idMovie = {$this->id};"; 
		$this->db->ExecuteSelectQueryAndFetchAll($sql); 
		
		foreach($genres as $genre)   
		{
			$genreId = $this->getGenreId($genre); 
			$sql = "INSERT INTO Movie2Genre (idMovie, idGenre) VALUES (?, ?);"; 
			$this->db->ExecuteSelectQueryAndFetchAll($sql, array($this->id, $genreId)); 
		}  
		
	}
	
	public function getGenreId($name) { 
		$sql = "SELECT * FROM Genre  WHERE name = ?;";
		$params = array($name);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		return $res[0]->id;
	} 
	
	/** 
     * Get all content as a list 
     *  
     * @return string html code for list with items 
     */ 	
	public function showAllContent()
	{
		$sql = "SELECT * FROM VMovie";
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		
		if($res)
		{
			$list = null;
			foreach($res as $key => $val)
			{
				$list .= "<li>". htmlentities($val->title, null, 'UTF-8') . " (<a href='edit_movie.php?id={$val->id}'>editera</a> <a href=movie.php?id={$val->id}>visa</a> <a href='delete_movie.php?id={$val->id}'>ta bort</a>)</li>\n";
			}
		}
		return $list;
	}

}